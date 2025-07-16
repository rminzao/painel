<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class ProductCode extends Controller
{
    public function index()
    {
        return $this->view->render('admin.product.code.index', [
            'servers' => Server::all(),
            'codeTypes' => getLanguage('products.code.types.')
        ]);
    } 
}
