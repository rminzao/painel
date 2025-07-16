<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Ranking extends Controller
{
    public function index()
    {
        $model = new Server();
        $data = $this->user->role != 1 ?
            $model->all() :
            $model->where([
                ['status', '<>', 'not_visible'],
                ['active', 0]
            ])
            ->where([
                ['status', '<>', 'comming_soon'],
                ['active', 0]
            ])
            ->orWhere('active', 1)->get();

        return $this->view->render('app.ranking.index', [
            'servers' => $data ?? [],
        ]);
    }
}
