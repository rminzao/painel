<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Post;

class Notices extends Controller
{
    public function index()
    {
        return $this->view->render('app.notices.index');
    }

    public function detail($uri)
    {
        if (empty($uri)) {
            redirect('/app/notices');
        }

        if (!$post = (new Post())->where('uri', $uri)->first()) {
            redirect('/app/notices');
        }

        return $this->view->render('app.notices.detail', [
          'post' => $post
        ]);
    }
}
