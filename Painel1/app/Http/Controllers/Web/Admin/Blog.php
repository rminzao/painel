<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;
use App\Models\User;

class Blog extends Controller
{
    public function index()
    {
        return $this->view->render('admin.blog.list');
    }

    public function create()
    {
        return $this->view->render('admin.blog.create', [
          'servers' => Server::all(),
          'users' => User::where('role', '>=', 2)->get(),
        ]);
    }
}
