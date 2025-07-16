<?php

namespace App\Http\Controllers\Api;

use App\Models\Character;
use App\Models\Server;
use App\Models\User;
use Core\Routing\Request;
use Core\View\Paginator;

class Ranking extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $page = $post['page'] ?? 1;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.',
            ];
        }

        $characters = (new Character())
            ->setTable($server->dbUser . '.dbo.Sys_Users_Detail')
            ->select('UserName', 'NickName', 'Win', 'Total', 'FightPower', 'State', 'Grade')
            ->orderBy('FightPower', 'desc');

        $pager = new Paginator(url("app/ranking?page="), onclick: "ranking.list");
        $pager->pager($characters->count(), 10, $page, 2);

        $users = $characters
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->get()
            ?->toArray();

        $i = $pager->offset() + 1;
        $index = 0;
        foreach ($users as &$user) {
            if ($appUser = User::where('u_hash', $user['UserName'])->first()) {
                $user['id'] = $appUser->id ?? 0;
                $user['avatar'] = image_avatar($appUser['photo'], 50, 50);
                $user['border'] = $appUser['border'];
            }

            $user['WinRate'] = ($user['Total'] > 0) ? round(($user['Win'] * 100) / $user['Total'], 2) : 0;
            $user['position'] = $i;

            unset($user['UserName']);
            unset($user['Total']);
            unset($user['Win']);

            $i++;
            $index++;
        }

        return [
            'state' => true,
            'data' => $users ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function lobbyRanking(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;

        if (!$server = Server::find($sid)) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.',
            ];
        }

        $characters = (new Character($server->dbUser))
            ->select('NickName', 'Grade', 'UserName', 'Style', 'State', 'FightPower', 'Sex')
            ->limit(10)
            ->orderBy('FightPower', 'DESC')
            ->get()
            ?->toArray();

        $podium = array_slice($characters, 0, 3);
        foreach ($podium as &$char) {
            $char['equipment'] = image_equipment(
                $char['Style'],
                $server->dbData,
                $char['Sex'] ? 'm' : 'f'
            );
            unset($char['Style']);
        }

        foreach ($characters as &$char) {
            //find user from app by u_hash
            if ($user = (new User)->select('photo', 'first_name', 'last_name')->where('u_hash', $char['UserName'])->get()->first()) {
                $user->photo = image_avatar($user->photo, 30, 30);
                $char['app'] = $user?->toArray();
            }
            unset($char['Style']);
        }

        return [
            'list' => $characters,
            'podium' => $podium,
        ];
    }
}
