<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Product extends Controller
{
    public function index()
    {
        return $this->view->render('admin.product.index', [
            'servers' => Server::all(),
        ]);
    }

    public function send()
    {
        return $this->view->render('admin.product.send', [
            'servers' => Server::all()
        ]);
    }
}
