<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\DropItem;
use App\Models\Game\User\AchievementData;
use App\Models\Game\User\UserRank;
use App\Models\Server;
use App\Models\User as ModelsUser;
use App\Models\UserGoods;
use App\Models\UserMessages;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Seo;
use Core\Utils\Wsdl;
use Core\View\Paginator;

class User extends Api
{
    /**
     * It gets a list of users from a database, and then gets the user's avatar from another database
     *
     * @param Request request The request object.
     *
     * @return array An array of users.
     */
    public function list(Request $request): array
    {
        $params = $request->get();
        $sid = $params['sid'] ?? null;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $search = $params['search'] ?? '';
        $state = $params['state'] ?? null;

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

        //find users in server
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $query = $model->select('UserID', 'NickName', 'UserName', 'State', 'LastDate');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('UserID', 'LIKE', "%{$search}%") :
                $query->where('NickName', 'LIKE', "%{$search}%");
        }

        if ($state != null && $state != 'all') {
            $query = $query->where('State', $state);
        }

        $query = $query->orderBy('UserID', 'ASC');

        //paginate
        $pager = new Paginator(url($request->getUri()), onclick: "user.list");
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $users = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();

        //get user info
        $userList = array_map(function ($users) use ($server) {
            $users['LastDate'] = date('Y-m-d H:i:s', strtotime($users['LastDate']));
            $users['server'] = $server->id;
            //find user
            $user = (new ModelsUser())->select('id', 'photo')->where('u_hash', $users['UserName'])->first();
            if ($user) {
                $users['web'] = $user->toArray() ?? [];
                $users['web']['avatar'] = image_avatar($user->photo, 50, 50);
            }

            return $users;
        }, $users);

        return [
            'status' => true,
            'users' => $userList ?? [],
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
    public function updateNickname(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;
        $nickname = $post['nickname'] ?? null;

        //find server
        $server =  Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        if (!$id || !$nickname) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos'
            ];
        }

        //find user
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $user = $model->where('UserID', $id)->first();
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        //check nickname
        if ($nickname != $post['nickname-confirm']) {
            return [
                'state' => false,
                'message' => 'Nomes não conferem'
            ];
        }

        //check nickname length
        if (strlen($nickname) < 4 or strlen($nickname) > 16) {
            return [
                'state' => false,
                'message' => 'Nome deve conter de 4-16 caracteres'
            ];
        }

        //check if nickname is identical
        $check = $model->where('NickName', $nickname)->first();
        if ($check) {
            return [
                'state' => false,
                'message' => 'Nome já existe'
            ];
        }

        //check if user is online
        if ($user->State) {
            return [
                'state' => false,
                'message' => 'Não é possível alterar o nome de um usuário online'
            ];
        }

        //update nickname
        $user->NickName = $nickname;
        $user->save();

