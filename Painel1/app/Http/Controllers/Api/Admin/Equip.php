<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\User;
use Core\Routing\Request;
use Core\View\Paginator;

class Equip extends Api
{
    /**
     * Get a list of users
     * @param Request request The request object.
     * @return The `getList` method returns an array with two keys: `users` and `paginator`. The `users`
     * key contains an array of users, and the `paginator` key contains an array of pagination
     * information.
     */
    public function getList(Request $request)
    {
        //filter and valid page request
        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        //get servers
        $user = (new User())->select('*')
            ->where('role', '>', '1')
            ->orWhere('role', '0')
            ->orderBy('id', 'ASC');

        //start paginator instance
        $pager = new Paginator(url("api/admin/users/equip?page="), onclick:
            "getServerList");
        $pager->pager($user->count(), 10, $page, 2);

        //get users data
        $users = $user->limit($pager->limit())
                ->offset($pager->offset())
                ->get()
                ?->toArray();

        foreach ($users as $_user) {
            unset($_user['password']);
            $_user['photo'] = image_avatar($_user['photo'], 50, 50);
            $_users[] = $_user;
        }

        return [
            'users' => $_users ?? [],
            'paginator' => [
                'total' => $pager->pages() ,
                'current' => $pager->page() ,
                'rendered' => $pager->render()
            ]
        ];
    }

    public function getAllUsers(Request $request)
    {
        $users = [];
        foreach (User::all() as $user) {
            $users[] = ['UserID' => $user->id, 'Name' => $user->first_name . ' ' . $user->last_name, ];
        }

        return $users;
    }

    public function changeRole(Request $request)
    {
        $uid = filter_input(INPUT_POST, "user", FILTER_VALIDATE_INT);
        $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);

        if (
            $this->user == null || $this
                ->user->role < $role || $this
                ->user->id == $uid
        ) {
            return ['state' => false, 'message' => 'Você não tem permissão para alterar o cargo desse usuário.'];
        }

        $user = User::find($uid);
        if ($user->role == $role) {
            return ['state' => false, 'message' => 'O usuário ja tem esse cargo!'];
        }

        $user->role = $role;

        if (!$user->save()) {
            return ['state' => false, 'message' => 'Erro ao Atualizar o Cargo!'];
        }

        return ['state' => true, 'message' => 'Cargo Atualizado Com Sucesso!'];
    }
}
