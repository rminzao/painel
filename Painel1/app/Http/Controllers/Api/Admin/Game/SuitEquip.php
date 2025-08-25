<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\SuitPartEquip;
use Core\Routing\Request;

class SuitEquip extends Api
{
    public function list(Request $request): array
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartEquip())->setTable($server->dbData . '.dbo.Suit_Part_Equip');
        $query = $model->where('ID', $id);
        $parts = $query->get()?->toArray();

        $data = [];
        foreach ($parts as $item) {
            $items = [];
            $equipments = explode(',', $item['ContainEquip']);
            foreach ($equipments as $id) {

                $modelGoods = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
                $goods = $modelGoods->select('TemplateID', 'Name', 'NeedSex')->where('TemplateID', intval($id))->first()?->toArray();
                if(!$goods){
                    continue;
                }

                $items[] = $goods;
            }

            $data[] = array_merge($item, [
                'ContainEquipDetail' => $items
            ]);
        }

        return [
            'state' => true,
            'data' => $data ?? []
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartEquip())->setTable($server->dbData . '.dbo.Suit_Part_Equip');

        //check
        if($model->where('PartName', $post['PartName'])->first()){
            return [
                'state' => false,
                'message' => 'Esse nome já existe.'
            ];
        }

        $model->ID = $id;
        $model->PartName = $post['PartName'];
        $model->ContainEquip = implode(',', $post['ContainEquip']);

        if(!$model->save()) {
            return [
                'state' => false,
                'message' => 'Falha ao criar parte de equipamento.'
            ];
        }

        //update item suitId
        $modelGoods = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $modelGoods->whereIn('TemplateID', $post['ContainEquip'])->update(['SuitID' => $id]);

        return [
            'state' => true,
            'message' => 'Parte de equipamento criada com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartEquip())->setTable($server->dbData . '.dbo.Suit_Part_Equip');
        $query = $model->where('ID', $id)->where('PartName', $post['PartNameOriginal']);
        if(!$model->first()){
            return [
                'state' => false,
                'message' => 'Parte de equipamento não encontrada.'
            ];
        }

        if(!$query->update([
            'PartName' => $post['PartName'],
            'ContainEquip' => implode(',', $post['ContainEquip'])
        ])){
            return [
                'state' => false,
                'message' => 'Falha ao atualizar parte de equipamento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Parte de equipamento atualizada com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;
        $name = $post['name'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartEquip())->setTable($server->dbData . '.dbo.Suit_Part_Equip');

        $query = $model->where('ID', $id)->where('PartName', $name);
        if(!$query){
            return [
                'state' => false,
                'message' => 'Parte de conjunto não encontrado.'
            ];
        }

        if(!$query->delete()){
            return [
                'state' => false,
                'message' => 'Falha ao deletar parte do conjunto.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Parte do conjunto deletado com sucesso.'
        ];
    }
}
