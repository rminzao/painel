<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\Product as ModelsProduct;
use App\Models\ProductCode;
use App\Models\ProductReward;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\ShopGoodsBox;
use Core\Routing\Request;

class Product extends Api
{
    public function getDetail(int $id): array
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return [
                'state' => false,
                'message' => 'Opss, ocorreu um erro com a solicitação, tente novamente mais tarde.',
            ];
        }

        $product = ModelsProduct::find($id);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Opss, ocorreu um erro com a solicitação, tente novamente mais tarde.',
            ];
        }

        //validate server
        $server = Server::find($product->sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Opss, ocorreu um erro com a solicitação, tente novamente mais tarde.',
            ];
        }

        $product = $product?->toArray();
        $product['value'] = str_price($product['value']);
        if ($product['reward']) {

            $rewards = ProductReward::where('pid', $product['id'])->get()?->toArray();
            foreach ($rewards as $reward) {
                //find item
                $item = (new ShopGoods($server->dbData))->find($reward['TemplateID'])?->toArray();
                if (!$item) {
                    continue;
                }
                $boxList = [];
                if (
                    ($item['CategoryID'] == 11 && $item['Property1'] == 6) ||
                    ($item['CategoryID'] == 11 && $item['Property1'] == 114)
                ) {
                    $boxList = (new ShopGoodsBox($server->dbData))->select('TemplateID', 'ItemCount')->where('ID', $reward['TemplateID'])->get()?->toArray();

                    foreach ($boxList as &$box) {
                        $itemBox = (new ShopGoods($server->dbData))
                            ->select('Name', 'Description')
                            ->where('TemplateID', $box['TemplateID'])
                            ->get()
                            ?->toArray();
                        if (!$itemBox) {
                            continue;
                        }

                        $box['Name'] = $itemBox[0]['Name'];
                        $box['Description'] = $itemBox[0]['Description'];
                        $box['Pic'] = image_item($box['TemplateID'], $server->dbData);
                    }
                }

                $rewardList[] = array_merge($reward, [
                    'Name' => $item['Name'],
                    'Description' => $item['Description'],
                    'NeedSex' => $item['NeedSex'],
                    'CategoryID' => $item['CategoryID'],
                    'box' => $boxList ?? [],
                    'Pic' => image_item($reward['TemplateID'], $server->dbData),
                ]);
            }
            $product['rewards'] = $rewardList;
        }

        return [
            'state' => true,
            'data' => $product
        ];
    }

    public function checkCode(Request $request): array
    {
        $post = $request->post();
        $code = $post['code'] ?? '';
        $pid = $post['pid'] ?? '';

        if (!$code || !$pid) {
            return [
                'state' => false,
                'message' => 'Você não inseriu nenhum código promocional.',
            ];
        }

        if (request_limit("appApiCheckCode", 10, 60 * 2)) {
            return [
                'state' => false,
                'message' => "Limite de requisições por minuto excedito. Por favor, aguarde 2 minutos para tentar novamente!"
            ];
        }

        $product = ModelsProduct::find($pid);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'O produto não existe.',
            ];
        }

        $pCode = ProductCode::where('code', $code)->first();
        if (!$pCode) {
            return [
                'state' => false,
                'message' => 'Código inválido.',
            ];
        }

        if ($pCode->limit > 0 && $pCode->limit <= $pCode->used) {
            return [
                'state' => false,
                'message' => 'Código já utilizado.',
            ];
        }

        if (!$pCode->state) {
            return [
                'state' => false,
                'message' => 'Código inválido.',
            ];
        }

        if ($pCode->sid != 0 && $pCode->sid != $product->sid) {
            return [
                'state' => false,
                'message' => 'Esse código não é válido para esse servidor.',
            ];
        }

        if (strtotime($pCode->start_at) > time()) {
            return [
                'state' => false,
                'message' => 'Código ainda não disponível.',
            ];
        }

        if (strtotime($pCode->expires_at) < time()) {
            return [
                'state' => false,
                'message' => 'Código expirado.',
            ];
        }

        if (!$pCode->repeat) {
            $invoices = Invoice::where([
                ['uid', $this->user->id],
                ['code', $code],
                ['state', 'approved']
            ])->first();

            if ($invoices) {
                return [
                    'state' => false,
                    'message' => 'Você já utilizou esse código em uma compra.',
                ];
            }
        }

        //calculate discount
        $discount = $product->value * ($pCode->param1 / 100);
        $newValue = number_format($product->value - $discount);
        if ($newValue <= 0) {
            $newValue = 0.01;
            $discount = $discount - $newValue;
        }

        return [
            'state'   => true,
            'message' => 'Sua promoção foi aplicada com sucesso, finalize a compra selecionando um método de pagamento.',
            'data'    => [
                'original' => str_price($product->value),
                'discount' => str_price($discount),
                'value'  => $newValue,
            ]
        ];
    }
}
