<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class PveInfo extends Model
{
    protected $table = '.dbo.Pve_Info';

    protected $primaryKey = 'ID';

    protected $hidden = [];

    protected $guarded = [];

    public $timestamps = false;

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
        }
    }
}
