<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopGoodsShowList extends Model
{
    protected $table = '.dbo.ShopGoodsShowList';

    protected $primaryKey = null;

    protected $hidden = [];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    public function __construct(?string $database = null, ?int $version = null)
    {
        if ($database != null) {
            $this->table =
            $database . (($version != null && $version >= 10000) ? '.dbo.Shop_Goods_Show_List' : $this->table);
        }
    }
}
