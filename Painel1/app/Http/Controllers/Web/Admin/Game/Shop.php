<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Shop extends Controller
{
    public function index()
    {
        $shopTypes = getLanguage('shop.types.');
        arr_sort($shopTypes, "name");

        return $this->view->render('admin.game.shop.index', [
            'servers' => Server::all(),
            'shopTypes' => $shopTypes,
            'moneyTypes' => getLanguage('shop.moneyTypes.'),
            'moneyBuyTypes' => getLanguage('shop.moneyBuyTypes.'),
            'labels' => getLanguage('shop.labels.')
        ]);
    }
}
