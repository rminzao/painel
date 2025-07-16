<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

/**
 * Equip class
 */
class Item extends Controller
{
    public function index()
    {
        return $this->view->render('admin.item.index', [
            'servers' => Server::all()
        ]);
    }

    public function send()
    {
        return $this->view->render('admin.item.send', [
            'servers' => Server::all()
        ]);
    }
}
