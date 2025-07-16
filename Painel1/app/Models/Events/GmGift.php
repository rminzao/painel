<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class GmGift extends Model
{
    protected $table = 'dbo.GM_Gift';

    protected $primaryKey = 'giftbagId';
    
    public $timestamps = false;

    protected $hidden = [];
    
    protected $guarded = [];

    protected $keyType = 'string';
}
