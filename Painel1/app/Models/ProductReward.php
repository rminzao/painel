<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReward extends Model
{
    protected $table = 'products_reward';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];
}
