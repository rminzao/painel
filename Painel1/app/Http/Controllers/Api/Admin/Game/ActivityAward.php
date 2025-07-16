<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\ActiveAward;
use App\Models\Events\EventGoods as ModelEventGoods;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;

class ActivityAward extends Api
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
        $activeID = $post['activeID'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $query = $model->select('*');

        //filters
        if ($activeID != '') {
            $query = $query->where('ActiveID', $activeID);
        }

        $query = $query->orderBy('ID', 'ASC');

        //get item list
        $rewards = $query->get()->toArray();
        $rewardList = array_map(function ($rewards) use ($server) {
            //get item name
            $modelGoods = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
            $goods = $modelGoods->select('Name', 'CanCompose', 'CanStrengthen', 'MaxCount')->where('TemplateID', $rewards['ItemID'])->first();
            $rewards['Name'] = $goods->Name;
            $rewards['CanCompose'] = $goods->CanCompose;
            $rewards['CanStrengthen'] = $goods->CanStrengthen;
            $rewards['MaxCount'] = $goods->MaxCount;
            $rewards['Icon'] = image_item($rewards['ItemID'], $server->dbData);
            return $rewards;
        }, $rewards);

        return [
            'state' => true,
            'data' => $rewardList ?? []
        ];
    }

    /**
     * I'm trying to save a new row in a table that is not the default table of the model.
     * </code>
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the state and message.
     */
    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $activeID = $post['activeID'] ?? '';

        //find server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $model->ActiveID = $activeID;
        $model->ItemID = $post['itemID'];
        $model->Count = $post['count'] ?? 1;
        $model->ValidDate = $post['validDate'] ?? 0;
        $model->StrengthenLevel = $post['strengthLevel'] ?? 0;
        $model->AttackCompose = $post['attackCompose'] ?? 0;
        $model->DefendCompose = $post['defendCompose'] ?? 0;
        $model->LuckCompose = $post['luckCompose'] ?? 0;
        $model->AgilityCompose = $post['agilityCompose'] ?? 0;
        $model->Gold = $post['gold'] ?? 0;
        $model->Money = $post['money'] ?? 0;
        $model->Sex = isset($post['Sex']) ? 1 : 0;
        $model->Mark = $post['mark'] ?? 0;

        if(!$model->save()){
            return [
                'state' => false,
                'message' => 'Falha ao adicionar item ao evento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item adicionado com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? '';

        //find server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $query = $model->find($id);

        if(!$query->update([
            'Count' => $post['count'] ?? 1,
            'ValidDate' => $post['validDate'] ?? 0,
            'StrengthenLevel' => $post['strengthLevel'] ?? 0,
            'AttackCompose' => $post['attackCompose'] ?? 0,
            'DefendCompose' => $post['defendCompose'] ?? 0,
            'LuckCompose' => $post['luckCompose'] ?? 0,
            'AgilityCompose' => $post['agilityCompose'] ?? 0,
            'Gold' => $post['gold'] ?? 0,
            'Money' => $post['money'] ?? 0,
            'Sex' => isset($post['sex']) ? 1 : 0,
            'Mark' => $post['mark'] ?? 0,
        ])){
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

    /**
     * It deletes a row from a table in a database
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? 0;
        $activeID = $post['activeID'] ?? null;

        //find server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $query = $model->where('ActiveID', $activeID);

        if($id != 0){
            $query = $model->where('ID', $post['id']);;
        }

        if(!$query->delete()){
            return [
                'state' => false,
                'message' => 'Falha ao remover item do evento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item removido com sucesso.'
        ];
    }
}
