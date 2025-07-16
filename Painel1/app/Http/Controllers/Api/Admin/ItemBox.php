<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\ShopGoodsBox;
use Core\Routing\Request;

class ItemBox extends Api
{
    /**
     * Get the items in a box
     * @param Request request The request object.
     * @return An array of items.
     */
    public function find(Request $request): array
    {
        $post = $request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }


        $model = (new ShopGoodsBox())->setTable($server->dbData . '.dbo.Shop_Goods_Box');
        $boxItem = $model->where('DataId', $post['itemID'])->get()->toArray();

        $itemList = array_map(function ($boxItem) use ($server) {
            $itemData = ((new ShopGoods())
                ->setTable($server->dbData . '.dbo.Shop_Goods'))
                ->select('Name', 'CategoryID', 'MaxCount', 'NeedSex', 'CanCompose', 'CanStrengthen', 'Pic')
                ->where('TemplateID', $boxItem['TemplateId'])
                ->first()?->toArray();

            if (!$itemData) {
                return $boxItem;
            }

            //add item data to box item
            $boxItem = array_merge($boxItem, $itemData);

            $boxItem['Icon'] = image_item($boxItem['TemplateId'], $server->dbData);
            return $boxItem;
        }, $boxItem);

        //remove empty items
        $itemList = array_filter($itemList);


        return [
            'state' => true,
            'items' => $itemList
        ];
    }

    /**
     * Create a new item box
     *
     * @param Request request The request object.
     *
     * @return The return value is an array with two keys: state and message. The state key is a
     * boolean value that indicates whether the operation was successful or not. The message key
     * contains a string that is either an error message or a success message.
     */
    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ShopGoodsBox())->setTable($server->dbData . '.dbo.Shop_Goods_Box');
        $params = $post;
        unset($params['sid']);
        $params['DataId'] = $post['ID']; // o ID do form representa a caixa
        unset($params['ID']); // remove ID primário (auto increment)
        $params['Random'] = isset($params['Random']) ? $params['Random'] : 1;
        $params['IsTips'] = isset($params['IsTips']) ? 1 : 0;
        $params['IsBind'] = isset($params['IsBind']) ? 1 : 0;
        $params['IsLogs'] = isset($params['IsLogs']) ? 1 : 0;
        $params['IsSelect'] = isset($params['IsSelect']) ? 1 : 0;
        $params['StrengthenLevel'] = isset($params['StrengthenLevel']) ? $params['StrengthenLevel'] : 0;

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Failed to create item box.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item adicionado com sucesso.'
        ];
    }

    /**
     * Update an item box
     *
     * @param Request request The request object.
     *
     * @return The return value is an array with two keys: state and message. The state key is a
     * boolean value that indicates whether the operation was successful. The message key contains a
     * string that is used to provide feedback to the user.
     */
    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ShopGoodsBox())->setTable($server->dbData . '.dbo.Shop_Goods_Box');
        $model = $model->where([
            ['DataId', $post['ID']],
            ['TemplateId', $post['TemplateId']]
        ]);      

        if (!$model) {
            return [
                'state' => false,
                'message' => 'Item box não encontrado, atualize a página.'
            ];
        }

        $params = $post;
        unset($params['ID']);
        unset($params['sid']);
        unset($params['DataId']);
        $params['IsLogs'] = isset($post['IsLogs']) ? 1 : 0;
        $params['IsTips'] = isset($post['IsTips']) ? 1 : 0;
        $params['IsSelect'] = isset($post['IsSelect']) ? 1 : 0;
        $params['IsBind'] = isset($post['IsBind']) ? 1 : 0;

        if (!$model->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar item box.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item box atualizado com sucesso'
        ];
    }

    /**
     * Delete a box from the database
     *
     * @param Request request The request object.
     *
     * @return The return value is an array with two keys: state and message. The state key is a
     * boolean value that indicates whether the operation was successful. The message key contains a
     * string that is used to display a message to the user.
     */
    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $where = ($post['templateID'] != 0) ? [
            ['DataId', $post['itemID']],
            ['TemplateId', $post['templateID']]
        ] : [['DataId', $post['itemID']]];
        
        $model = (new ShopGoodsBox())->setTable($server->dbData . '.dbo.Shop_Goods_Box');
        $model->where($where)->delete();

        return [
            'state' => true,
            'message' => 'Item removido com sucesso!'
        ];
    }
}
