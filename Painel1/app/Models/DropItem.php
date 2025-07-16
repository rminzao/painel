<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropItem extends Model
{
    protected $table = '.dbo.Drop_Item';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $hidden = [];

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
        }
    }
}
