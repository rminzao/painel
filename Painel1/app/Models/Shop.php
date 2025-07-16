<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'dbo.Shop';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $hidden = [];

    protected $guarded = [];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m\TH:i:s');
    }
}
