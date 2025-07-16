<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\GmGift as EventsGmGift;
use App\Models\Server;
use Core\Routing\Request;

class GmGift extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();

        //filter and valid page request
        $sid = $post['sid'] ?? null;
        $activityId = $post['activityId'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $data = (new EventsGmGift())
            ->setTable($server->dbData . '.dbo.GM_Gift')
            ->where('activityId', $activityId)
            ->orderBy('giftbagOrder', 'ASC')
            ->get()?->toArray();

        return [
            'state' => true,
            'data' => $data ?? []
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new EventsGmGift())->setTable($server->dbData . '.dbo.GM_Gift');

        $params = $post;
        unset($params['sid']);
        $params['giftbagId'] = uuid();

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar gift.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Gift criado com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new EventsGmGift())->setTable($server->dbData . '.dbo.GM_Gift');
        $query = $model->where('giftbagId', $post['giftbagId']);
        $params = $post;
        unset($params['sid']);

        if (!$query->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar gift.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Gift atualizar com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new EventsGmGift())->setTable($server->dbData . '.dbo.GM_Gift');
        $query = $model->find($post['id']);
        if(!$query) {
            return [
                'state' => false,
                'message' => 'Gift not found.'
            ];
        }

        if (!$query->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao deletar gift.'
            ];
        }

        //delete conditions
        $model = (new EventsGmGift())->setTable($server->dbData . '.dbo.GM_Active_Condition');
        $query = $model->where('giftbagId', $post['id'])->delete();

        return [
            'state' => true,
            'message' => 'Gift deletado com sucesso.'
        ];
    }
}
