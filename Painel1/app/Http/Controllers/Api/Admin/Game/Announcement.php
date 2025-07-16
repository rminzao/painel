<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Game\EdictumList;
use App\Models\Server;
use Carbon\Carbon;
use Core\View\Paginator;

class Announcement extends Api
{
    public function list()
    {
        $get = $this->request->get();

        $sid = $get['sid'] ?? 0;
        $page = $get['page'] ?? 1;
        $limit = $get['limit'] ?? 10;
        $search = $get['search'] ?? '';
        $state = $get['state'] ?? 'all';

        $data = [];

        try {
            $server = Server::find($sid);
            if (!$server) {
                return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
                ];
            }

            $query = (new EdictumList($server->dbData))->select('*');

            if ($search != '') {
                $query = $query->where('Title', 'LIKE', "%{$search}%")->orWhere('Text', 'LIKE', "%{$search}%");
            }

            if ($state != 'all') {
                $query = $query->where(
                    'EndDate',
                    $state == 'enable' ? '>' : '<',
                    Carbon::now()->toDateTimeString()
                );
            }

            $query = $query->orderBy('ID', 'ASC');

            $pager = new Paginator(url($this->request->getUri()), onclick: "announcement.list");
            $pager->pager($query->count(), $limit, $page, 2);

            $data = $query
              ->limit($pager->limit())
              ->offset($pager->offset())
              ->get()
              ?->toArray();

            foreach ($data as &$item) {
                $item['active'] =  (strtotime($item['EndDate']) > strtotime(Carbon::now())) ? true : false;
            }
        } catch (\Exception $exception) {
            http_response_code(500);
            if ($_ENV['APP_DEBUG'] == 'true') {
                dd($exception->getMessage());
            }
            exit;
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

        $server = Server::find($post['sid'] ?? 0);
        if (!$server) {
            return [
            'state' => false,
            'message' => 'Servidor não encontrado.'
            ];
        }

        $params = $post;
        unset($params['sid']);

        if (in_array('', $params)) {
            return [
            'state' => false,
            'message' => 'Todos os campos são obrigatórios'
            ];
        }

        $params['BeginTime'] = $params['BeginDate'];
        $params['EndTime'] = $params['EndDate'];
        $params['IsExist'] = isset($post['IsExist']) ? 1 : 0;

        $model = new EdictumList($server->dbData);
        if (!$model->insert($params)) {
            return [
              'state' => false,
              'message' => 'Falha ao criar anuncio'
            ];
        }

        return [
          'state' => true,
          'message' => 'Anuncio criado com sucesso'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);

        $server = Server::find($post['sid'] ?? 0);
        if (!$server) {
            return [
            'state' => false,
            'message' => 'Servidor não encontrado.'
            ];
        }

        $params = $post;
        unset($params['sid']);

        if (in_array('', $params)) {
            return [
            'state' => false,
            'message' => 'Todos os campos são obrigatórios'
            ];
        }

        $params['BeginTime'] = $params['BeginDate'];
        $params['EndTime'] = $params['EndDate'];

        $model = (new EdictumList($server->dbData))->find($post['ID']);
        if (!$model->update($params)) {
            return [
            'state' => false,
            'message' => 'Falha ao atualizar anuncio'
            ];
        }

        return [
        'state' => true,
        'message' => 'Anuncio atualizado com sucesso'
        ];
    }

    public function delete()
    {
        $get = $this->request->get();

        $server = Server::find($get['sid'] ?? 0);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new EdictumList($server->dbData))->find($get['id']);
        if (!$model) {
            return [
              'state' => false,
              'message' => 'Anuncio não encontrado, atualize a página e tente novamente.'
            ];
        }

        if (!$model->delete()) {
            return [
              'state' => false,
              'message' => 'Anuncio não foi deletado.'
            ];
        }

          return [
            'state' => true,
            'message' => 'Anuncio deletado com sucesso.'
          ];
    }
}
