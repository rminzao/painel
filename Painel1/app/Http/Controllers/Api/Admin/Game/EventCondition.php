<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\EventGoods;
use App\Models\Events\EventInfo;
use App\Models\Server;
use Core\Routing\Request;

class EventCondition extends Api
{
    public function list(Request $request): array
    {
        try {
            $post = $request->get();
            $sid = $post['sid'] ?? null;
            $activeType = $post['activityType'] ?? '';

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor informado, não foi encontrado.'
                ];
            }

            $model = (new EventInfo())->setTable($server->dbData . '.dbo.Event_Reward_Info');
            $query = $model->select('*');

            if ($activeType != '') {
                $query = $query->where('ActivityType', $activeType);
            }

            $query = $query->orderBy('ActivityType', 'ASC');
            $events = $query->get()->toArray();
            $eventList = array_map(function ($events) use ($server) {
                return $events;
            }, $events);

            return [
                'state' => true,
                'data' => $eventList ?? []
            ];
        } catch (\Exception $e) {
            return [
                'state' => false,
                'message' => 'Erro ao listar eventos: ' . $e->getMessage()
            ];
        }
    }

    public function create(Request $request): array
    {
        try {
            $post = $request->post(false);
            $sid = $post['sid'] ?? null;
            $activityType = $post['activityType'] ?? null;
            $condition = $post['condition'] ?? 0;

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor não encontrado.'
                ];
            }

            $model = (new EventInfo())->setTable($server->dbData . '.dbo.Event_Reward_Info');
            $model->ActivityType = $activityType;
            $model->SubActivityType = $this->getAvailableId($server, $activityType);
            $model->Condition = $condition;

            if (!$model->save()) {
                return [
                    'state' => false,
                    'message' => 'Falha ao criar condição.'
                ];
            }

            return [
                'state' => true,
                'message' => 'Condição criada com sucesso.',
                'data' => [
                    'id' => $model->SubActivityType
                ]
            ];
        } catch (\Exception $e) {
            return [
                'state' => false,
                'message' => 'Erro ao criar condição: ' . $e->getMessage()
            ];
        }
    }

    public function update(Request $request): array
    {
        try {
            $post = $request->post(false);
            $sid = $post['sid'] ?? null;
            $activityType = $post['activityType'] ?? null;
            $subActivityType = $post['subActivityType'] ?? null;
            $condition = $post['condition'] ?? 0;

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor não encontrado.'
                ];
            }

            $model = (new EventInfo())->setTable($server->dbData . '.dbo.Event_Reward_Info');
            $event = $model->where('ActivityType', $activityType)->where('SubActivityType', $subActivityType);

            if (!$event->exists()) {
                return [
                    'state' => false,
                    'message' => 'Condição não encontrada.'
                ];
            }

            if (!$event->update(['Condition' => $condition])) {
                return [
                    'state' => false,
                    'message' => 'Falha ao atualizar condição.'
                ];
            }

            return [
                'state' => true,
                'message' => 'Condição atualizada com sucesso.'
            ];
        } catch (\Exception $e) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar condição: ' . $e->getMessage()
            ];
        }
    }

    public function delete(Request $request): array
    {
        try {
            $post = $request->get();
            $sid = $post['sid'] ?? null;
            $activityType = $post['activityType'] ?? null;
            $subActivityType = $post['subActivityType'] ?? null;

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor não encontrado.'
                ];
            }

            $model = (new EventInfo())->setTable($server->dbData . '.dbo.Event_Reward_Info');
            $event = $model->where('ActivityType', $activityType)->where('SubActivityType', $subActivityType);

            if (!$event->exists()) {
                return [
                    'state' => false,
                    'message' => 'Condição não encontrada.'
                ];
            }

            if (!$event->delete()) {
                return [
                    'state' => false,
                    'message' => 'Falha ao deletar condição.'
                ];
            }

            $modelRewards = (new EventGoods())->setTable($server->dbData . '.dbo.Event_Reward_Goods');
            $rewards = $modelRewards->where('ActivityType', $activityType)->where('SubActivityType', $subActivityType);
            $rewards->delete();

            return [
                'state' => true,
                'message' => 'Condição deletada com sucesso.'
            ];
        } catch (\Exception $e) {
            return [
                'state' => false,
                'message' => 'Erro ao deletar condição: ' . $e->getMessage()
            ];
        }
    }

    protected function getAvailableId(Server $server, int $activityType): int
    {
        try {
            $model = (new EventInfo())->setTable($server->dbData . '.dbo.Event_Reward_Info');
            $query = $model->where('ActivityType', $activityType)->select('SubActivityType')->orderBy('SubActivityType', 'DESC');
            $lastId = $query->first();
            $lastId = $lastId ? $lastId->SubActivityType : 0;
            return $lastId + 1;
        } catch (\Exception $e) {
            // Se ocorrer um erro aqui, você pode tratar isso como quiser.
            return 1; // Ou outra lógica de fallback.
        }
    }
}