        return [
            'state' => true,
            'message' => 'Nome alterado com sucesso'
        ];
    }

    public function forbid()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;
        $reason = $post['reason'] ?? null;
        $forbid = $post['forbid'] ?? null;

        //find server
        if (!$server =  Server::find($sid)) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        if (!$user = (new Character($server->dbUser))->find($id)) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        $user->disconnect($server);

        $user->ForbidDate = date('Y-m-d H:i:s', strtotime($forbid));
        $user->ForbidReason = $reason;
        $user->IsExist = 0;

        if (!$user->save()) {
            return [
              'state' => true,
              'message' => 'Ocorreu um erro ao banir o jogador'
            ];
        }

        $isBan = (strtotime($forbid) > (strtotime(date('Y-m-d H:i:s'))));
        return [
            'state' => true,
            'message' => $isBan
            ? "O jogador <b class='text-primary'>{$user->NickName}</b> do servidor <b class='text-primary'>{$server->name}</b> foi banido até o dia <b class='text-warning'>{$user->ForbidDate}</b>"
            : "Jogador desbanido com sucesso."
        ];
    }

    /**
     * Disconnects a user from the server
     *
     * @param Request request The request object.
     *
     * @return array An array with the state and message.
     */
    public function disconnect(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $uid = $post['uid'] ?? null;

        //find server
        $server =  Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        //find character
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $character = $model->where('UserID', $uid)->first();
        if (!$character) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        //send disconnect command
        if ($server->wsdl != '') {
            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'KitoffUser';
            $wsdl->paramters = [
                "playerID" => (int) $character->UserID,
                "msg" => "Você foi desconectado do servidor, pela administração do sistema."
            ];
            if (!$wsdl->send()) {
                return [
                    'state' => false,
                    'message' => 'Erro ao desconectar usuário, verifique o WSDL do servidor.'
                ];
            }
        }

        return [
            'state' => true,
            'message' => 'Usuário desconectado com sucesso.'
        ];
    }

    public function completeLaboratory(Request $request)
    {
        $post = $request->post(false);

        $sid = $post['sid'] ?? null;
        $uid = $post['uid'] ?? null;

        $laboratoryDrops = [
            10000 => [
                'name' => 'Distância de tela Fácil'
            ],
            10001 => [
                'name' => 'Distância de tela Médio'
            ],
            10002 => [
                'name' => 'Distância de tela Avançado'
            ],
            10010 => [
                'name' => 'Estratégia de ângulo 20° Fácil'
            ],
            10011 => [
                'name' => 'Estratégia de ângulo 20° Médio'
            ],
            10012 => [
                'name' => 'Estratégia de ângulo 20° Avançado'
            ],
            10020 => [
                'name' => 'Estratégia de ângulo 65° Fácil'
            ],
            10021 => [
                'name' => 'Estratégia de ângulo 65° Médio'
            ],
            10022 => [
                'name' => 'Estratégia de ângulo 65° Avançado'
            ],
            10030 => [
                'name' => 'Estratégia de lançamento alto Fácil'
            ],
            10031 => [
                'name' => 'Estratégia de lançamento alto Médio'
            ],
            10032 => [
                'name' => 'Estratégia de lançamento alto Avançado'
            ],
            10040 => [
                'name' => 'Estratégia de lançamento avançada Fácil'
            ],
            10041 => [
                'name' => 'Estratégia de lançamento avançada Médio'
            ],
            10042 => [
                'name' => 'Estratégia de lançamento avançada Avançado'
            ],
        ];

        //find server
        $server =  Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        //find character
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
        $character = $model->where('UserID', $uid)->first();
        if (!$character) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        //check if user is online
        if ($character->State) {
            if ($server->wsdl == '') {
                return [
                    'status' => false,
                    'message' => 'O usuário está online e o servidor não possui WSDL, por favor, desconecte-o.'
                ];
            }

            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'KitoffUser';
            $wsdl->paramters = [
                "playerID" => (int) $character->UserID,
                "msg" => "Você foi desconectado do servidor, pela administração do sistema."
            ];
            if (!$wsdl->send()) {
                return [
                    'state' => false,
                    'message' => 'Erro ao desconectar usuário, verifique o WSDL do servidor.'
                ];
            }
            sleep(1);
        }

        $positions = str_split($character->FightLabPermission);

        $index = [1, 3, 5, 7, 9];

        $Needed = [];

        for ($y = 0; $y <  sizeof($index); $y++) {
            for ($x = (int)$positions[$index[$y]]; $x < 3; $x++) {
                $Needed[] = 10000 + (10 * $y) + $x;
            }
        }

        $gp = 0;
        $gift = 0;

        foreach ($laboratoryDrops as $id => $drop) {
            if (!in_array($id, $Needed)) {
                continue;
            }

            //find drop list
            $drops = (new DropItem())->setTable($server->dbData . '.dbo.Drop_Item')->where('DropId', $id)->get()?->toArray();
            if (!$drops) {
                continue;
            }

            $dropList = array_chunk($drops, 5);



            foreach ($dropList as $group) {
                $groupIds = [];

                //foreach group attachment
                foreach ($group as $attachment) {
                    $attachment = (object) $attachment;

                    if ($attachment->ItemId == 11107) {
                        $gp += $attachment->EndData;
                        continue;
                    }
                    if ($attachment->ItemId == -300) {
                        $gift += $attachment->EndData;
                        continue;
                    }

                    //create user goods
                    $goodsModel = (new UserGoods())->setTable($server->dbUser . '.dbo.Sys_Users_Goods');
                    $rewardGoods = $goodsModel->create([
                        'UserID' => $character->UserID,
                        'BagType' => 0,
                        'TemplateID' => $attachment->ItemId,
                        'Place' => -1,
                        'Count' => $attachment->EndData,
                        'IsJudge' => 1,
                        'Color' => null,
                        'IsExist' => 1,
                        'StrengthenLevel' => 0,
                        'AttackCompose' => 0,
                        'DefendCompose' => 0,
                        'LuckCompose' => 0,
                        'AgilityCompose' => 0,
                        'IsBinds' => $attachment->IsBind,
                        'BeginDate' => Carbon::now()->format('Y-m-d H:i:s.v'),
                        'ValidDate' => $attachment->ValueDate
                    ]);

                    if (!$rewardGoods) {
                        return false;
                    }

                    //append item to groupids
                    $groupIds[] = $rewardGoods->ItemID;
                }

                //create mail
                $annex1 = $groupIds[0] ?? 0;
                $annex2 = $groupIds[1] ?? 0;
                $annex3 = $groupIds[2] ?? 0;
                $annex4 = $groupIds[3] ?? 0;
                $annex5 = $groupIds[4] ?? 0;

                $messageModel = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
                $rewardMessage = $messageModel->create([
                    'SenderID' => 0,
                    'Sender' => 'Sistema',
                    'ReceiverID' => $character->UserID,
                    'Receiver' => $character->NickName,
                    'Title' => $drop['name'] ?? 'Recompensa do sistema',
                    'Content' => 'Parabéns por completar ' . $drop['name'] . '!',
                    'IsRead' => 0,
                    'IsDelR' => 0,
                    'IfDelS' => 0,
                    'IsDelete' => 0,
                    'Annex1' => $annex1,
                    'Annex2' => $annex2,
                    'Annex3' => $annex3,
                    'Annex4' => $annex4,
                    'Annex5' => $annex5,
                    'Gold' => 0,
                    'Money' => 0,
                    'IsExist' => 1,
                    'Type' => 51,
                    'Remark' =>
                    "Gold:0,Money:0,Annex1:$annex1,Annex2:$annex2,
                    Annex3:$annex3,Annex4:$annex4,Annex5:$annex5,
                    GiftToken:0"
                ]);

                if (!$rewardMessage) {
                    return false;
                }
            }
        }

        if (
            !$character->update([
            'GP' => $character->GP + $gp,
            'GiftToken' => $character->GiftToken + $gift,
            'FightLabPermission' => 33333333333
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Erro ao completar laboratório'
            ];
        }

        $achievements = [];
        $achievementData = (new AchievementData())->setTable($server->dbUser . '.dbo.AchievementData');

        for ($i = 3002; $i <= 3007; $i++) {
            if ($i == 3003) {
                continue;
            }

            $achievement = $achievementData->where('UserID', $character->UserID)->where('AchievementID', $i)->first();
            if ($achievement) {
                continue;
            }

            $achievements[] = [
                'UserID' => $character->UserID,
                'AchievementID' => $i,
                'IsComplete' => 1,
                'CompletedDate' => Carbon::now()->format('Y-m-d H:i:s.v')
            ];
        }

        if (!$achievementData->insert($achievements)) {
            return [
                'state' => true,
                'message' => 'Laboratório completado com sucesso! mas não foi possível enviar as conquistas do personagem.'
            ];
        }

        $userRank = (new UserRank())->setTable($server->dbUser . '.dbo.Sys_User_Rank');

        if (
            !$userRank->updateOrCreate(
                ['UserID' => $character->UserID, 'UserRank' => 'Menino Bom'],
                ['UserRank' => 'Menino Bom', 'IsExit' => 1]
            )
        ) {
            return [
                'state' => true,
                'message' => 'Laboratório completado com sucesso! mas não foi possível enviar o título do personagem.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Laboratório completado com sucesso'
        ];
    }
}
