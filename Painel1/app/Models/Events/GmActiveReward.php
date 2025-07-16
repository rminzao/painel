<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class GmActiveReward extends Model
{
    protected $table = 'dbo.GM_Active_Reward';

    protected $primaryKey = null;
    
    public $timestamps = false;

    protected $hidden = [];
    
    protected $guarded = [];
}
