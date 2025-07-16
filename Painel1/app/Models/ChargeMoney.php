<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ChargeMoney extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo.Charge_Money';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ChargeID';

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
     * When you're converting a date from a database to a string, make sure it's in the format that the
     * API expects.
     * 
     * @param value The value to be converted.
     * 
     * @return The date in the format of Y-d-m\TH:i:s
     */
    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m\TH:i:s');
    }
}
