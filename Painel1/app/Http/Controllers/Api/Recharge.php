<?php

namespace App\Http\Controllers\Api;

use App\Models\Character;
use App\Models\ChargeMoney;
use App\Models\DropItem;
use App\Models\Game\User\AchievementData;
use App\Models\Game\User\UserRank;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductCode;
use App\Models\ProductReward;
use App\Models\Server;
use App\Models\User;
use App\Models\UserGoods;
use App\Models\UserMessages;
use App\Models\UserReferrals;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Utils\PicPay;
use Core\Utils\Telegram;
use Core\Utils\Wsdl;
use MercadoPago;
use stdClass;

class Recharge extends Api
{
    /**
     * This function is called when a payment is made.
     * It checks if the payment was successful and if so, it creates a recharge for the user.
     * If the payment was not successful, it sets the invoice state to the payment status
     * @param Request request The request object.
     * @param method The payment method used.
     * @return The return is a JSON object with the following structure:
     */
    public function setNotification(Request $request, $method)
    {
        if ($method == 'mercadopago') {
            $payment = $this->mpNotification($request);
        } else {
            $payment = $this->picpayNotification();
        }

        //find invoice
        if (!$invoice = Invoice::where('reference', $payment->reference)->first()) {
            // TODO: send telegram critical error
            return [
                'state' => false,
                'type' => 'invoice_not_found'
            ];
        }
        
        if ($payment->status == 'completed') {
            $payment->status = 'approved';
        }

        if ($payment->status != 'approved') {
            $invoice->state = $payment->status;
            $invoice->invoiceid = $payment->id;
            $invoice->save();
            return [
                'state' => true,
                'message' => 'Sucess!'
            ];
        }

        $invoice->state = $payment->status;
        $invoice->invoiceid = $payment->id;
        $invoice->paid_at = date('Y-m-d H:i:s');

        $product = Product::find($invoice->pid);

        if ($product->type == 3 && $this->sendLaboratory($invoice)) {
            $invoice->sent = 1;
        }

        if ($product->type == 1 && $this->createRecharge($invoice, $payment)) {
            $invoice->sent = 1;
            if ($product->reward && !$this->sendReward($invoice)) {
                //send telegram message [recarga enviada com cupons mas falhou ao enviar itens]
            }
        }

        if (!$invoice->save()) {
            // TODO: send telegram critical error
            return;
        }

        header("HTTP/1.1 200 OK");

        return [
            'state' => true,
            'message' => 'Sucess!'
        ];
    }

    /**
     * This function is called when a payment is made. It will check the status of the payment and if
     * it is paid, it will update the database with the payment details
     * @param Request request The request object.
     * @return The payment status and the payment reference.
     */
    protected function mpNotification(Request $request)
    {
        $queryParams = $request->get();
        $id = $queryParams['id'];

        MercadoPago\SDK::setAccessToken($_ENV['MERCADOPAGO_ACCESS_TOKEN']);

        //find payment detail
        $payment = MercadoPago\Payment::find_by_id($id);

        $data = new \stdClass();

        $data->status = $payment->status;
        $data->reference = $payment->external_reference;
        $data->id = $payment->id;
        $data->payment_method_id = $payment->payment_method_id;

        return $data;
    }

    /**
     * This function is called when a payment is made. It will return a status of the payment
     * @return The data object contains the status of the payment, the reference number and the
     * authorization id.
     */
    protected function picpayNotification()
    {
        //start picpay instance
        $picpay = new PicPay($_ENV['PICPAY_TOKEN'], $_ENV['PICPAY_SELLER_TOKEN']);

        $payment = $picpay->notification();

        $data = new \stdClass();
        $data->status = $payment->status != 'paid' ? $payment->status : 'approved';
        $data->reference = $payment->referenceId;
        $data->id = $payment->authorizationId;

        return $data;
    }

