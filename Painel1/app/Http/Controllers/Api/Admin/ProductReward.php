<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Product as ModelsProduct;
use App\Models\ProductReward as ModelsProductReward;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;

class ProductReward extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();

        $id = $post['id'] ?? '';
        if ($id == '') {
            return [
                'state' => false,
                'message' => 'Product id is required'
            ];
        }

        //find reward
        $data = ModelsProductReward::where('pid', $id)->get()?->toArray() ?? [];

        //find product
        $product = ModelsProduct::where('id', $id)->first();

        //find server
        if (!$server = Server::where('id', $product->sid)->first()) {
            return [
                'state' => false,
                'type' => 'server_not_found',
                'message' => 'O servidor informado não existe.',
                'data' => []
            ];
        }

        foreach ($data as &$reward) {
            $item = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods')->find($reward['TemplateID']);
            if (!$item) {
                unset($reward);
                continue;
            }

            $reward['item'] = [
                'Name' => $item->Name,
                'Icon' => image_item($item->TemplateID, $server->dbData),
                'CanStrengthen' => $item->CanStrengthen,
                'CanCompose' => $item->CanCompose,
            ];
        }

        return [
            'state' => true,
            'data' => $data
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);

        $params = $post;
        $params['IsBind'] = isset($post['IsBind']) ? 1 : 0;

        $model = new ModelsProductReward();

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar recompensa'
            ];
        }

        return [
            'state' => true,
            'message' => 'Recompensa criada com sucesso'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $id = $post['id'] ?? '';

        $reward = ModelsProductReward::find($id);
        if (!$reward) {
            return [
                'state' => false,
                'message' => 'Recompensa não encontrada, talvez tenha sido excluída ou não existe'
            ];
        }

        $params = $post;
        $params['IsBind'] = isset($post['IsBind']) ? 1 : 0;

        unset($params['id']);

        if (!$reward->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar recompensa'
            ];
        }

        return [
            'state' => true,
            'message' => 'Recompensa atualizada com sucesso'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();

        $id = $post['id'] ?? '';

        $reward = ModelsProductReward::find($id);
        if (!$reward) {
            return [
                'state' => false,
                'message' => 'Recompensa não encontrada, atualize a página.'
            ];
        }

        if (!$reward->delete()) {
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
