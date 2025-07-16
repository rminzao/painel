<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopGoodsBox extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '.dbo.Shop_Goods_Box';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
        }
    }
}
