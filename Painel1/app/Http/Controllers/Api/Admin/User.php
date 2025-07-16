<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\User as ModelsUser;
use App\Models\UserGoods;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class User extends Api
{
    /**
     * It returns a list of items from the database
     * @param Request request The request object.
     * @return The return is an array with the following keys:
     */
    public function list(Request $request): array
    {
        try {
            $params = $request->get();
            $sid = $params['sid'] ?? null;
            if (!$sid) {
                return [
                    'status' => false,
                    'message' => 'No server selected'
                ];
            }

            //find server
            $server =  Server::find($params['sid']);
            if (!$server) {
                return [
                    'status' => false,
                    'message' => 'Server not found'
                ];
            }

            //find users
            $users = ModelsUser::all()->toArray() ?? [];
            if (sizeof($users) == 0) {
                return [
                    'status' => false,
                    'message' => 'No users found'
                ];
            }

            //find characters for each user
            foreach ($users as &$user) {
                //set table name
                $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
                $character = $model->select(['UserID', 'NickName', 'State', 'Style', 'Sex'])->where('UserName', $user['u_hash'])->first()?->toArray();

                $user['avatar'] = image_avatar($user['photo'], 40, 40);
                $user['app_id'] = $user['id'];
                $user['character'] = $character;

                if ($character) {
                    $user['character']['equipment'] = image_equipment(
                        $character['Style'],
                        $server->dbData,
                        $character['Sex'] ? 'm' : 'f'
                    );
                }
            }

            return [
                'status' => true,
                'users' => $users
            ];
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function web(): array
    {
        $get = $this->request->get();

        $page = $get['page'] ?? 1;
        $limit = $get['limit'] ?? 10;
        $search = $get['search'] ?? '';
        $filter = $get['filter'] ?? 'all';
        $onclick = $get['onclick'] ?? 'user.list';

        $model = new ModelsUser();

        $query = $model->select('*');

        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('id', $search) :
                $query->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', '%{$search}%');
        }

        if ($filter != 'all') {
            $query = $filter == 'team' ? $query->where('role', '<>', 1) : $query->where('active', 0);
        }

        $query = $query->orderBy('id', 'ASC');

        $pager = new Paginator(url($this->request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 1);

        $data = $query
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->get()
            ?->toArray();

        foreach ($data as &$user) {
            $user['avatar'] = image_avatar($user['photo'], 40, 40);
        }

        return [
            'state' => true,
            'data' => $data ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    /**
     * It updates the user's data
     *
     * @param Request request The request object.
     *
     * @return array An array with the state and message.
     */
    public function update(Request $request): array
    {
        $post = $request->post(false);

        //find user
        $user = ModelsUser::find($post['id']);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'User not found'
            ];
        }

        //check if role is changed
        $role = $post['role'] ?? $user->role;
        if ($this->user->id == $post['id'] and isset($post['role']) and ($this->user->role == $post['role'])) {
            return [
                'state' => false,
                'message' => 'Você não pode mudar o seu próprio cargo'
            ];
        }

        //update user
        $user->first_name = $post['first_name'];
        $user->last_name = $post['last_name'];
        $user->role = $role;
        $user->active = isset($post['active']) ? 1 : 0;
        $user->save();
        return [
            'state' => true,
            'message' => 'Dados atualizados com sucesso'
        ];
    }

    public function detail(Request $request, $id): array
    {
        //find user
        $user = ModelsUser::find($id);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'User not found'
            ];
        }

        //find characters for user on all servers
        $charactersList = null;
        $servers = Server::all();
        foreach ($servers as $server) {
            //set table name
            $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
            $characters = $model->select(['UserID', 'NickName', 'Grade', 'State', 'Style', 'Sex', 'ForbidDate', 'PasswordTwo'])->where('UserName', $user->u_hash)->get()?->toArray();
            if (!$characters) {
                continue;
            }
            
            foreach ($characters as $character) {
                //set server id
                $character['server_id'] = $server->id;
                $character['server_name'] = $server->name;

                //get equipments image
                $equipmentImages = image_equipment(
                    $character['Style'],
                    $server->dbData,
                    $character['Sex'] ? 'm' : 'f'
                );

                $equipment = [
                    'head' => ['image' => $equipmentImages['head']],
                    'glass' => ['image' => $equipmentImages['glass']],
                    'hair' => ['image' => $equipmentImages['hair']],
                    'eff' => ['image' => $equipmentImages['eff']],
                    'cloth' => ['image' => $equipmentImages['cloth']],
                    'face' => ['image' => $equipmentImages['face']],
                    'arm' => ['image' => $equipmentImages['arm']],
                    'suit' => ['image' => $equipmentImages['suit']],
                ];

                $goods = (new Character($server->dbUser))
                    ->goods(uid: $character['UserID'])
                    ->where('UserID', $character['UserID'])
                    ->where('BagType', 0)
                    ->whereIn('Place', [4, 6, 13])
                    ->get()
                    ?->toArray();

                foreach ($goods as $good) {
                    if ($good['Place'] == 4) {
                        $equipment['cloth']['data'] = $good;
                    }
                    if ($good['Place'] == 6) {
                        $equipment['arm']['data'] = $good;
                    }
                    if ($good['Place'] == 13) {
                        $equipment['wing']['data'] = $good;
                        $equipment['wing']['data']['Pic'] = (new ShopGoods($server->dbData))
                            ->find($good['TemplateID'] ?? null)
                            ?->Pic;
                    }
                }

                $character['equipment'] = $equipment;
                $character['server'] = [
                    'resource' => $server->resource,
                ];

                unset($character['Style']);

                //set characters
                $charactersList[] = $character;
            }
        }

        return [
            'state' => true,
            'characters' => $charactersList,
        ];
    }

    /**
     * It receives a request, gets the post variables, finds the user, checks if the password is
     * identical, updates the password and returns a message
     *
     * @param Request request The request object
     *
     * @return array An array with the state and message.
     */
    public function updatePassword(Request $request): array
    {
        $post = $request->post(false);

        //find user
        $user = ModelsUser::find($post['id']);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'User not found'
            ];
        }

        //check if all fields are filled with in_array function
        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos'
            ];
        }


        //check if password is identical
        if ($post['password'] != $post['password-confirm']) {
            return [
                'state' => false,
                'message' => 'As senhas não conferem'
            ];
        }

        //update password
        $user->password = passwd($post['password']);
        $user->save();
        return [
            'state' => true,
            'message' => 'Senha atualizada com sucesso'
        ];
    }

    /**
     * It updates the user's email
     *
     * @param Request request The request object.
     *
     * @return array An array with the state and message.
     */
    public function updateEmail(Request $request): array
    {
        $post = $request->post(false);

        //find user
        $user = ModelsUser::find($post['id']);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'User not found'
            ];
        }

        //check if all fields are filled with in_array function
        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos'
            ];
        }

        //check if email is identical
        if ($post['email'] != $post['email-confirm']) {
            return [
                'state' => false,
                'message' => 'Os emails não conferem'
            ];
        }

        //update email
        $user->email = $post['email'];
        $user->save();

        return [
            'state' => true,
            'message' => 'Email atualizado com sucesso'
        ];
    }

    public function updateRanking(Request $request)
    {
        $post = $request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $client = new Client();
        try {
            $res = $client->request('GET', $server->quest . '/CelebList/CreateAllCeleb.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'success')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar ranking.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Ranking atualizado com sucesso.'
        ];
    }
}
