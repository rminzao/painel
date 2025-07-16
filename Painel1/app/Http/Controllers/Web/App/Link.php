<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;

class Link extends Controller
{
  public function index()
  {
    return $this->view->render('others.link');
  }
}
