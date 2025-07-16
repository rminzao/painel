<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class GmActivity extends Model
{
    protected $table = 'dbo.GM_Activity';

    protected $primaryKey = 'activityId';
    
    protected $hidden = [];
    
    protected $guarded = [];

    protected $keyType = 'string';

    public $timestamps = false;
}
