<?php

namespace App\Http\Controllers\Api\Admin\Game\Event;

use App\Http\Controllers\Api\Api;
use App\Models\Events\ActivityQuest;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\Events\ActivityQuestReward as Reward;
use Core\Database;

class ActivityQuestReward extends Api
{
    public function list()
    {
        $get = $this->request->get();
        $sid = intval($get['sid'] ?? null);
        $id = intval($get['id'] ?? null);

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = new ActivityQuest($server->dbData);

        $data = $model
          ->rewards($id)
          ->get()
          ?->toArray();

        foreach ($data as &$reward) {
            $itemData = (new ShopGoods($server->dbData))->find($reward['TemplateID']);
            $reward['Icon'] = $itemData->image();
            $reward = array_merge($reward, $itemData->detail());
        }

        return [
          'state' => true,
          'data' => $data ?? [],
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivityQuest($server->dbData))->rewards();

        unset($post['sid']);
        if (!$model->insert($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao criar recompensa.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Recompensa criada com sucesso.'
        ];
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
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivityQuest($server->dbData))->rewards();

        $reward = $model->find($id);
        if (!$reward) {
            return [
              'state' => false,
              'message' => 'Essa recompensa não existe mais, atualize a página e tente novamente.'
            ];
        }

        unset($post['sid']);
        if (!$reward->update($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao atualizar essa recompensa.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Recompensa atualizada com sucesso.'
        ];
    }

    public function delete()
    {
        try {
            $post = $this->request->get();
            $sid = $post['sid'] ?? null;
            $id = $post['id'] ?? null;

            if (!$sid || !$id) {
                return [
                    'state' => false,
                    'message' => 'ID do servidor ou recompensa não informado.'
                ];
            }

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor informado não encontrado.'
                ];
            }

            // ✅ Conexão dinâmica (IMPORTANTE)
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

            $model = new Reward($server->dbData);
            $reward = $model->find($id);

            if (!$reward) {
                return [
                    'state' => false,
                    'message' => 'Recompensa não encontrada.'
                ];
            }

            if (!$reward->delete()) {
                return [
                    'state' => false,
                    'message' => 'Erro ao excluir recompensa.'
                ];
            }

            return [
                'state' => true,
                'message' => 'Recompensa excluída com sucesso.'
            ];
        } catch (\Throwable $e) {
            \Log::error('Erro ao deletar recompensa: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'state' => false,
                'message' => 'Erro interno ao deletar recompensa.',
                'details' => $e->getMessage()
            ];
        }
    }
}
