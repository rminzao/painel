<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Server as ModelsServer;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;

class Server extends Api
{
    public function list(Request $request)
    {
        $get = $request->get();

        $page = $get['page'] ?? 1;
        $limit = $get['limit'] ?? 10;
        $search = $get['search'] ?? '';
        $status = $get['status'] ?? 'all';
        $onclick = $get['onclick'] ?? 'server.list';

        $model = (new ModelsServer());
        $query = $model->select('*');

        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
              $query->where('id', $search) :
              $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($status != 'all') {
            $query = $query->where('active', $status == 'true' ? 1 : 0);
        }

        $query = $query->orderBy('id', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 2);

        $servers = $query
          ->limit($pager->limit())
          ->offset($pager->offset())
          ->get()
          ?->toArray();

        $data = [];
        foreach ($servers as $server) {
            try {
                $server['online'] = (new Character())
                  ->setTable($server['dbUser'] . '.dbo.Sys_Users_Detail')
                  ->where('State', '!=', 0)
                  ->count();
            } catch (\Throwable $th) {
                $server['online'] = 0;
            }

            if ($server['settings'] != null) {
                $server['settings'] = json_decode(unserialize($server['settings']), true);
            }

            $data[] = $server;
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

    public function create(Request $request)
    {
        $post = $request->post(false);

        $modelServer = new ModelsServer();

        $params = $post;
        $params['active'] = $post['active'] ?? 0;
        $params['visible'] = $post['visible'] ?? 0;
        $check = $params;
        unset($check['description']);
        unset($check['setting_areaid']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos obrigatórios'
            ];
        }

        $settings = [
            'lang'          => $params['lang'] ?? 'spain',
            'flash_quality' => $params['setting_flash_quality'] ?? 'high',
            'areaid'        => $params['setting_areaid'] ?? 1001,
            'navbar'        => isset($params['setting_navbar']) ? 1 : 0,
            'suit'          => isset($params['setting_suit']) ? 1 : 0,
            'pets_eat'      => isset($params['setting_pets_eat']) ? 1 : 0,
            'batismo'      => isset($params['setting_batismo']) ? 1 : 0,
            'fugura'      => isset($params['setting_fugura']) ? 1 : 0,
            'passe'      => isset($params['setting_passe']) ? 1 : 0,
            'fazenda'      => isset($params['setting_fazenda']) ? 1 : 0,
            'manual'      => isset($params['setting_manual']) ? 1 : 0,
            'cabine'      => isset($params['setting_cabine']) ? 1 : 0,
            'templo'      => isset($params['setting_templo']) ? 1 : 0,
            'totem'         => isset($params['setting_totem']) ? 1 : 0,
            'latent_energy' => isset($params['setting_latent_energy']) ? 1 : 0,
            'advance'       => isset($params['setting_advance']) ? 1 : 0,
            'gemstone'      => isset($params['setting_gemstone']) ? 1 : 0,
            'md5'           => isset($params['setting_md5']) ? 1 : 0,
        ];

        unset($params['lang']);
        unset($params['setting_navbar']);
        unset($params['setting_flash_quality']);
        unset($params['setting_suit']);
        unset($params['setting_pets_eat']);
        unset($params['setting_batismo']);
        unset($params['setting_templo']);
        unset($params['setting_fugura']);
        unset($params['setting_passe']);
        unset($params['setting_fazenda']);
        unset($params['setting_manual']);
        unset($params['setting_cabine']);
        unset($params['setting_totem']);
        unset($params['setting_latent_energy']);
        unset($params['setting_advance']);
        unset($params['setting_gemstone']);
        unset($params['setting_md5']);
        unset($params['setting_areaid']);
        unset($params['description']);

        $params['settings'] = serialize(json_encode($settings));

        if (!$modelServer->insert($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao adicionar o servidor'
            ];
        }

        return [
            'state' => true,
            'message' => 'Servidor adicionado com sucesso'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);

        $model = (new ModelsServer())->find($post['id']);
        if (!$model) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        $params = $post;
        $params['active'] = $params['active'] ?? 0;
        $params['visible'] = $params['visible'] ?? 0;
        $check = $params;
        unset($check['description']);
        unset($check['setting_areaid']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos obrigatórios'
            ];
        }

        $settings = [
            'lang'          => $params['lang'] ?? 'spain',
            'flash_quality' => $params['setting_flash_quality'] ?? 'high',
            'areaid'        => $params['setting_areaid'] ?? 1001,
            'navbar'        => isset($params['setting_navbar']) ? 1 : 0,
            'suit'          => isset($params['setting_suit']) ? 1 : 0,
            'pets_eat'      => isset($params['setting_pets_eat']) ? 1 : 0,
            'batismo'      => isset($params['setting_batismo']) ? 1 : 0,
            'fugura'      => isset($params['setting_fugura']) ? 1 : 0,
            'passe'      => isset($params['setting_passe']) ? 1 : 0,
            'fazenda'      => isset($params['setting_fazenda']) ? 1 : 0,
            'manual'      => isset($params['setting_manual']) ? 1 : 0,
            'cabine'      => isset($params['setting_cabine']) ? 1 : 0,
            'templo'      => isset($params['setting_templo']) ? 1 : 0,
            'totem'         => isset($params['setting_totem']) ? 1 : 0,
            'latent_energy' => isset($params['setting_latent_energy']) ? 1 : 0,
            'advance'       => isset($params['setting_advance']) ? 1 : 0,
            'gemstone'      => isset($params['setting_gemstone']) ? 1 : 0,
            'md5'           => isset($params['setting_md5']) ? 1 : 0,
        ];

        unset($params['id']);
        unset($params['lang']);
        unset($params['setting_navbar']);
        unset($params['setting_flash_quality']);
        unset($params['setting_suit']);
        unset($params['setting_pets_eat']);
        unset($params['setting_batismo']);
        unset($params['setting_fugura']);
        unset($params['setting_passe']);
        unset($params['setting_fazenda']);
        unset($params['setting_manual']);
        unset($params['setting_cabine']);
        unset($params['setting_templo']);
        unset($params['setting_totem']);
        unset($params['setting_latent_energy']);
        unset($params['setting_advance']);
        unset($params['setting_gemstone']);
        unset($params['setting_md5']);
        unset($params['setting_areaid']);

        $params['settings'] = serialize(json_encode($settings));

        if (!$model->update($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar o servidor'
            ];
        }

        return [
            'state' => true,
            'message' => 'Servidor atualizado com sucesso'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();

        $model = (new ModelsServer())->find($post['id']);
        if (!$model) {
            return [
                'state' => false,
                'message' => 'Server not found'
            ];
        }

        if (!$model->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao excluir o servidor'
            ];
        }

        return [
            'state' => true,
            'message' => 'Servidor excluído com sucesso'
        ];
    }

