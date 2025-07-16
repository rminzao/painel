<?php

namespace App\Http\Controllers\Api\Admin\Game;

use Core\Utils\GameTypes\eReloadType;
use App\Http\Controllers\Api\Api;
use App\Models\Quest as ModelsQuest;
use App\Models\QuestCondiction;
use App\Models\QuestGoods;
use App\Models\Server;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Quest extends Api
{
	public function list(): array
	{
		try {
			$post = $this->request->get();

			$page = filter_var($post['page'], FILTER_VALIDATE_INT);
			$sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
			$search = $post['search'] ?? '';
			$limit = $post['limit'] ?? 10;
			$type = $post['type'] ?? 'all';
			$onclick = $post['onclick'] ?? 'quest.list';

			$server = Server::find($sid);
			if (!$server) {
				return [
					'state' => false,
					'message' => 'Servidor informado, não foi encontrado.'
				];
			}

			$model = (new ModelsQuest($server->dbData));
			$query = $model->select('*');

			if ($search != '') {
				$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); // Sanitizando
				$query = filter_var($search, FILTER_VALIDATE_INT) ?
					$query->where('ID', 'LIKE', "%{$search}%") :
					$query->where('Title', 'LIKE', "%{$search}%");
			}

			if ($type != 'all') {
				$query = $query->where('QuestID', $type);
			}

			$query = $query->orderBy('ID', 'ASC');

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
		} catch (\Throwable $th) {
			return [
				'state' => false,
				'message' => 'Erro ao listar missões: ' . $th->getMessage()
			];
		}
	}

    public function create(): array
    {
        $post = $this->request->post(false);

        $check = $post;
        unset($check['Detail'], $check['Objective']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Dados incompletos, verifique-os e tente novamente.'
            ];
        }

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        if (strtotime($post['StartDate']) > strtotime($post['EndDate'])) {
            return [
                'state' => false,
                'message' => 'A <b>data de início</b> não pode ser maior que a <b>data de término</b>.'
            ];
        }

        $quest = new ModelsQuest($server->dbData);

        $params = $post;
        $params['ID'] = $quest->max('id') + 1;
        $params['IsOther'] = 0;
        $params['RewardBuffID'] = 0;
        $params['RewardBuffDate'] = 0;
        $params['Rands'] = 0.00;
        $params['RandDouble'] = 1;
        $params['MapID'] = 0;
        $params['AutoEquip'] = 0;
        $params['StarLev'] = 0;
        $params['NotMustCount'] = 0;
        $params['TimeMode'] = isset($post['TimeMode']) ? 1 : 0;
        $params['StartDate'] = Carbon::parse($post['StartDate'])->format('Y-m-d 00:00:00.000');
        $params['EndDate'] = Carbon::parse($post['EndDate'])->format('Y-m-d 23:59:59.000');
        unset($params['sid']);

        if (!$quest->insert($params)) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro ao criar missão.'
            ];
        }

        return [
            'state' => true,
            'message' => "Missão <span class=\"text-primary\">{$post['Title']}</span> criada com sucesso, não se esqueça das condições e recompensas."
        ];
    }

    public function update(): array
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        if (!$quest = (new ModelsQuest($server->dbData))->find($post['ID'])) {
            return [
                'state' => false,
                'message' => 'Quest Não Encontrada.'
            ];
        }

        $params = $post;
        unset($params['sid'], $params['ID']);
        $params['TimeMode'] = isset($post['TimeMode']) ? 1 : 0;
        $params['StartDate'] = Carbon::parse($post['StartDate'])->format('Y-m-d 00:00:00.000');
        $params['EndDate'] = Carbon::parse($post['EndDate'])->format('Y-m-d 23:59:59.000');

        if (!$quest->update($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao Alterar a Missão'
            ];
        }

        log_system(
            $this->user->id,
            "Alterou a Missão [<b>{$quest['Title']}</b>]({$quest['ID']})",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Missão Alterada Com Sucesso'
        ];
    }

    public function delete(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $id = filter_var($post['id'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = new ModelsQuest($server->dbData);
        $quest = $model->find($id);
        if (!$quest) {
            return [
                'state' => false,
                'message' => 'Quest Não Encontrada.'
            ];
        }

        $title = $quest->Title;

        if (!$quest->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao apagar missão.'
            ];
        }

        $conditions = $model->conditions($id);
        $conditions->delete();

        $rewards = $model->rewards($id);
        $rewards->delete();

        log_system(
            $this->user->id,
            "Removeu a Missão [<b>{$title}</b>]({$id})",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => "Missão <span class=\"text-primary\">{$title}</span> removida com Sucesso"
        ];
    }

    public function duplicate(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $qid = filter_var($post['id'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $modelQuest = (new ModelsQuest())->setTable($server->dbData . '.dbo.Quest');
        $newID = $modelQuest->max('id') + 1;

        $quest = $modelQuest->where('ID', $qid)->first()->replicate()->toArray();
        $quest['ID'] =  $newID;
        $quest['Title'] =  $quest['Title'] . ' #duplicado';

        $modelConditions = (new QuestCondiction())->setTable($server->dbData . '.dbo.Quest_Condiction');
        $conditionList = $modelConditions->where('QuestID', $qid)->get()?->toArray();
        $conditions = array_map(function ($item) use ($newID) {
            $item['QuestID'] = $newID;
            return $item;
        }, $conditionList);

        $modelGoods = (new QuestGoods())->setTable($server->dbData . '.dbo.Quest_Goods');
        $rewardList = $modelGoods->where('QuestID', $qid)->get()?->toArray();
        $rewards = array_map(function ($item) use ($newID) {
            $item['QuestID'] = $newID;
            return $item;
        }, $rewardList);

        $modelQuest->insert($quest);
        $modelConditions->insert($conditions);
        $modelGoods->insert($rewards);

        return [
            'state' => true,
            'message' => 'Missão Duplicada Com Sucesso.'
        ];
    }

    public function onGameUpdate()
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
            // Faz a requisição para o servidor de quest
		$client->request('GET', $server->quest . '/build/Createallxml.ashx?key=' . env('APP_REQUEST_KEY'));
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        // Sem verificação do conteúdo da resposta
        // Envia o comando WSDL
        (new Wsdl())->reload(Wsdl::QUEST, $server);

        return [
            'state' => true,
            'message' => 'Lista de missões atualizada com sucesso.'
        ];
    }
}
