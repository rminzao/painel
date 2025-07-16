<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Character;
use App\Models\Server;

class Shop extends Controller
{
    public function index()
    {
        return $this->view->render('app.shop.index');
    } 
}