    /**
     * It creates a new record in the Charge_Money table.
     * @param object invoice The invoice object.
     * @return The return value is a boolean value. If the method is successful, it returns true.
     * Otherwise, it returns false.
     */
    protected function createRecharge(object $invoice, object $payment): bool
    {
        $server = Server::find($invoice->sid);
        $user = User::find($invoice->uid);
        $product = Product::find($invoice->pid);

        if (!$server or !$user or !$product) {
            return false;
        }

        //find character
        $modelChar = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        if (!$char = $modelChar->where('UserName', $user->u_hash)->first()) {
            return false;
        }

        // $this->sendReferralPoint(
        //     $server,
        //     $user,
        //     $invoice,
        //     $product
        // );
        // die;

        $modelMoney = (new ChargeMoney())->setTable($server->dbUser . '.dbo.Charge_Money');
        $chargeMoney = $modelMoney->where('ChargeID', $invoice->reference)->first();
        if ($chargeMoney) {
            return false;
        }

        $cupons = $product->ammount;

        if (
            isset($payment->payment_method_id) &&
            $payment?->payment_method_id == 'pix' &&
			$cupons >= 10
        ) {
            $bonus = $cupons * (10 / 100);
            $cupons = $cupons + $bonus;
        }

        $modelMoney->ChargeID = $invoice->reference;
        $modelMoney->UserName = $user->u_hash;
        $modelMoney->Money = (int)$cupons;
        $modelMoney->Date = date('Y-m-d H:i:s');
        $modelMoney->CanUse = 1;
        $modelMoney->PayWay = $invoice->method;
        $modelMoney->NeedMoney = number_format($product->value, 2);
        $modelMoney->IP = '0.0.0.0';
        $modelMoney->NickName = $char->NickName;

        if (!$modelMoney->save()) {
            return false;
        }


        if ($payment?->payment_method_id == 'pix') {
            $messageModel = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
            if (!$messageModel->create([
                'SenderID' => 0,
                'Sender' => 'Sistema',
                'ReceiverID' => $char->UserID,
                'Receiver' => $char->NickName,
                'Title' => '🤑 Bônus de recarga',
                'Content' => "Ola jogador você efetuou uma recarga utilizando o método [PIX] e recebeu {$bonus} de cupons como bônus.",
                'IsRead' => 0,
                'IsDelR' => 0,
                'IfDelS' => 0,
                'IsDelete' => 0,
                'Annex1' => 0,
                'Annex2' => 0,
                'Annex3' => 0,
                'Annex4' => 0,
                'Annex5' => 0,
                'Gold' => 0,
                'Money' => 0,
                'IsExist' => 1,
                'Type' => 51,
                'Remark' =>
                "Gold:0,Money:0,Annex1:0,Annex2:0,Annex3:0,Annex4:0,Annex5:0,GiftToken:0"
            ])) {
                // TODO: Send telegram error
            }
        }

        //send wsdl recharge
        if ($server->wsdl != '') {
            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'ChargeMoney';
            $wsdl->paramters = [
                "userID" => (int) $char->UserID,
                "chargeID" => $invoice->reference,
            ];
            if (!$wsdl->send()) {
                // TODO: send telegram critical error
            }
        }

        $this->sendReferralPoint(
            $server,
            $user,
            $invoice,
            $product
        );

        //check if invoice is a code and if so, update the code used
        if ($invoice->code) {
            ProductCode::where('code', $invoice->code)->increment('used');
        }

        (new Telegram())->sendMessage(view('telegram.payment', [
            'method' => $invoice->method,
            'value' => str_price($invoice->value),
            'amount' => $product->ammount,
            'nickname' => $char->NickName,
            'server' => $server->name,
        ]));

        return true;
    }


    /**
     * Send a reward to a user
     * @param object invoice the invoice object
     * @return The return value is the result of the function.
     */
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

