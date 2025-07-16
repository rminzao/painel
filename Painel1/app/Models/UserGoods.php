<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserGoods extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'dbo.Sys_Users_Goods';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
    protected $primaryKey = 'ItemID';

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
    protected $hidden = [];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
    public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
    'UserID',
    'BagType',
    'TemplateID',
    'Place',
    'Count',
    'IsJudge',
    'Color',
    'IsExist',
    'StrengthenLevel',
    'AttackCompose',
    'DefendCompose',
    'LuckCompose',
    'AgilityCompose',
    'IsBinds',
    'BeginDate',
    'ValidDate'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
