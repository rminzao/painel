<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Game\GameMap;
use App\Models\Server;
use Core\View\Paginator;

class Map extends Api
{
    public function list()
    {
        $get = $this->request->get();

        $sid = $get['sid'] ?? null;
        $page = $get['page'] ?? 1;
        $limit = $get['limit'] ?? 10;
        $onclick = $get['onclick'] ?? 'map.list';
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

        $model = (new GameMap())->setTable($server->dbData . '.dbo.Game_Map');
        $query = $model->select('*');



        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('ID', 'LIKE', "%{$search}%") :
                $query->where('Name', 'LIKE', "%{$search}%")->orWhere('Description', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy($sort, $order);

        $pager = new Paginator(url($this->request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 1);

        $maps = $query
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->get()
            ?->toArray();

        foreach ($maps as &$map) {
            $map['bg'] = $server->resource . '/image/map/' . $map['ID'] . '/back.jpg';
            $map['fg'] = $server->resource . '/image/map/' . $map['ID'] . '/fore.png';
            $map['dg'] = $server->resource . '/image/map/' . $map['ID'] . '/dead.png';
            $map['backgroundMusic'] = $server->resource . '/sound/' . $map['BackMusic'] . '.flv';
        }

        return [
            'state' => true,
            'maps' => $maps ?? [],
            'pagination' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'render' => $pager->render()
            ]
        ];
    }

    public function create()
    {
    }

    public function update()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'] ?? null;
        $id = $post['ID'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new GameMap())->setTable($server->dbData . '.dbo.Game_Map');
        $map = $model->find($id);
        if (!$map) {
            return [
                'state' => false,
                'message' => 'Mapa não encontrado.'
            ];
        }

        unset($post['sid']);
        unset($post['id']);
        if (!$map->update($post)) {
            return [
                'state' => false,
                'message' =>  'Erro ao atualizar mapa.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Mapa atualizado com sucesso.'
        ];
    }

    public function delete()
    {
    }
}
