<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class GmActiveCondition extends Model
{
    protected $table = 'dbo.GM_Active_Condition';

    protected $primaryKey = null;
    
    public $timestamps = false;

    protected $hidden = [];
    
    protected $guarded = [];
}
