<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\GmActiveCondition;
use App\Models\Events\GmActiveReward;
use App\Models\Events\GmActivity as EventsGmActivity;
use App\Models\Game\User\GmGiftData;
use App\Models\Game\User\GmStatusData;
use App\Models\Server;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class GmActivity extends Api
{
    // Converte datas para o formato padrão do banco
    private function formatDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    // Lista todos os eventos GM com filtros opcionais de busca, tipo e status (ativo, futuro, encerrado)
    public function list(Request $request): array
    {
        $post = $request->get();
        $page = $post['page'] ?? 1;
        $sid = $post['sid'] ?? null;
        $search = $post['search'] ?? null;
        $type = $post['type'] ?? 'all';
        $filter = $post['filter'] ?? 'all';
        $limit = $post['limit'] ?? 10;

        // Verifica o servidor
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity');
        $query = $model->select('*');

        // Filtro por nome
        if ($search != null) {
            $query = $query->where('activityName', 'LIKE', "%{$search}%");
        }

        // Filtro por tipo de atividade
        if ($type != 'all') {
            $query = $query->where('activityType', $type);
        }

        // Filtro por status de tempo (ativo, encerrado, futuro)
        if ($filter !== 'all') {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            switch ($filter) {
                case 'enable':
                    $query = $query->where('beginTime', '<=', $now)->where('endTime', '>=', $now);
                    break;
                case 'disable':
                    $query = $query->where('endTime', '<', $now);
                    break;
                case 'future':
                    $query = $query->where('beginTime', '>', $now);
                    break;
            }
        }

        $query = $query->orderBy('activityName', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: "events.list");
        $pager->pager($query->count(), $limit, $page, 1);

        $events = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();

        foreach ($events as &$event) {
            $event['state'] = $this->getEventStateByDate($event['beginTime'], $event['endTime']);
        }

        return [
            'state' => true,
            'data' => $events ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    // Cria um novo evento GM
    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return ['state' => false, 'message' => 'Servidor informado, não foi encontrado.'];
        }

        $fields = $post;
        unset($fields['desc'], $fields['rewardDesc']);
        if (in_array('', $fields)) {
            return ['state' => false, 'message' => 'Preencha os campos obrigatórios.'];
        }

        if (strtotime($post['beginTime']) > strtotime($post['endTime'])) {
            return ['state' => false, 'message' => 'Data de início maior que a data de término.'];
        }

        $model = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity');

        unset($post['sid']);
        $params = $post;
        $params['activityId'] = uuid();
        $params['beginTime'] = $this->formatDate($post['beginTime']);
        $params['beginShowTime'] = $params['beginTime'];
        $params['endTime'] = $this->formatDate($post['endTime']);
        $params['endShowTime'] = $params['endTime'];
        $params['icon'] = 1;
        $params['isContinue'] = 0;
        $params['status'] = 1;

        if (!$model->insert($params)) {
            return ['state' => false, 'message' => 'Falha ao criar evento.'];
        }

        return ['state' => true, 'message' => 'Evento criado com sucesso.'];
    }

    // Atualiza um evento existente
    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return ['state' => false, 'message' => 'Servidor informado, não foi encontrado.'];
        }

        $model = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity');
        $activity = $model->find($post['activityId']);
        unset($post['sid']);

        $params = $post;
        $params['beginTime'] = $this->formatDate($post['beginTime']);
        $params['beginShowTime'] = $params['beginTime'];
        $params['endTime'] = $this->formatDate($post['endTime']);
        $params['endShowTime'] = $params['endTime'];
        $params['icon'] = 1;
        $params['isContinue'] = 0;
        $params['status'] = 1;

        if (!$activity->update($params)) {
            return ['state' => false, 'message' => 'Falha ao atualizar evento.'];
        }

        return ['state' => true, 'message' => 'Evento atualizado com sucesso.'];
    }

    // Deleta um evento e todos os dados associados (giftbags, condições, recompensas)
    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $activityId = $post['id'] ?? null;

        $server = Server::find($sid);
        if (!$server) return ['state' => false, 'message' => 'Servidor informado, não foi encontrado.'];

        $model = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity');
        $query = $model->find($activityId);

        if (!$query || !$query->delete()) {
            return ['state' => false, 'message' => 'Falha ao deletar evento.'];
        }

        $modelGift = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Gift');
        $queryGift = $modelGift->where('activityId', $activityId);
        $giftIds = $queryGift->pluck('giftbagId')->toArray();
        $queryGift->delete();

        (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition')->whereIn('giftbagId', $giftIds)->delete();
        (new GmActiveReward())->setTable($server->dbData . '.dbo.GM_Active_Reward')->whereIn('giftId', $giftIds)->delete();

        return ['state' => true, 'message' => 'Evento deletado com sucesso.'];
    }

    // Duplicação de um evento, incluindo giftbags, condições e recompensas
    public function duplicate(Request $request)
    {
        $post = $request->get();
        $id = $post['id'] ?? null;
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) return ['state' => false, 'message' => 'Servidor informado, não foi encontrado.'];

        $modelActivity = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity');
        $original = $modelActivity->find($id);
        if (!$original) return ['state' => false, 'message' => 'Evento não encontrado.'];

        $activity = $original->replicate()->toArray();
        $activity['activityId'] = uuid();
        $activity['activityName'] .= ' #duplicated';
        $modelActivity->insert($activity);

        $modelGift = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Gift');
        $giftBags = $modelGift->where('activityId', $id)->get()?->toArray() ?? [];

        foreach ($giftBags as &$giftBag) {
            $oldId = $giftBag['giftbagId'];
            $giftBag['giftbagId'] = uuid();
            $giftBag['activityId'] = $activity['activityId'];

            $conds = (new GmActiveCondition())->setTable($server->dbData . '.dbo.GM_Active_Condition')->where('giftbagId', $oldId)->get()?->toArray() ?? [];
            foreach ($conds as &$c) { $c['giftbagId'] = $giftBag['giftbagId']; }
            (new GmActiveCondition())->insert($conds);

            $rewards = (new GmActiveReward())->setTable($server->dbData . '.dbo.GM_Active_Reward')->where('giftId', $oldId)->get()?->toArray() ?? [];
            foreach ($rewards as &$r) { unset($r['Id']); $r['giftId'] = $giftBag['giftbagId']; }
            (new GmActiveReward())->insert($rewards);
        }

        $modelGift->insert($giftBags);
        return ['state' => true, 'message' => 'Evento duplicado com sucesso.'];
    }

    // Reseta progresso ou recompensas de um evento
    public function reset(Request $request)
    {
        $post = $request->get();
        $id = $post['id'] ?? null;
        $sid = $post['sid'] ?? null;
        $progress = isset($post['progress']);
        $rewarded = isset($post['rewarded']);

        if (!$progress && !$rewarded) {
            return ['state' => false, 'message' => 'Selecione ao menos uma opção.'];
        }

        $server = Server::find($sid);
        if (!$server) return ['state' => false, 'message' => 'Servidor não encontrado.'];

        $event = (new EventsGmActivity())->setTable($server->dbData . '.dbo.GM_Activity')->find($id);
        if (!$event) return ['state' => false, 'message' => 'Evento não encontrado.'];

        if ($progress) {
            (new GmGiftData())->setTable($server->dbUser . '.dbo.Sys_Users_Gm_GiftData')->where('ActivityID', $id)->delete();
            (new GmStatusData())->setTable($server->dbUser . '.dbo.Sys_Users_Gm_StatusData')->where('ActivityID', $id)->delete();
            return ['state' => true, 'message' => "Evento <span class=\"text-primary\">{$event->activityName}</span> resetado com sucesso."];
        }

        if ($rewarded) {
            (new GmGiftData())->setTable($server->dbUser . '.dbo.Sys_Users_Gm_GiftData')->where('ActivityID', $id)->update(['Times' => 0]);
            return ['state' => true, 'message' => "Recompensas resetadas com sucesso para o evento <span class=\"text-primary fw-bolder\">{$event->activityName}</span>."];
        }
    }

    // Atualiza os arquivos XML e emuladores do servidor
    public function updateOnGame(Request $request)
    {
        $post = $request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) return ['state' => false, 'message' => 'Servidor informado, não foi encontrado.'];

        $client = new Client();
        try {
            $client->request('GET', $server->quest . '/build/Createallxml.ashx?key=' . env('APP_REQUEST_KEY'));
        } catch (\Throwable $th) {
            return ['state' => false, 'message' => 'Servidor inacessível.'];
        }

        (new Wsdl())->reload(Wsdl::ACTIVITY_WONDER, $server);

        return ['state' => true, 'message' => 'GmActivity atualizado com sucesso.'];
    }

    // Função auxiliar que retorna o estado de um evento com base nas datas
    protected function getEventStateByDate($beginTime, $endTime)
    {
        $now = time();
        if ($now < strtotime($beginTime)) return 'before';
        if ($now > strtotime($endTime)) return 'after';
        return 'active';
    }
}
