<?php

namespace App\Http\Controllers\Web\Admin\Events;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Activity extends Controller
{
    public function index()
    {
        return $this->view->render('admin.events.activity.index', [
            'servers' => Server::all(),
        ]);
    }
}
