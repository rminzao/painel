<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\ChargeMoney;
use App\Models\Invoice as ModelsInvoice;
use App\Models\Product;
use App\Models\ProductReward;
use App\Models\Server;
use App\Models\User;
use App\Models\UserGoods;
use App\Models\UserMessages;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;

class Invoice extends Api
{
    /**
     * It returns a list of items from the database
     * @param Request request The request object.
     * @return The return is an array with the following keys:
     */
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $page = $post['page'] ?? 1;
        $search = $post['search'] ?? '';
        $onclick = $post['onclick'] ?? '';
        $state = $post['state'] ?? '';
        $uid = $post['uid'] ?? '';
        $limit = $post['limit'] ?? 10;

        //get servers
        $model = new ModelsInvoice();
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = $query->where('reference', 'LIKE', "%{$search}%")->orWhere('invoiceid', 'LIKE', "%{$search}%");
        }

        if (!in_array($state, ['', 0])) {
            $query = $query->where('state', $state);
        }

        if (!in_array($uid, ['', 0])) {
            $query = $query->where('uid', $uid);
        }

        $query = $query->orderBy('id', 'DESC');

        //start paginator instance
        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $items = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();

        foreach ($items as $_item) {
            $_item['created_at'] = date_fmt_br($_item['created_at']);
            $_item['updated_at'] = date_fmt_br($_item['updated_at']);
            $_item['paid_at'] = date_fmt_br($_item['paid_at']);
            $_item['user'] = User::find($_item['uid'])?->toArray();
            $_item['product'] = Product::find($_item['pid'])?->toArray();
            $_item['value'] = str_price($_item['value']);
            $_items[] = $_item;
        }

        return [
            'state' => true,
            'invoices' => $_items ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    /**
     * Create a new invoice
     *
     * @param Request request The request object.
     *
     * @return array The return is an array with two values: state and message.
     */
    public function create(Request $request): array
    {
        //get post data
        $post = $request->post(false);
        $check = $post;
        unset($check['note']);

        //verifica se algum dado está faltando
        if (in_array(null, $check)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos'
            ];
        }

        //verifica se o usuário existe
        $user = User::find($post['uid']);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        //verifica se o servidor existe
        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado'
            ];
        }

        //verifica se o produto existe
        $product = Product::find($post['pid']);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Produto não encontrado'
            ];
        }

        $model = new ModelsInvoice();
        $model->uid = $post['uid'];
        $model->pid = $post['pid'];
        $model->sid = $post['sid'];
        $model->state = isset($post['approved']) ? 'approved' : 'pending';
        $model->method = $post['method'];
        $model->value = str_replace(',', '.', $post['price']);
        $model->reference = str_hash(20);
        $model->sent = isset($post['send']) ? ($this->sendMoney($product, $user, $server, $post['method']) ? 1 : 0) : 0;
        $model->note = $post['note'] ?? '';
        $model->code = '';
        if ($post['approved']) {
            $model->paid_at = date('Y-m-d H:i:s');
        }

        if (!$model->save()) {
            return [
                'state' => false,
                'message' => 'Erro ao criar a fatura'
            ];
        }

        if (isset($post['send']) && $product->reward) {
            if (!$this->sendReward($model)) {
                return [
                    'state' => false,
                    'message' => 'A fatura foi criada, mas não foi possível enviar o prêmio'
                ];
            }
        }

        return [
            'state' => true,
            'message' => 'Fatura criada com sucesso'
        ];
    }

    /**
     * Update an invoice
     *
     * @param Request request The request object.
     *
     * @return array The invoice is being updated.
     */
    public function update(Request $request): array
    {
        $post = $request->post(false);

        $id = filter_var($post['id'], FILTER_VALIDATE_INT);

        $invoice = (new ModelsInvoice())->where('id', $id)->first();
        if (!$invoice) {
            return [
                'state' => false,
                'message' => 'Fatura não encontrada.'
            ];
        }

        //verifica se o usuário existe
        $user = User::find($invoice->uid);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        //verifica se o servidor existe
        $server = Server::find($invoice->sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado'
            ];
        }

        //verifica se o produto existe
        $product = Product::find($invoice->pid);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Produto não encontrado'
            ];
        }


        $invoice->state = $post['state'];
        $invoice->note = $post['note'] ?? '';
        if (isset($post['sent']) && $invoice->sent == 0) {
            $invoice->sent = ($this->sendMoney($product, $user, $server, $invoice->method) ? 1 : 0);
        }

        if (!$invoice->save()) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar a fatura.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Fatura atualizada com sucesso.'
        ];
    }

    /**
     * Send money to the user
     *
     * @param product The product that was bought.
     * @param user The user object.
     * @param server The server object.
     * @param method The method that will be used to send the money.
     *
     * @return bool The return value is an array with two elements:
     */
    protected function sendMoney($product, $user, $server, $method = 'desconhecido'): bool
    {
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $character = $model->select(['UserID', 'UserName', 'NickName'])->where('UserName', $user->u_hash)->first();
        if (!$character) {
            return false;
        }

        $hash = str_hash(20);

        $chargeMoney = (new ChargeMoney())->setTable($server->dbUser . '.dbo.Charge_Money');
        $chargeMoney->ChargeID = $hash;
        $chargeMoney->UserName = $character->UserName;
        $chargeMoney->Money = $product->ammount;
        $chargeMoney->Date = date('Y-m-d');
        $chargeMoney->CanUse = 1;
        $chargeMoney->PayWay = 'Administração (fatura) - ' . $method;
        $chargeMoney->NeedMoney =  number_format(0, 2);
        $chargeMoney->IP = '0.0.0.0';
        $chargeMoney->NickName = $character->NickName;
        if (!$chargeMoney->save()) {
            return [
                'state' => false,
                'message' => 'Erro interno com banco de dados.'
            ];
        }

        //send wsdl recharge
        if ($server->wsdl != '') {
            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'ChargeMoney';
            $wsdl->paramters = [
                "userID" => (int) $character->UserID,
                "chargeID" => $hash,
            ];
            if (!$wsdl->send()) {
                // TODO: send telegram critical error
            }
        }

        return true;
    }

    protected function sendReward(object $invoice): bool
    {
        $server = Server::find($invoice->sid);
        $user = User::find($invoice->uid);
        $rewards = ProductReward::where('pid', $invoice->pid)->get();
        if (!$server or !$user or !$rewards) {
            return false;
        }

        //find character
        $modelChar = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $char = $modelChar->where('UserName', $user->u_hash)->first();
        if (!$char) {
            return false;
        }

        $attachments = array_chunk($rewards?->toArray() ?? [], 5);

        foreach ($attachments as $group) {
            $groupIds = [];

            //foreach group attachment
            foreach ($group as $attachment) {
                $attachment = (object) $attachment;

                //create user goods
                $goodsModel = (new UserGoods())->setTable($server->dbUser . '.dbo.Sys_Users_Goods');
                $rewardGoods = $goodsModel->create([
                    'UserID' => $char->UserID,
                    'BagType' => 0,
                    'TemplateID' => $attachment->TemplateID,
                    'Place' => -1,
                    'Count' => $attachment->ItemCount,
                    'IsJudge' => 1,
                    'Color' => null,
                    'IsExist' => 1,
                    'StrengthenLevel' => $attachment->StrengthenLevel,
                    'AttackCompose' => $attachment->AttackCompose,
                    'DefendCompose' => $attachment->DefendCompose,
                    'LuckCompose' => $attachment->LuckCompose,
                    'AgilityCompose' => $attachment->AgilityCompose,
                    'IsBinds' => $attachment->IsBind,
                    'BeginDate' => Carbon::now()->format('Y-m-d H:i:s.v'),
                    'ValidDate' => $attachment->ItemValid
                ]);

                if (!$rewardGoods) {
                    return false;
                }

                //append item to groupids
                $groupIds[] = $rewardGoods->ItemID;
            }

            //create mail
            $annex1 = $groupIds[0] ?? 0;
            $annex2 = $groupIds[1] ?? 0;
            $annex3 = $groupIds[2] ?? 0;
            $annex4 = $groupIds[3] ?? 0;
            $annex5 = $groupIds[4] ?? 0;

            $messageModel = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
            $rewardMessage = $messageModel->create([
                'SenderID' => 0,
                'Sender' => 'Sistema',
                'ReceiverID' => $char->UserID,
                'Receiver' => $char->NickName,
                'Title' => 'Recompensa de recarga',
                'Content' => 'Ola jogador voce recebeu esta(s) recompensa(s) por sua recarga de cupons.',
                'IsRead' => 0,
                'IsDelR' => 0,
                'IfDelS' => 0,
                'IsDelete' => 0,
                'Annex1' => $annex1,
                'Annex2' => $annex2,
                'Annex3' => $annex3,
                'Annex4' => $annex4,
                'Annex5' => $annex5,
                'Gold' => 0,
                'Money' => 0,
                'IsExist' => 1,
                'Type' => 51,
                'Remark' =>
                "Gold:0,Money:0,Annex1:$annex1,Annex2:$annex2,Annex3:$annex3,Annex4:$annex4,Annex5:$annex5,GiftToken:0"
            ]);

            if (!$rewardMessage) {
                return false;
            }
        }

        //send wsdl recharge
        if ($server->wsdl != '') {
            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'MailNotice';
            $wsdl->paramters = [
                "playerID" => (int) $char->UserID
            ];
            if (!$wsdl->send()) {
                // TODO: send telegram critical error
            }
        }

        return true;
    }
}
