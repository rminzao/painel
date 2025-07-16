<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestCondiction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo.Quest_Condiction';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'QuestID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
