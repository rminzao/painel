<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropCondition extends Model
{
    protected $table = '.dbo.Drop_Condiction';

    protected $primaryKey = 'DropID';

    public $timestamps = false;

    protected $hidden = [];

    protected $fillable = [
        'DropID',
        'ConditionType',
        'Para1',
        'Para2',
    ];

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
        }
    }
}
