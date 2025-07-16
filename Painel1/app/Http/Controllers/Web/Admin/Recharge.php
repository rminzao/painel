<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Recharge extends Controller
{
    public function getList()
    {
        return $this->view->render('admin.product.index');
    }

    public function getCode()
    {
        return $this->view->render('admin.product.code.index', [
            'servers' => Server::all(),
            'codeTypes' => getLanguage('products.code.types.')
        ]);
    }
}
