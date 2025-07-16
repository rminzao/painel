<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    public static function earningsMonth()
    {
        return number_format(
            self::whereMonth(
                'paid_at',
                Carbon::now()->month
            )->sum('value'),
            2,
            ',',
            ''
        );
    }

    public static function invoicesMonth()
    {
        return self::whereMonth(
            'created_at',
            Carbon::now()->month
        );
    }
}
