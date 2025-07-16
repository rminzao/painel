<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\Api;
use App\Models\Invoice as ModelsInvoice;
use App\Models\Product;
use App\Models\ProductCode;
use Core\Routing\Request;
use Core\Utils\PicPay;
use Core\View\Paginator;
use MercadoPago;
use stdClass;

class Invoice extends Api
{
    protected $errorMessage;

    public function list()
    {
        //filter and valid page request
        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        $invoice = ModelsInvoice::where('uid', '=', $this->user->id)->orderBy('created_at', 'desc');

        //start paginator instance
        $pager = new Paginator(url("api/invoice/list?page="), onclick: "invoice.list");
        $pager->pager($invoice->count(), 10, $page, 2);

        $invoices = $invoice->limit($pager->limit())
            ->offset($pager->offset())
            ->get()
            ?->toArray();

        foreach ($invoices as $item) {
            $product = Product::where('id', '=', $item['pid'])
                ->select('name', 'ammount')
                ->first()
                ?->toArray();

            $item['value'] = str_price($item['value']);
            $invoiceList[] = array_merge($item, ['product' => $product ?? [
                'name' => '❓ Desconhecido',
                'ammount' => 0
            ]]);
        }

        return [
            'invoices' => $invoiceList ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create()
    {
        $post = $this->request->get();
        $id =  $post['id'] ?? '';
        $method = $post['method'] ?? '';
        $code = $post['code'] ?? '';

        if (!$method or !$id) {
            return [
                'state' => false,
                'message' => 'Ops ocorreu um erro ao gerar sua fatura, tente novamente.',
            ];
        }

        //check if the method is allowed
        if (!in_array($method, ['picpay', 'mercadopago'])) {
            return [
                'state' => false,
                'message' => 'O método selecionado não é valido selecione outro e tente novamente.',
            ];
        }

        //find product by id
        $product = Product::find($id);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Opss, o produto que está tentando comprar não existe ou foi alterado,
                      atualize a página e tente novamente.',
            ];
        }

        if (request_limit("appApiInvoiceCreate", 10, 60 * 2)) {
            return [
                'state' => false,
                'message' => "Limite de requisições por minuto excedito. Por favor, aguarde 2 minutos para tentar novamente!"
            ];
        }

        //check and valid code promotion
        if ($code) {
            if (!$pCode = ProductCode::where('code', $code)->get()->first()) {
                return [
                    'state' => false,
                    'message' => 'O código promocional que você inseriu não é válido, tente novamente.',
                ];
            }

            //calculate discount
            $discount = $product->value * ($pCode->param1 / 100);
            $newValue = number_format($product->value - $discount);
            if ($newValue <= 0) {
                $newValue = 0.01;
            }
            $product->value = number_format($newValue, 2);
        }

        //set hash from reference id
        $reference = str_hash(18);

        //find invoice if any
        $obInvoice = ModelsInvoice::where('pid', $product->id)
            ->where('uid', $this->user->id)
            ->where('method', $method)
            ->where('state', 'pending')->first();

        //get invoice detail by method
        if ($method == 'picpay') {
            if ($obInvoice) {
                if ($code) {
                    $obInvoice->code = $code;
                }
                $obInvoice->reference = $reference;
                $obInvoice->save();
                $reference = $obInvoice->reference;
            }
            if (!$obInvoice) {
                $create = $this->build($product, $reference, $method, $code);
                $reference = $create->reference;
            }
            $paymentUrl = $this->createPicpay($product, $reference); #novo
        }

        if ($method == 'mercadopago') {
            if ($obInvoice) {
                $reference = $obInvoice->reference;
            }
            if (!$obInvoice) {
                $create = $this->build($product, $reference, $method, $code);
                $reference = $create->reference;
            }
            $paymentUrl = $this->createMercadopago($product, $reference);
        }

        if ($code && $obInvoice) {
            $obInvoice->code = $code;
            $obInvoice->save();
        }

        if (!$paymentUrl) {
            return [
                'state' => false,
                'message' => $this->errorMessage,
            ];
        }

        return [
            'state' => true,
            'paymentUrl' => $paymentUrl
        ];
    }

    private function createPicpay(object $product, string $reference)
    {
        //start picpay instance
        $picpay = new PicPay($_ENV['PICPAY_TOKEN'], $_ENV['PICPAY_SELLER_TOKEN']);

        //create item
        $item = new stdClass();
        $item->ref = $reference;
        $item->nome = $product->name;
        $item->valor = number_format($product->value, 2);
        $item->urlCallBack = url('api/recharge/picpay/notification'); #notification
        $item->urlReturn = url('app/me/account/invoices'); #invoice page

        //create buyer
        $buyer = new stdClass();
        $buyer->nome = $this->user->first_name;
        $buyer->sobreNome = $this->user->last_name;
        $buyer->cpf = '000.000.000-00';
        $buyer->email = $this->user->email;
        $buyer->telefone = '11999999999';

        //send a picpay request
        $preference = $picpay->request($item, $buyer);

        //check exist error message
        if (isset($preference->message)) {
            $this->errorMessage = $preference->message;
            return false;
        }

        //return invoice data create from picpay
        return $preference->paymentUrl;
    }

    private function createMercadopago(object $product, string $reference)
    {
        MercadoPago\SDK::setAccessToken($_ENV['MERCADOPAGO_ACCESS_TOKEN']);

        $item = new MercadoPago\Item();
        $preference = new MercadoPago\Preference();

        $item->title = $product->name;
        $item->quantity = 1;
        $item->unit_price = number_format($product->value, 2);

        $preference->items = [$item];
        $preference->notification_url = url('api/recharge/mercadopago/notification');
        $preference->external_reference = $reference;
        $preference->auto_return = 'all';
        $preference->payment_methods = [
            'default_payment_method_id' => 'pix'
            // 'excluded_payment_types' => [
            //     ["id" => "credit_card"],
            // ]
        ];
        $preference->back_urls = [
            'success' => url('app/me/account/invoices'),
            'failure' => url('app/me/account/invoices'),
            'pending' => url('app/me/account/invoices'),
        ];

        $preference->save();

        if($preference->error){
            $this->errorMessage = $preference->error->message;
        }
        
        return $preference->init_point;
    }

    private function build(object $product, string $reference, string $method, ?string $code = null): object|bool
    {
        $invoice = (new ModelsInvoice());
        $invoice->uid = $this->user->id;
        $invoice->pid = $product->id;
        $invoice->sid = $product->sid;
        $invoice->state = 'pending'; //approved
        $invoice->method = $method;
        $invoice->value = $product->value;
        $invoice->reference = $reference;
        $invoice->code = $code;

        if (!$invoice->save()) {
            return false;
        }

        return $invoice;
    }
}
