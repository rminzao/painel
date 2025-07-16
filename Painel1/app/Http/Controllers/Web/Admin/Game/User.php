<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class User extends Controller
{
    /**
     * It returns a view with a list of servers
     *
     * @return The view is being returned.
     */
    public function list()
    {
        return $this->view->render('admin.game.users.list', [
            'servers' => Server::all()
        ]);
    }
}
