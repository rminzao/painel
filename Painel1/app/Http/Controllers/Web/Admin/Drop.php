<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

/**
 * Drop class
 */
class Drop extends Controller
{
    public function index()
    {
        return $this->view->render('admin.drop.index', [
            'servers' => Server::all(),
            'dropTypes' => getLanguage('drop.types.'),
        ]);
    }
}
