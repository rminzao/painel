<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $table = '.dbo.Quest';

    protected $tableCondition = '.dbo.Quest_Condiction';

    protected $tableReward = '.dbo.Quest_Goods';

    protected $primaryKey = 'ID';

    protected $hidden = [];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
            $this->tableCondition = $database . $this->tableCondition;
            $this->tableReward = $database . $this->tableReward;
        }
    }

    public function conditions(?int $id = null)
    {
        $this->primaryKey = '';
        $this->table = $this->tableCondition;
        return !$id ? $this : ($this)->where('QuestID', $id);
    }

    public function rewards(?int $id = null)
    {
        $this->table = $this->tableReward;
        return !$id ? $this : ($this)->where('QuestID', $id);
    }
}
