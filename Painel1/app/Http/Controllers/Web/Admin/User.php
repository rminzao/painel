<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

/**
 * Equip class
 */
class User extends Controller
{
    /**
     * Get userEquip list
     *
     * @return string
     */
    public function list()
    {
        return $this->view->render('admin.users.list', [
            'servers' => Server::all()
        ]);
    }

    /**
     * It gets the user from the database, then it gets all the servers from the database, then it gets
     * all the characters from the database
     *
     * @param id The id of the user you want to view.
     *
     * @return The view is being returned.
     */
    public function detail($id)
    {
        $id = (int) $id;
        $user = \App\Models\User::find($id);
        if (!$user) {
            redirect('admin/users');
        }

        $characters = [];

        //find servers
        foreach (Server::all() as $server) {
            //find characters
            $model = (new \App\Models\Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
            $character = $model->where('UserName', $user->u_hash)->first();

            if ($character) {
                //user equip
                $style = explode(',', $character['Style']);
                $userEquip = [
                  "head" => image_item((explode("|", $style[0]))[0] ?? '', $server->dbData, true),
                  "hair" => image_item((explode("|", $style[2]))[0] ?? '', $server->dbData, true),
                  "eff" => image_item((explode("|", $style[3]))[0] ?? '', $server->dbData, true),
                  "cloth" => image_item((explode("|", $style[4]))[0] ?? '', $server->dbData, true),
                  "face" => image_item((explode("|", $style[5]))[0] ?? '', $server->dbData, true),
                  "gun" => image_item((explode("|", $style[6]))[0] ?? '', $server->dbData, true),
                  "suit" => image_item((explode("|", $style[7]))[0] ?? '', $server->dbData, true),
                ];

                $characters[] = array_merge($character->toArray(), [
                    'server' => $server?->toArray(),
                    'equipment' => $userEquip ?? []
                ]);
            }
        }

        return $this->view->render('admin.users.detail', [
            'userSelected' => $user,
            'characters' => $characters,
            'questTypes' => getLanguage('quest.types.'),
            'bagTypes' => getLanguage('bagInfo.types.'),
        ]);
    }
}
