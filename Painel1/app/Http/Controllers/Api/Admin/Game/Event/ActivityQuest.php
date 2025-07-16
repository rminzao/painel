<?php

namespace App\Http\Controllers\Api\Admin\Game\Event;

use App\Http\Controllers\Api\Api;
use App\Models\Events\ActivityQuest as EventsActivityQuest;
use App\Models\Server;
use Core\Database;
use Core\View\Paginator;
use GuzzleHttp\Client;

class ActivityQuest extends Api
{
    public function list(): array
    {
        try {
            $get = $this->request->get();

            $page = $get['page'] ?? 1;
            $sid = $get['sid'] ?? null;
            $limit = $get['limit'] ?? 10;
            $search = $get['search'] ?? '';
            $onclick = $get['onclick'] ?? 'activity.list';

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor informado, não foi encontrado.'
                ];
            }

            // 🔧 Adiciona conexão dinâmica
            Database::addConnection($server->dbData, [
                'driver' => $_ENV['DB_CONNECTION'],
                'host' => $_ENV['DB_HOST'],
                'database' => $server->dbData,
                'username' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ]);

            $model = new EventsActivityQuest($server->dbData);
            $query = $model->select('*');

            if ($search !== '') {
                $query = filter_var($search, FILTER_VALIDATE_INT)
                    ? $query->where('ID', $search)
                    : $query->where(function ($q) use ($search) {
                        $q->where('Title', 'LIKE', "%{$search}%")
                          ->orWhere('Detail', 'LIKE', "%{$search}%");
                    });
            }

            $query->orderBy('ID', 'ASC');

            $pager = new Paginator(url($this->request->getUri()), onclick: $onclick);
            $pager->pager($query->count(), $limit, $page, 1);

            $data = $query
                ->limit($pager->limit())
                ->offset($pager->offset())
                ->get()
                ?->toArray();

            return [
                'state' => true,
                'data' => $data ?? [],
                'paginator' => [
                    'total' => $pager->pages(),
                    'current' => $pager->page(),
                    'rendered' => $pager->render()
                ]
            ];
        } catch (\Throwable $e) {
            \Log::error('[ActivityQuest] Erro no list(): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'state' => false,
                'message' => 'Erro interno ao listar missões.',
                'details' => $e->getMessage()
            ];
        }
    }

    public function create()
    {
        $post = $this->request->post(false);
        $sid = $post['sid'] ?? null;

        $check = $post;
        unset($check['Objective']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Todos os campos são obrigatórios.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        // 🔧 Conexão dinâmica
        Database::addConnection($server->dbData, [
            'driver' => $_ENV['DB_CONNECTION'],
            'host' => $_ENV['DB_HOST'],
            'database' => $server->dbData,
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $model = new EventsActivityQuest($server->dbData);
        $post['ID'] = $model->max('ID') + 1;
        unset($post['sid']);

        if (!$model->insert($post)) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro ao criar a missão.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Missão criada com sucesso.'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);
        $sid = intval($post['sid'] ?? null);
        $id = $post['ID'];

        $params = $post;
        unset($params['sid'], $params['Objective']);

        if (in_array('', $params)) {
            return [
                'state' => false,
                'message' => 'Todos os campos são obrigatórios.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        // 🔧 Conexão dinâmica
        Database::addConnection($server->dbData, [
            'driver' => $_ENV['DB_CONNECTION'],
            'host' => $_ENV['DB_HOST'],
            'database' => $server->dbData,
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $event = (new EventsActivityQuest($server->dbData))->find($id);
        if (!$event->update($params)) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro ao atualizar a missão.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Missão atualizada com sucesso.'
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

        // 🔧 Conexão dinâmica
        Database::addConnection($server->dbData, [
            'driver' => $_ENV['DB_CONNECTION'],
            'host' => $_ENV['DB_HOST'],
            'database' => $server->dbData,
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $model = new EventsActivityQuest($server->dbData);

        if (!$model->find($id)->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao excluir missão.'
            ];
        }

        $model->conditions($id)->delete();
        $model->rewards($id)->delete();

        return [
            'state' => true,
            'message' => 'Missão excluída com sucesso.'
        ];
    }

    public function updateOnGame()
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

        $client = new Client();
        try {
            $res = $client->request('GET', $server->quest . '/ActivityQuestList.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'success')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar missões de evento, verifique se o servidor de destino está configurado corretamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'As missões de evento foram atualizadas com sucesso.'
        ];
    }
}
