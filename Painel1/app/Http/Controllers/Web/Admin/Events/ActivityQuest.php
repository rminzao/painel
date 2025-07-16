<?php

namespace App\Http\Controllers\Web\Admin\Events;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class ActivityQuest extends Controller
{
    public function index()
    {
        return $this->view->render('admin.events.activityQuest.index', [
            'servers' => Server::all(),
            'activityConditions' => getLanguage('activityQuest.conditions.'),
        ]);
    }
}
