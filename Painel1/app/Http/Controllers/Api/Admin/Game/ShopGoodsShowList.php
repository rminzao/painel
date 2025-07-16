<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\ShopGoodsShowList as ModelsShopGoodsShowList;
use Core\Routing\Request;
use GuzzleHttp\Client;

class ShopGoodsShowList extends Api
{
    public function list(Request $request)
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

        $model = (new ModelsShopGoodsShowList($server->dbData, $server->version));
        $list = $model
          ->where('ShopId', $id)
          ->get()
          ?->toArray();

        return [
            'state' => true,
            'data' => $list ?? []
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

        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos.'
            ];
        }

        $model = (new ModelsShopGoodsShowList($server->dbData, $server->version));

        if ($model->where('ShopId', $post['shopID'])->where('Type', $post['type'])->first()) {
            return [
                'state' => false,
                'message' => 'Este item já está na lista.'
            ];
        }

        if (
            !$model->insert([
            'Type' => $post['type'],
            'ShopId' => $post['shopID']
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Erro ao adicionar item a loja.'
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

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //check if fields are empty
        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos.'
            ];
        }

        $model = (new ModelsShopGoodsShowList($server->dbData, $server->version));

        $query = $model
            ->where('ShopId', $post['shopID'])
            ->where('Type', $post['originalType']);

        if (
            !$query->update([
            'Type' => $post['type'],
            'ShopId' => $post['shopID']
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Atualizado com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;
        $type = $post['type'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsShopGoodsShowList($server->dbData, $server->version));

        $query = $model->where('ShopId', $id)->where('Type', $type);
        if (!$query->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao remover item da loja.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Removido com sucesso.'
        ];
    }
}
