<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\EventDetail;
use App\Models\Server;
use App\Models\ServerConfig as ModelsServerConfig;
use Core\Routing\Request;
use Core\Utils\GameTypes\eReloadType;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class ServerConfig extends Api
{
    /**
     * It's a function that returns an array of event data from a database
     *
     * @param Request request The request object.
     */
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $page = $post['page'] ?? 1;
        $sid = $post['sid'] ?? null;
        $search = $post['search'] ?? '';
        $limit = $post['limit'] ?? 10;
        $onclick = $post['onclick'] ?? 'serverConfig.list';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsServerConfig())->setTable($server->dbUser . '.dbo.Server_Config');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('ID', $search) :
                $query->where('Name', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('ID', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $data = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();

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

    /**
     * I'm trying to insert a new row into a table that is not in the default database.
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
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

        //check fields is empty
        if (empty($post['name']) || empty($post['value'])) {
            return [
                'state' => false,
                'message' => 'Campo nome e valor são obrigatórios.'
            ];
        }

        $model = (new ModelsServerConfig())->setTable($server->dbUser . '.dbo.Server_Config');

        //get valid id
        $id = $model->max('ID') + 1;

        if (
            !$model->insert([
            'ID' => $id,
            'Name' => $post['name'],
            'Value' => $post['value']
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Falha ao criar parâmetro.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Parâmetro criado com sucesso.'
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

        //check fields is empty
        if (empty($post['name']) || empty($post['value'])) {
            return [
                'state' => false,
                'message' => 'Campo nome e valor são obrigatórios.'
            ];
        }

        $model = (new ModelsServerConfig())->setTable($server->dbUser . '.dbo.Server_Config');

        if (
            !$model->where('ID', $post['id'])->update([
            'Name' => $post['name'],
            'Value' => $post['value']
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar parâmetro.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Parâmetro atualizado com sucesso.'
        ];
    }

    public function delete(Request $request)
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

        $model = (new ModelsServerConfig())->setTable($server->dbUser . '.dbo.Server_Config');

        $config = $model->find($id);
        if (!$config) {
            return [
                'state' => false,
                'message' => 'Parâmetro não encontrado.'
            ];
        }

        if (!$config->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao excluir parâmetro.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Parâmetro excluído com sucesso.'
        ];
    }

    /**
     * It sends a request to the server and checks if the response contains the word "success"
     *
     * @param Request request The request object.
     *
     * @return The server is returning a JSON object with the following structure:
     * <code>{
     *     "state": true,
     *     "message": "Lista de atividades atualizada com sucesso."
     * }
     * </code>
     */
    public function updateOnGame(Request $request)
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

        $client = new Client();
        try {
            $res = $client->request('GET', $server->quest . '/Build/ServerConfig.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'success') && !strpos(strtolower($res->getBody()), 'succeeded')) {
            return [
              'state' => false,
              'message' =>
                'Erro ao atualizar ServerConfig.xml, verifique se o servidor de destino esta configurado corretamente.'
            ];
        }

        //send wsdl reload
        (new Wsdl())->reload(Wsdl::SERVER_CONFIG, $server);

        return [
            'state' => true,
            'message' => 'ServerConfig atualizada com sucesso.'
        ];
    }
}
