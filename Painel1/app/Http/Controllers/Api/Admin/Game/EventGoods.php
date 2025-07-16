<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\EventGoods as ModelEventGoods;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;

class EventGoods extends Api
{
    /**
     * It gets the list of items from the database and returns it to the frontend
     *
     * @param Request request The request object
     */
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid request
        $sid = $post['sid'] ?? null;
        $activeType = $post['activityType'] ?? '';
        $subActivityType = $post['subActivityType'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelEventGoods())->setTable($server->dbData . '.dbo.Event_Reward_Goods');
        $query = $model->select('*');

        //filters
        if ($activeType != '') {
            $query = $query->where('ActivityType', $activeType);
        }
        if ($subActivityType != '') {
            $query = $query->where('SubActivityType', $subActivityType);
        }

        $query = $query->orderBy('ActivityType', 'ASC');

        //get item list
        $rewards = $query->get()->toArray();
        $rewardList = array_map(function ($rewards) use ($server) {
            //get item name
            $modelGoods = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
            $goods = $modelGoods->select('Name', 'CanCompose', 'CanStrengthen', 'MaxCount')->where('TemplateID', $rewards['TemplateId'])->first();
            $rewards['Name'] = $goods->Name;
            $rewards['CanCompose'] = $goods->CanCompose;
            $rewards['CanStrengthen'] = $goods->CanStrengthen;
            $rewards['MaxCount'] = $goods->MaxCount;
            $rewards['Icon'] = image_item($rewards['TemplateId'], $server->dbData);
            return $rewards;
        }, $rewards);

        return [
            'state' => true,
            'data' => $rewardList ?? []
        ];
    }

    /**
     * It receives a request, validates the server, creates a new model and saves it
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $activityType = $post['activityType'] ?? null;
        $subActivityType = $post['subActivityType'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelEventGoods())->setTable($server->dbData . '.dbo.Event_Reward_Goods');
        $model->ActivityType = $activityType;
        $model->SubActivityType = $subActivityType;
        $model->TemplateId = $post['itemID'] ?? 0;
        $model->Count = $post['count'] ?? 1;
        $model->IsBind = isset($post['isBind']) ? 1 : 0;
        $model->ValidDate = $post['validDate'] ?? 0;
        $model->StrengthLevel = $post['strengthLevel'] ?? 0;
        $model->AttackCompose = $post['attackCompose'] ?? 0;
        $model->DefendCompose = $post['defendCompose'] ?? 0;
        $model->LuckCompose = $post['luckCompose'] ?? 0;
        $model->AgilityCompose = $post['agilityCompose'] ?? 0;

        if(!$model->save()){
            return [
                'state' => false,
                'message' => 'Falha ao criar recompensa.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Recompensa criada com sucesso.'
        ];
    }

    /**
     * I'm trying to update a table in a database that is not the default database.
     * </code>
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelEventGoods())->setTable($server->dbData . '.dbo.Event_Reward_Goods');
        $reward = $model->where('Id', $id);

        if(!$reward->update([
            'Count' => $post['count'] ?? 1,
            'IsBind' => isset($post['isBind']) ? 1 : 0,
            'ValidDate' => $post['validDate'] ?? 0,
            'StrengthLevel' => $post['strengthLevel'] ?? 0,
            'AttackCompose' => $post['attackCompose'] ?? 0,
            'DefendCompose' => $post['defendCompose'] ?? 0,
            'LuckCompose' => $post['luckCompose'] ?? 0,
            'AgilityCompose' => $post['agilityCompose'] ?? 0,
        ])){
            return [
                'state' => false,
                'message' => 'Falha ao atualizar recompensa.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Recompensa atualizada com sucesso.'
        ];
    }

   /**
    * Delete a item from the database
    *
    * @param Request request The request object.
    *
    * @return The return is a json with the following structure:
    */
    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //validate item and delete item
        $model = (new ModelEventGoods())->setTable($server->dbData . '.dbo.Event_Reward_Goods');
        if(!$model->where('Id', $id)->delete()){
            return [
                'state' => false,
                'message' => 'Falha ao deletar recompensa.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Recompensa deletada com sucesso.'
        ];
    }
}
