<?php

namespace App\Models\Game;

use App\Models\BaseGame;
use Illuminate\Support\Str;

class UserDetail extends BaseGame
{
    protected $table = '.dbo.Sys_Users_Detail';

    protected $primaryKey = 'UserID';

    protected $fillable = [];

    public function sendCharge($product, $order, $server)
    {
        $chargeID = Str::random(18);

        $modelMoney = (new UserDetail())->setTable($server->dbUser.'.dbo.Charge_Money');
        $fields = [
            'ChargeID' => $chargeID,
            'UserName' => $this->UserName,
            'Money' => $product->amount,
            'Date' => date('Y-m-d H:i:s'),
            'CanUse' => 1,
            'PayWay' => "$order->method",
            'NeedMoney' => $order->value,
            'IP' => '0.0.0.0',
            'NickName' => $this->NickName,
        ];

        if (! $modelMoney->create($fields)) {
            return false;
        }

        //send wsdl recharge
        if ($server->wsdl != '') {
            try {
                $client = new \SoapClient($server->wsdl, ['trace' => 1, 'exceptions' => 1]);
                $client->ChargeMoney(['userID' => (int) $this->UserID, 'chargeID' => $chargeID]);
            } catch (\Throwable $th) {
                //save log
                file_put_contents(storage_path('logs/soap.log'), $th->getMessage().PHP_EOL, FILE_APPEND);
            }
        }

        return true;
    }
}
