<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Server as ServerModel;

class Server extends Controller
{
    public function list()
    {
        $model = new ServerModel();

        $data = $this->user->role != 1 ?
            $model->all() :
            $model->where([
                ['status', '!=', 'not_visible'],
                ['active', 0]
            ])->orWhere('active', 1)->get();

        //append $this->user->role to server
        foreach ($data as $key => $value) {
            $data[$key]->role = $this->user->role;
        }

        return $this->view->render('app.server.list', [
            'servers' => $data?->toArray() ?? []
        ]);
    }
}
