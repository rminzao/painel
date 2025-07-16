<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\DropItem as ModelsDropItem;
use App\Models\Server;
use App\Models\ShopGoods;

class DropItem extends Api
{
    public function list()
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $did = filter_var($post['did'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsDropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        $rewards = $model->where('DropId', $did)->get()->toArray();

        $rewardList = array_map(function ($rewards) use ($server) {
            $modelItem = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
            $item = $modelItem->where('TemplateID', $rewards['ItemId'])->first();
            if (!$item) {
                return $rewards;
            }
            $rewards['Name'] = $item->Name;
            $rewards['MaxCount'] = $item->MaxCount;
            $rewards['NeedSex'] = $item->NeedSex;
            $rewards['CanEquip'] = $item->CanEquip;
            $rewards['CanCompose'] = $item->CanCompose;
            $rewards['CanStrengthen'] = $item->CanStrengthen;
            $rewards['Pic'] = image_item($rewards['ItemId'], $server->dbData);
            return $rewards;
        }, $rewards);

        return [
            'state' => true,
            'items' => $rewardList
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $did = filter_var($post['did'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsDropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        $model->DropId = $did;
        $model->ItemId = $post['itemID'];
        $model->ValueDate = $post['valid'];
        $model->IsBind = isset($post['isBind']) ? 1 : 0;
        $model->Random = $post['random'];
        $model->BeginData = $post['beginData'];
        $model->EndData = $post['endData'];
        $model->IsTips = isset($post['isTips']) ? 1 : 0;
        $model->IsLogs = isset($post['isLogs']) ? 1 : 0;
        if (!$model->save()) {
            return [
                    'state' => false,
                    'message' => 'Falha ao adicionar item.'
                ];
        }

        return [
            'state' => true,
            'message' => 'Item adicionado com sucesso.'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $id = filter_var($post['id'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsDropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        $drop = $model->where('Id', $id);
        if (!$drop) {
            return [
                'state' => false,
                'message' => 'Item not found.'
            ];
        }

        if (
            !$drop->update([
            'ValueDate' => $post['valid'],
            'Random' => $post['random'],
            'BeginData' => $post['beginData'],
            'EndData' => $post['endData'],
            'IsTips' => isset($post['isTips']) ? 1 : 0,
            'IsBind' => isset($post['isBind']) ? 1 : 0,
            'IsLogs' => isset($post['isLogs']) ? 1 : 0
            ])
        ) {
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

    public function delete()
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $did = filter_var($post['did'], FILTER_VALIDATE_INT);
        $id = filter_var($post['id'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $whereDelete = ($id != 0) ? [
            ['Id', '=', $id]
        ] : [['DropId', '=', $did]];


        $model = (new ModelsDropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        $quest = $model->where($whereDelete);
        if (!$quest) {
            return [
                'state' => false,
                'message' => 'Recompensa não encontrada.'
            ];
        }

        if (!$quest->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao remover recompensa'
            ];
        }

        log_system(
            $this->user->id,
            "Removeu recompensa [<b>{$id}</b>] do drop [<b>{$did}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Recompensa removida com sucesso'
        ];
    }
}