    public function setSendMessage(Request $request)
    {
        $postVars = $request->post(false);
        $id = $postVars['sid'] ?? '';
        $message = $postVars['content'] ?? '';

        if (!$id or !$message) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos.'
            ];
        }

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return [
                'state' => false,
                'message' => 'O servidor selecionado é inválido, atualize a página e tente novamente.'
            ];
        }

        $server = ModelsServer::find($id);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'O servidor selecionado não foi encontrado, atualize a página e tente novamente.'
            ];
        }

        if ($server->wsdl == '') {
            return [
                'state' => false,
                'message' => 'O servidor <b>' . $server->name . '</b> não possui um dominio de wsdl configurado,
                      acesse a página de servidores e configure um.'
            ];
        }

        if (!$server->active) {
            return [
                'state' => false,
                'message' => 'O servidor <b>' . $server->name . '</b> está desativado.'
            ];
        }

        $wsdl = new Wsdl($server->wsdl);
        $wsdl->method = 'SystemNotice';
        $wsdl->paramters = [
            'msg' => (string) trim($message)
        ];

        if (!$wsdl->send()) {
            return [
                'state' => false,
                'message' => 'Falha ao enviar mensagem ao servidor
                      <b>' . $server->name . '</b>, servidor desligado ou <b>wsdl</b> incorreto,
                      verifique se os dados do servidor na
                      <a href="' . url('admin/server/list') . '" target="_blank">lista de servidores</a>
                      estão corretos e tente novamente.'
            ];
        }

        log_system(
            $this->user->id,
            'Enviou uma mensagem contendo [<b>' . $message . '</b>] ao servidor de id (' . $server->id . ').',
            $request->getUri(),
            'admin.server.message'
        );

        return [
            'state' => true,
            'message' => 'Mensagem enviada com sucesso ao servidor <b>' . $server->name . '</b>.'
        ];
    }

    public function getUsers(Request $request, $server)
    {
        $id = filter_var($server, FILTER_VALIDATE_INT);
        if (!$id) {
            return [
                'state' => false,
                'message' => 'O servidor selecionado é inválido, atualize a página e tente novamente.'
            ];
        }

        $server = ModelsServer::find($id);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'O servidor selecionado é inválido, atualize a página e tente novamente.'
            ];
        }

        //change db to current server on foreach
        $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');

        return [
            'state' => true,
            'users' => $model->get()?->toArray()
        ];
    }
}
