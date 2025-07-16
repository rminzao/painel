<?php

namespace App\Models;

use App\Models\Game\UserDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Orders extends Model
{
    public function store(
        Products $product,
        string $reference,
        int $char,
        string $method
    ): ?Orders {
        $server = Server::find($product->sid);

        //check if char exists
        if (! (new UserDetail($server->dbUser))->find($char)) {
            return null;
        }

        $this->user_id = Auth::user()->id;
        $this->char_id = $char;
        $this->product_id = $product->id;
        $this->server_id = $product->sid;
        $this->status = 'pending';
        $this->method = $method;
        $this->value = $product->value;
        $this->reference = $reference;
        $this->reference_original = $reference;
        if (! $this->save()) {
            return null;
        }

        return $this;
    }


    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function sendMoney($hasBonus = false, $bonusValue = 10)
    {
        if ($this->sent) {
            return true;
        }

        $this->paid_at = date('Y-m-d H:i:s');

        //find product and server
        if (! $product = Products::find($this->product_id)) {
            return false;
        }

        if (! $server = Server::find($this->server_id)) {
            return false;
        }

        //find char
        if (! $char = (new UserDetail($server->dbUser))->where('UserID', $this->char_id)->first()) {
            return false;
        }

        //check if order is approved or paid and is sent
        if (($this->status != 'approved' && $this->status != 'paid') || $this->sent) {
            return false;
        }

        //check pix bonus
        if ($hasBonus) {
            $product->amount += $product->amount * ($bonusValue / 100);
        }

        $this->sent_at = date('Y-m-d H:i:s');
        $this->sent = $char->sendCharge($product, $this, $server);
        $this->save();
    }
}
