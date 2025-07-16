<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\ActivitySystemItem;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;

class ActivitySystem extends Api
{
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $sid = $post['sid'] ?? null;
        $type = $post['type'] ?? 0;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivitySystemItem())->setTable($server->dbData . '.dbo.Activity_System_Item');

        $query = $model->select('*')->where('ActivityType', $type)->orderBy('ID', 'ASC');

        //get item list
        $list = $query->get()?->toArray();
        $list = array_map(function ($shop) use ($server) {
            $shop['Item'] = $this->getItem($server, $shop['TemplateID']);
            $shop['Item']['Icon'] = image_item($shop['TemplateID'], $server->dbData);
            return $shop;
        }, $list);

        //get quality list
        $qualities = array_values(array_unique(array_column($list, 'Quality')));

        return [
            'state' => true,
            'data' => $list ?? [],
            'qualities' => $qualities ?? []
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //validate data
        $parameters = $post;
        unset($parameters['sid']);
        $parameters['IsBind'] = isset($parameters['IsBind']) ? 1 : 0;
        $parameters['canRenew'] = isset($parameters['canRenew']) ? 1 : 0;
        $parameters['canTransfer'] = isset($parameters['canTransfer']) ? 1 : 0;
        $parameters['canRepeat'] = isset($parameters['canRepeat']) ? 1 : 0;

        //create item
        $model = (new ActivitySystemItem())->setTable($server->dbData . '.dbo.Activity_System_Item');
        if (!$model->insert($parameters)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item criado com sucesso.',
            'type' => $parameters['ActivityType']
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['ID'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //validate data
        $parameters = $post;
        unset($parameters['sid']);
        $parameters['IsBind'] = isset($parameters['IsBind']) ? 1 : 0;
        $parameters['canRenew'] = isset($parameters['canRenew']) ? 1 : 0;
        $parameters['canTransfer'] = isset($parameters['canTransfer']) ? 1 : 0;
        $parameters['canRepeat'] = isset($parameters['canRepeat']) ? 1 : 0;


        //find item
        $model = (new ActivitySystemItem())->setTable($server->dbData . '.dbo.Activity_System_Item');
        $item = $model->find($id);

        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item not found.'
            ];
        }

        //update item
        if (!$item->update($parameters)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item atualizado com sucesso.',
            'type' => $item->ActivityType
        ];
    }

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

        //validate item
        $item = (new ActivitySystemItem())->setTable($server->dbData . '.dbo.Activity_System_Item')->find($id);
        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item not found.'
            ];
        }

        //delete item
        if (!$item->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao deletar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item deletado com sucesso.',
        ];
    }

    protected function getItem(Server $server, int $templateId)
    {
        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $item = $model->select('Name', 'NeedSex')->where('TemplateID', $templateId)->first()?->toArray();
        return $item;
    }
}
