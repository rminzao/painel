<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\GmActiveCondition;
use App\Models\Events\GmGift;
use App\Models\Server;
use Core\Routing\Request;

class GmActivityCondition extends Api
{
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition');

        $data = $model->get()?->toArray();

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

        $model = (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition');

        //check if exist same condition
        $condition = $model->where([
            ['conditionIndex', $post['conditionIndex']],
            ['giftbagId', $post['giftbagId']],
        ])->first();
        if ($condition) {
            return [
                'state' => false,
                'message' => "A conditionIndex [<b><span class=\"text-primary\">{$post['conditionIndex']}</span></b>] ja está em uso."
            ];
        }

        $params = $post;
        unset($params['sid']);

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar condição.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Condição criada com sucesso.'
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

        $model = (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition');
        $query = $model->where('giftbagId', $post['giftbagId'])->where('conditionIndex', $post['originalConditionIndex']);
        $params = $post;
        unset($params['sid']);
        unset($params['originalConditionIndex']);

        if (!$query->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar condição.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Condição atualizada com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;
        $index = $post['index'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition');

        $condition = $model->where('giftbagId', $id)->where('conditionIndex', $index);
        if (!$condition) {
            return [
                'state' => false,
                'message' => 'Condição não encontrada.'
            ];
        }

        if(!$condition->delete()){
            return [
                'state' => false,
                'message' => 'Falha ao deletar condição.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Condição deletada.'
        ];
    }
}
