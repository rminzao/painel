<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\DropCondition;
use App\Models\DropItem;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Utils\GameTypes\eReloadType;
use Core\Utils\Wsdl;
use Core\View\Paginator;

/* The Quest class is used to create, update and delete quests */
class Drop extends Api
{
    public function list(): array
    {
        $post = $this->request->get();

        $page = filter_var($post['page'], FILTER_VALIDATE_INT);
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $search = $post['search'] ?? '';
        $limit = $post['limit'] ?? 10;
        $type = $post['type'] ?? 'all';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new DropCondition())->setTable($server->dbData . '.dbo.Drop_Condiction');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('DropID', 'LIKE', "%{$search}%") :
                $query->where('Name', 'LIKE', "%{$search}%");
        }

        if ($type != 'all') {
            $query = $query->where('CondictionType', $type);
        }

        $query = $query->orderBy('DropID', 'ASC');

        $pager = new Paginator(url($this->request->getUri()), onclick: "drop.list");
        $pager->pager($query->count(), $limit, $page, 1);

        //get item list
        $items = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();
        $dropList = array_map(function ($items) use ($server) {
            $items['Pic'] = '';
            return $items;
        }, $items);

        return [
            'state' => true,
            'items' => $dropList ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create(): array
    {
        $post = $this->request->post(false);

        //check fields
        $check = $post;
        unset($check['name']);
        unset($check['detail']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Preencha todo os campos obrigatórios.'
            ];
        }

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new DropCondition())->setTable($server->dbData . '.dbo.Drop_Condiction');

        if (
            !$model->insert([
            'DropID' => $model->max('DropID') + 1,
            'CondictionType' => $post['condictionType'],
            'Para1' => $post['para1'],
            'Para2' => $post['para2'],
            'Name' => $post['name'] == '' ? null : $post['name'],
            'Detail' =>  $post['detail'] == '' ? null : $post['detail'],
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Falha ao criar, verifique os dados e tente novamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Drop criado com sucesso.'
        ];
    }

    public function update(): array
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $did = filter_var($post['did'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new DropCondition())->setTable($server->dbData . '.dbo.Drop_Condiction');
        $drop = $model->where('DropID', $did);
        if (!$drop) {
            return [
                'state' => false,
                'message' => 'Drop não encontrado.'
            ];
        }

        if (
            !$drop->update([
            'CondictionType' => $post['condictionType'],
            'Para1' => $post['para1'],
            'Para2' => $post['para2'],
            'Name' => $post['name'],
            'Detail' => $post['detail'],
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar, verifique os dados e tente novamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Drop atualizado com sucesso.'
        ];
    }

    public function delete(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $did = filter_var($post['dropID'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new DropCondition())->setTable($server->dbData . '.dbo.Drop_Condiction');
        $drop = $model->where('DropID', $did);
        if (!$drop) {
            return [
                'state' => false,
                'message' => 'Drop não encontrado'
            ];
        }

        if (!$drop->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao apagar drop, verifique os dados e tente novamente.'
            ];
        }

        //find dorp items and delete
        $model = (new DropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        $items = $model->where('DropId', $did);
        if ($items) {
            $items->delete();
        }

        return [
            'state' => true,
            'message' => 'Drop removido com sucesso.'
        ];
    }

    public function pveImport(): array
    {
        $post = $this->request->post(false);

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $ids = explode(',', $post['ids']);
        $ids = array_filter(array_map('trim', $ids));

        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $items = $model->select('TemplateID')->whereIn('TemplateID', $ids)->get()?->toArray();
        if (!$items) {
            return [
                'state' => false,
                'message' => 'Nenhum item informado é válido.'
            ];
        }

        foreach ($items as &$item) {
            $item['DropId'] = $post['id'];
            $item['ItemId'] = $item['TemplateID'];
            $item['ValueDate'] = 0;
            $item['IsBind'] = 1;
            $item['Random'] = 0;
            $item['BeginData'] = 1;
            $item['EndData'] = 1;
            $item['IsTips'] = 0;
            $item['IsLogs'] = 0;
            unset($item['TemplateID']);
        }

        $modelDrop = (new DropItem())->setTable($server->dbData . '.dbo.Drop_Item');
        if (!$modelDrop->insert($items)) {
            return [
                'state' => false,
                'message' => 'Falha ao importar, verifique os dados e tente novamente.'
            ];
        }


        return [
            'state' => true,
            'message' => 'Drop\'s importados com sucesso.'
        ];
    }

    public function import()
    {
        $post = $this->request->post(false);
        $id = $post['id'] ?? 0;
        $hash = $post['hash'] ?? '';

        if (in_array('', $post)) {
            return ['state' => false, 'message' => 'Todos os campos são obrigatórios.'];
        }

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $condition = (new DropCondition($server->dbData))->find($id);
        if (!$condition) {
            return ['state' => false, 'message' => 'Drop não encontrado, atualize a página e tente novamente.'];
        }

        try {
            $rewards = json_decode(base64_decode($hash), true);
        } catch (\Throwable $th) {
            return [
              'state' => false,
              'message' => 'A chave inserida parece estar quebrada, verifique os dados e tente novamente.'
            ];
        }

        foreach ($rewards as &$reward) {
            unset($reward['Id']);
            $reward['DropId'] = $id;
        }

        $model = new DropItem($server->dbData);
        if (!$model->insert($rewards)) {
            return [
              'state' => false,
              'message' => 'Falha ao importar recompensas.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Recompensas importadas com sucesso.'
        ];
    }

    public function export()
    {
        $get = $this->request->get();

        $server = Server::find($get['sid']);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $drops = (new DropItem($server->dbData))
          ->where('DropId', $get['id'])
          ->get()
          ?->toArray();

        if (!$drops) {
            return [
              'state' => false,
              'message' => 'Falha ao exportar, nenhuma recompensa foi encontrada.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Recompensa\'s exportada\'s com sucesso.',
          'content' => base64_encode(json_encode($drops))
        ];
    }

    public function updateOnGame(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //send wsdl reload
        (new Wsdl())->reload(Wsdl::DROP, $server);

        return [
            'state' => true,
            'message' => 'Drops atualizados com sucesso.'
        ];
    }
}
