<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server as ModelsServer;

class Server extends Controller
{
    public function index()
    {
        return $this->view->render('admin.server.index');
    }

    public function message()
    {
        return $this->view->render('admin.server.message', [
            'servers' => ModelsServer::all()
        ]);
    }
}
