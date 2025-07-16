<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\User as ModelsUser;
use App\Models\UserGoods;
use App\Models\UserMessages;
use Core\Routing\Request;
use Core\View\Paginator;

class Message extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();

        //filter and valid page request
        $page = $post['page'] ?? 1;
        $sid = $post['sid'] ?? null;
        $search = $post['search'] ?? '';
        $limit = $post['limit'] ?? 10;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
        $query = $model->select('*')->where('ReceiverID', $post['uid']);

        //filters
        if ($search != '') {
            $query = $query->where(function ($query) use ($search) {
                $query->orWhere('Title', 'LIKE', "%{$search}%")
                    ->orWhere('Content', 'LIKE', "%{$search}%")
                    ->orWhere('Sender', 'LIKE', "%{$search}%");
            });
        }

        $query = $query->orderBy('ID', 'DESC');

        $pager = new Paginator(url($request->getUri()), onclick: "message.list");
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $messages = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();

        //get item detail and append to item list
        foreach ($messages as &$message) {
            $message['annexList'] = $this->getAnnexList($message, $server);
            $message['TimeAgo'] = date_fmt_ago($message['SendTime']);

            if ($message['SenderID'] != 0) {
                $username = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail')->where('UserID', $message['SenderID'])->first()->UserName;
                //find user
                $userData = ModelsUser::where('u_hash', $username)->first();
                $message['user_app'] = [
                    'id' => $userData->id,
                    'photo' => image_avatar($userData->photo, 50, 50),
                ];
            }
        }

        return [
            'state' => true,
            'data' => $messages ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    protected function getAnnexList($data, $server)
    {
        $annexList = [];

        for ($i = 1; $i <= 5; $i++) {
            if ($data['Annex' . $i] == null) {
                continue;
            }

            $bagItem = (new UserGoods())
                ->setTable($server->dbUser . '.dbo.Sys_Users_Goods')
                ->where('ItemID', $data['Annex' . $i])
                ->first()?->toArray();

            if (!$bagItem) {
                continue;
            }

            $itemData = (new ShopGoods())
                ->setTable($server->dbData . '.dbo.Shop_Goods')
                ->select('Name', 'Pic', 'NeedSex', 'CategoryID', 'CanStrengthen', 'CanCompose', 'Description')
                ->where('TemplateID', $bagItem['TemplateID'])
                ->first()?->toArray();

            if (!$itemData) {
                continue;
            }

            $itemData['Icon'] = image_item($bagItem['TemplateID'], $server->dbData);
            $annexList[$data['Annex' . $i]] = array_merge($bagItem, $itemData);
        }

        return $annexList;
    }
}
