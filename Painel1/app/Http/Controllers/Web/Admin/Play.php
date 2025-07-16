<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;
use App\Models\User;

class Play extends Controller
{
    public function index($uid, $sid)
    {
        $int = filter_var($sid, FILTER_VALIDATE_INT);
        if (!$int) {
            redirect('/');
        }

        $user = User::find($uid);
        if (!$user) {
            redirect('/');
        }

        $server = Server::find($sid);
        if (!$server) {
            redirect('/');
        }

        $server->settings = json_decode(unserialize($server['settings']));

        return $this->view->render('others.playgame', [
            'server' => $server,
            'user' => $user,
            'type' => 'admin'
        ]);
    }
}
