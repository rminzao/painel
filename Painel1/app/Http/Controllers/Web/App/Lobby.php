<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Character;
use App\Models\Post;
use App\Models\Server;

class Lobby extends Controller
{
    public function index()
    {

        if (isset($_COOKIE['last_server'])) {
            $lastServer = explode(';', $_COOKIE['last_server']);
            $last = [
                'name' => $lastServer[0],
                'id' => $lastServer[1],
                'last' => $lastServer[2],
            ];
        }

        return $this->view->render('app.lobby.index', [
            'servers' => Server::all(),
            'characters' => $this->characters(),
            'last_server' => $last ?? [],
        ]);
    }

    protected function characters(): ?array
    {
        $servers = Server::all();

        $list = [];

        foreach ($servers as $server) {
            //find character
            $character = (new Character())
                ->setTable($server->dbUser . '.dbo.Sys_Users_Detail')
                ->select('NickName', 'Style', 'Sex', 'State', 'FightPower', 'UserID', 'Grade', 'Total', 'Win')
                ->where('UserName', $this->user->u_hash)
                ->first();

            if (!$character) {
                continue;
            }

            $character->equipments = image_equipment(
                $character->Style,
                $server->dbData,
                $character->Sex ? 'm' : 'f'
            );

            $character->WinRate = ($character->Total > 0) ? round(($character->Win * 100) / $character->Total, 2) : 0;

            $character->position = (new Character())
                ->setTable($server->dbUser . '.dbo.Sys_Users_Detail')
                ->where('FightPower', '>', $character->FightPower)
                ->where('UserID', '<>', $character->UserID)
                ->count() + 1;

            $list[$server->id] = $character;
        }

        return $list;
    }
}
