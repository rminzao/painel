<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Map extends Controller
{
    public function index()
    {
        return $this->view->render('admin.game.map.index', [
            'servers' => Server::all()
        ]);
    }
}
