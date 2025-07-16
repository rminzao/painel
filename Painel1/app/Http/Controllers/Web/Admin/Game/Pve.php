<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Pve extends Controller
{
    public function index()
    {
        $pveTypes = getLanguage('map.pve.types.');
        arr_sort($pveTypes, "name");
        return $this->view->render('admin.game.pve.index', [
            'servers' => Server::all(),
            'pveTypes' => $pveTypes
        ]);
    }
}
