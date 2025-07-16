<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCode extends Model
{
    protected $table = 'products_code';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];
}
