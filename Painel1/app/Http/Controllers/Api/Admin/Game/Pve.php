<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Game\PveInfo;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\View\Paginator;

class Pve extends Api
{
    public function list()
    {
        $get = $this->request->get();

        $sid = $get['sid'] ?? null;
        $page = $get['page'] ?? 1;
        $limit = $get['limit'] ?? 10;
        $type = $get['type'] ?? 0;
        $onclick = $get['onclick'] ?? 'pve.list';
        $sort = $get['sort'] ?? 'ID';
        $order = $get['order'] ?? 'asc';
        $search = $get['search'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new PveInfo($server->dbData));
        $query = $model->select('*');

        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('ID', 'LIKE', "%{$search}%") :
                $query->where('Name', 'LIKE', "%{$search}%")->orWhere('Description', 'LIKE', "%{$search}%");
        }

        if ($type != 0) {
            $query = $query->where('Type', $type);
        }

        $query = $query->orderBy($sort, $order);

        $pager = new Paginator(url($this->request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 1);

        $data = $query
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->get()
            ?->toArray();

        foreach ($data as &$pve) {
            $pve['ImageDefault'] = $server->resource . '/image/map/0/samll_map.png';
            $pve['Image'] = $server->resource . '/image/map/0/samll_map.png';

            $itemModel = (new ShopGoods($server->dbData));

            try {
                $pve['Item'] = [
                    'Simple' => $itemModel->getByListId(explode(',', $pve['SimpleTemplateIds'])),
                    'Normal' => $itemModel->getByListId(explode(',', $pve['NormalTemplateIds'])),
                    'Hard' => $itemModel->getByListId(explode(',', $pve['HardTemplateIds'])),
                    'Terror' => $itemModel->getByListId(explode(',', $pve['TerrorTemplateIds'])),
                  ];
            } catch (\Throwable $th) {
                dd($pve);

                dd($th);
            }

            $pve['Image'] = $server->resource . '/image/map/' . $pve['ID'] . '/samll_map.png';
        }

        return [
            'state' => true,
            'data' => $data ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'] ?? null;

        if ($post['ID'] == 0 || !$post['ID']) {
            return [
              'state' => false,
              'message' => 'A <span class="text-warning">ID inserida</span> não é válida.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor não encontrado.'
            ];
        }

        unset($post['sid']);

        $model = (new PveInfo($server->dbData));

        if ($check = $model->find($post['ID'])) {
            return [
              'state' => false,
              'message' => 'A instância <span class="text-warning">' . $check->Name .
              '</span> ja está utilizando esta ID, tente outra.'
            ];
        }

        if (!$model->insert($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao adicionar a instancia.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Instancia adicionada com sucesso.'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'] ?? null;
        $id = $post['OriginalID'] ?? null;

        if ($post['ID'] == 0 || !$post['ID']) {
            return [
              'state' => false,
              'message' => 'A <span class="text-warning">ID inserida</span> não é válida.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor não encontrado.'
            ];
        }

        unset($post['sid']);
        unset($post['OriginalID']);

        if ($id != $post['ID']) {
            $check = (new PveInfo($server->dbData))->find($post['ID']);
            if ($check) {
                return [
                  'state' => false,
                  'message' => 'A instância <span class="text-warning">' . $check->Name .
                  '</span> ja está utilizando esta ID, tente outra.'
                ];
            }
        }

        $model = (new PveInfo($server->dbData))->find($id);
        if (!$model->update($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao atualizar a instancia.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Instancia atualizada com sucesso.'
        ];
    }

    public function delete()
    {
        $post = $this->request->get();
        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new PveInfo($server->dbData));
        $pve = $model->find($id);
        if (!$pve) {
            return [
              'state' => false,
              'message' => 'Instancia não encontrada.'
            ];
        }

        if (!$pve->delete()) {
            return [
              'state' => false,
              'message' => 'Falha ao excluir Instancia.'
            ];
        }
        return [
          'state' => true,
          'message' => 'Instancia excluída com sucesso.'
        ];
    }
}
