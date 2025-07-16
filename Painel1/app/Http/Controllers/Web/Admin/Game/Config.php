<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Config extends Controller
{
    public function index()
    {
        return $this->view->render('admin.game.config.index', [
            'servers' => Server::all(),
        ]);
    }
}
