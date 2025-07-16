<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Suit extends Controller
{
    public function index()
    {
        return $this->view->render('admin.game.suit.index', [
            'servers' => Server::all()
        ]);
    }
}
