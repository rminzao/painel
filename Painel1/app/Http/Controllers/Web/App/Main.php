<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Main extends Controller
{
    public function getMain()
    {
        $data['head'] = $this->seo->render(
            $_ENV['APP_NAME'] . " - DDTank Pirata",
            "DDTank Raiz de verdade é aqui!",
            url(),
            url($_ENV['APP_URL'] . "/assets/media/logos/logo.png")
        );

        if ($this->session->has('uid')) {
            if ($this->session->has('last_server')) {
                $last = [
                    'name' => $this->session->last_server->name,
                    'id' => $this->session->last_server->id
                ];
            }

            $data['last'] = $last ?? [];
            $data['servers'] = Server::all();
        }

        return $this->view->render('main', $data);
    }

    public function getNotices()
    {
    }
}
