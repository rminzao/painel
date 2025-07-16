<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\GmActiveReward;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;

class GmActivityReward extends Api
{
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid request
        $sid = $post['sid'] ?? null;
        $giftId = $post['giftId'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new GmActiveReward())->setTable($server->dbData . '.dbo.GM_Active_Reward');
        $query = $model->select('*');

        //filters
        if ($giftId != '') {
            $query = $query->where('giftId', $giftId);
        }

        //get item list
        $rewards = $query->get()?->toArray();
        $rewardList = array_map(function ($rewards) use ($server) {

            //get item name
            $modelGoods = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
            $goods = $modelGoods->select('Name', 'CanCompose', 'CanStrengthen', 'MaxCount')->where('TemplateID', $rewards['templateId'])->first();

            if (!$goods) {
                return $rewards;
            }

            $rewards['Name'] = $goods->Name;
            $rewards['CanCompose'] = $goods->CanCompose;
            $rewards['CanStrengthen'] = $goods->CanStrengthen;
            $rewards['MaxCount'] = $goods->MaxCount;
            $rewards['Icon'] = image_item($rewards['templateId'], $server->dbData);
            return $rewards;
        }, $rewards);

        return [
            'state' => true,
            'data' => $rewardList ?? []
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

        $model = (new GmActiveReward())->setTable($server->dbData . '.dbo.GM_Active_Reward');

        $params = $post;
        $params['isBind'] = isset($post['isBind']) ? 1 : 0;
        unset($params['sid']);

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item criado com sucesso.'
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

        $model = (new GmActiveReward())
          ->setTable($server->dbData . '.dbo.GM_Active_Reward');

        $query = $model
          ->where('templateId', $post['templateId'])
          ->where('giftId', $post['giftId']);

        $params = $post;
        $params['isBind'] = isset($post['isBind']) ? 1 : 0;

        unset($params['sid']);

        if (!$query->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item atualizado com sucesso.'
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

        $model = (new GmActiveReward())->setTable($server->dbData . '.dbo.GM_Active_Reward');
        $query = $model->where('templateId', $post['templateId'])->where('giftId', $post['giftId']);

        if (!$query->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao deletar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item deletado com sucesso.'
        ];
    }
}
