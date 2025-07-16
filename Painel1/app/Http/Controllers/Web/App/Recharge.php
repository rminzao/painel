<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Character;
use App\Models\Product;
use App\Models\Server;

class Recharge extends Controller
{
    public function index()
    {
        $servers = [];

        foreach (Server::all()->toArray() as $server) {
            $user = (new Character())
            ->setTable($server['dbUser'] . '.dbo.Sys_Users_Detail')
            ->select('UserID', 'NickName', 'Sex', 'State', 'Style', 'FightPower')
            ->where('UserName', '=', $this->user->u_hash)
            ->first()?->toArray();

            if (!$user) {
                $servers[] = $server;
                continue;
            }

            $user['equipment'] = image_equipment(
                $user['Style'],
                $server['dbData'],
                $user['Sex'] ? 'm' : 'f'
            );

            $servers[] = array_merge(['user' => $user], $server);
        }

        return $this->view->render('app.recharge.select', [
        'servers' => $servers
        ]);
    }

    public function detail($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            redirect('app/recarga');
        }

        $server = Server::find($id);
        if (!$server) {
            redirect('app/recarga');
        }

        //change db to current server on
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $character = $model->select('UserID', 'NickName')->where('UserName', '=', $this->user->u_hash)->first();
        if (!$character) {
            redirect('app/recarga');
        }

        $products = (new Product())->where('sid', '=', $id)->orderBy('type', 'desc')->get()?->toArray();

        foreach ($products as $key => $product) {
            if ($product['active'] || $this->user->role >= 3) {
                continue;
            }
            unset($products[$key]);
        }

        return $this->view->render('app.recharge.list', [
        'products' => $products,
        'server' => $server,
        'character' => $character
        ]);
    }
}