    protected function sendLaboratory(object $invoice): bool
    {
        $sid = $invoice->sid;
        $uid = $invoice->uid;

        $laboratoryDrops = [
            10000 => [
                'name' => 'Distância de tela Fácil'
            ],
            10001 => [
                'name' => 'Distância de tela Médio'
            ],
            10002 => [
                'name' => 'Distância de tela Avançado'
            ],
            10010 => [
                'name' => 'Estratégia de ângulo 20° Fácil'
            ],
            10011 => [
                'name' => 'Estratégia de ângulo 20° Médio'
            ],
            10012 => [
                'name' => 'Estratégia de ângulo 20° Avançado'
            ],
            10020 => [
                'name' => 'Estratégia de ângulo 65° Fácil'
            ],
            10021 => [
                'name' => 'Estratégia de ângulo 65° Médio'
            ],
            10022 => [
                'name' => 'Estratégia de ângulo 65° Avançado'
            ],
            10030 => [
                'name' => 'Estratégia de lançamento alto Fácil'
            ],
            10031 => [
                'name' => 'Estratégia de lançamento alto Médio'
            ],
            10032 => [
                'name' => 'Estratégia de lançamento alto Avançado'
            ],
            10040 => [
                'name' => 'Estratégia de lançamento avançada Fácil'
            ],
            10041 => [
                'name' => 'Estratégia de lançamento avançada Médio'
            ],
            10042 => [
                'name' => 'Estratégia de lançamento avançada Avançado'
            ],
        ];

        //find server
        $server =  Server::find($sid);
        if (!$server) {
            return false; //'Server not found'
        }

        //find character
        if (!$user = User::find($uid)) {
            return false; //usuario nao encontrado
        }
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $character = $model->where('UserName', $user->u_hash)->first();
        if (!$character) {
            //personagem não encontrado
            return false;
        }

        //check if user is online
        if ($character->State == 1) {
            if ($server->wsdl == '') {
                //'O usuário está online e o servidor não possui WSDL, por favor, desconecte-o.'
                return false;
            }

            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'KitoffUser';
            $wsdl->paramters = [
                "playerID" => (int) $character->UserID,
                "msg" => "Você foi desconectado do servidor, pela administração do sistema."
            ];
            $wsdl->send();
            sleep(1);
        }

        $positions = str_split($character->FightLabPermission);

        $index = [1, 3, 5, 7, 9];

        $Needed = [];

        for ($y = 0; $y <  sizeof($index); $y++) {
            for ($x = (int)$positions[$index[$y]]; $x < 3; $x++) {
                $Needed[] = 10000 + (10 * $y) + $x;
            }
        }

        $gp = 0;
        $gift = 0;

        foreach ($laboratoryDrops as $id => $drop) {
            if (!in_array($id, $Needed)) {
                continue;
            }

            //find drop list
            $drops = (new DropItem())
                ->setTable($server->dbData . '.dbo.Drop_Item')
                ->where('DropId', $id)
                ->get()?->toArray();
            if (!$drops) {
                continue;
            }

            $dropList = array_chunk($drops, 5);



            foreach ($dropList as $group) {
                $groupIds = [];

                //foreach group attachment
                foreach ($group as $attachment) {
                    $attachment = (object) $attachment;

                    if ($attachment->ItemId == 11107) {
                        $gp += $attachment->EndData;
                        continue;
                    }
                    if ($attachment->ItemId == -300) {
                        $gift += $attachment->EndData;
                        continue;
                    }

                    //create user goods
                    $goodsModel = (new UserGoods())->setTable($server->dbUser . '.dbo.Sys_Users_Goods');
                    $rewardGoods = $goodsModel->create([
                        'UserID' => $character->UserID,
                        'BagType' => 0,
                        'TemplateID' => $attachment->ItemId,
                        'Place' => -1,
                        'Count' => $attachment->EndData,
                        'IsJudge' => 1,
                        'Color' => null,
                        'IsExist' => 1,
                        'StrengthenLevel' => 0,
                        'AttackCompose' => 0,
                        'DefendCompose' => 0,
                        'LuckCompose' => 0,
                        'AgilityCompose' => 0,
                        'IsBinds' => $attachment->IsBind,
                        'BeginDate' => Carbon::now()->format('Y-m-d H:i:s.v'),
                        'ValidDate' => $attachment->ValueDate
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
                    'ReceiverID' => $character->UserID,
                    'Receiver' => $character->NickName,
                    'Title' => $drop['name'] ?? 'Recompensa do sistema',
                    'Content' => 'Parabéns por completar ' . $drop['name'] . '!',
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
                    "Gold:0,Money:0,Annex1:$annex1,Annex2:$annex2,
                    Annex3:$annex3,Annex4:$annex4,Annex5:$annex5,
                    GiftToken:0"
                ]);

                if (!$rewardMessage) {
                    return false;
                }
            }
        }

        if (
            !$character->update([
                'GP' => $character->GP + $gp,
                'GiftToken' => $character->GiftToken + $gift,
                'FightLabPermission' => 33333333333
            ])
        ) {
            //Erro ao completar laboratório
            return false;
        }

        //send achievement
        $achievements = [];
        $achievementData = (new AchievementData())->setTable($server->dbUser . '.dbo.AchievementData');

        for ($i = 3002; $i <= 3007; $i++) {
            if ($i == 3003) {
                continue;
            }

            $achievement = $achievementData->where('UserID', $character->UserID)->where('AchievementID', $i)->first();
            if ($achievement) {
                continue;
            }

            $achievements[] = [
                'UserID' => $character->UserID,
                'AchievementID' => $i,
                'IsComplete' => 1,
                'CompletedDate' => Carbon::now()->format('Y-m-d H:i:s.v')
            ];
        }

        $achievementData->insert($achievements);

        //send title
        $userRank = (new UserRank())->setTable($server->dbUser . '.dbo.Sys_User_Rank');
        $userRank->updateOrCreate(
            ['UserID' => $character->UserID, 'UserRank' => 'Menino Bom'],
            ['UserRank' => 'Menino Bom', 'IsExit' => 1]
        );

        (new Telegram())->sendMessage(view('telegram.payment', [
            'method' => $invoice->method,
            'value' => str_price($invoice->value),
            'name' => '🎯 Laboratório',
            'nickname' => $character->NickName,
            'server' => $server->name,
        ]));

        return true;
    }

    protected function sendReferralPoint(
        Server $server,
        User $user,
        Invoice $invoice,
        Product $product
    ): void {
        if (!$user->referenced || $user->referenced == '') return;

        //find referenced user
        if (!$userRef = User::where('reference', $user->referenced)->first()) {
            // TODO: Enviar mensagem telegram e/ou salvar log
            return;
        }

        $modelChar = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        if (!$char = $modelChar->where('UserName', $userRef->u_hash)->first()) {
            return;
        }

        $cupons = $product->ammount * (10 / 100);
        $chargeCode = str_hash(12);

        //send money 
        $modelMoney = (new ChargeMoney())->setTable($server->dbUser . '.dbo.Charge_Money');
        $modelMoney->ChargeID = $chargeCode;
        $modelMoney->UserName = $userRef->u_hash;
        $modelMoney->Money = $cupons;
        $modelMoney->Date = date('Y-m-d H:i:s');
        $modelMoney->CanUse = 1;
        $modelMoney->PayWay = $invoice->method;
        $modelMoney->NeedMoney = number_format($product->value, 2);
        $modelMoney->IP = '0.0.0.0';
        $modelMoney->NickName = $char->NickName;
        if (!$modelMoney->save()) {
            return;
        }

        $messageModel = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
        if (!$messageModel->create([
            'SenderID' => 0,
            'Sender' => 'Sistema de indicação',
            'ReceiverID' => $char->UserID,
            'Receiver' => $char->NickName,
            'Title' => '🌿 Prêmio de indicação',
            'Content' => "Ola {$char->NickName} um jogador indicado por você acaba de realizar uma recarga, você recebeu {$cupons} cupons através do sistema de indicação.",
            'IsRead' => 0,
            'IsDelR' => 0,
            'IfDelS' => 0,
            'IsDelete' => 0,
            'Annex1' => 0,
            'Annex2' => 0,
            'Annex3' => 0,
            'Annex4' => 0,
            'Annex5' => 0,
            'Gold' => 0,
            'Money' => 0,
            'IsExist' => 1,
            'Type' => 51,
            'Remark' =>
            "Gold:0,Money:0,Annex1:0,Annex2:0,Annex3:0,Annex4:0,Annex5:0,GiftToken:0"
        ])) {
            // TODO: Send telegram error
        }

        if ($server->wsdl != '') {
            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'ChargeMoney';
            $wsdl->paramters = [
                "userID" => (int) $char->UserID,
                "chargeID" => $chargeCode,
            ];
            $wsdl->send();
        }

        //send money
        $discountMoney = ceil((intval($product->value) * 20) / 100);
        $userRef->money += $discountMoney;
        $userRef->save();

        $modelReferrals = new UserReferrals;
        if (
            $referral = $modelReferrals
            ->where('uid', $userRef->id)
            ->where('rid', $user->id)
            ->first()
        ) {
            $referral->points += $discountMoney;
            $referral->money += $cupons;
            $referral->save();
            return;
        }

        $modelReferrals->create([
            'uid' =>  $userRef->id,
            'rid' =>  $user->id,
            'points' => $discountMoney,
            'money' => $cupons
        ]);
        return;
    }
}
