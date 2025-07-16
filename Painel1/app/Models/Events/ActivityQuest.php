<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class ActivityQuest extends Model
{
    protected $table = 'dbo.Activity_Quest';
    protected $tableCondition = 'dbo.Activity_Quest_Condictions';
    protected $tableReward = 'dbo.Activity_Quest_Rewards';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $hidden = [];

    protected $guarded = [];

    public function __construct(?string $connection = null)
    {
        parent::__construct();

        if ($connection !== null) {
            // Aponta para a conexão específica (nome passado via addConnection)
            $this->setConnection($connection);

            // Prepara os nomes das tabelas com prefixo de schema se necessário
            $this->table = $connection . '.dbo.Activity_Quest';
            $this->tableCondition = $connection . '.dbo.Activity_Quest_Condictions';
            $this->tableReward = $connection . '.dbo.Activity_Quest_Rewards';
        }
    }

    public function conditions(?int $id = null)
    {
        $instance = new static($this->getConnectionName());
        $instance->setTable($this->tableCondition);

        return !$id ? $instance : $instance->where('QuestID', $id);
    }

    public function rewards(?int $id = null)
    {
        $model = new ActivityQuestReward($this->getConnectionName());
        return !$id ? $model : $model->where('QuestID', $id);
    }
}
