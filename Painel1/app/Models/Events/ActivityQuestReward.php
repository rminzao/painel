<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use Core\Database;


class ActivityQuestReward extends Model
{
    protected $table = 'ActivityQuest_Reward';
    protected $connection = 'default';

    public $timestamps = false;

    protected $fillable = [
        'ActiveID',
        'TemplateID',
        'Count',
        'IsBind',
        'ValidDate'
    ];

    public function __construct(string $connection = null)
    {
        parent::__construct();

        if ($connection) {
            $this->setConnection($connection);
        }
    }
}
