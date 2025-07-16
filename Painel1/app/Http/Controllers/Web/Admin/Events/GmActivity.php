<?php

namespace App\Http\Controllers\Web\Admin\Events;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class GmActivity extends Controller
{
    public function index()
    {
        return $this->view->render('admin.events.gm-activity.index',[
            'servers' => Server::all(),
            'activityTypes' => getLanguage('gmActivity.activityTypes.')
        ]);
    }
}