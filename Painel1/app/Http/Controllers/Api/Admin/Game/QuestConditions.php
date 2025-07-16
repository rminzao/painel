<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Quest;
use App\Models\QuestCondiction;
use App\Models\Server;
use Core\Routing\Request;

class QuestConditions extends Api
{
    public function list()
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

        $conditions = (new Quest($server->dbData))
          ->conditions($id)
          ->get()
          ?->toArray();

        return [
            'state' => true,
            'data' => $conditions
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'];
        $qid = $post['QuestID'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        if (!$qid) {
            return [
                'state' => false,
                'message' => 'Nenhuma missão foi selecionada.'
            ];
        }

        $model = (new Quest($server->dbData))->conditions();
        $validConditionID = $this->validCondition($model, $qid, $server?->version ?? 0);

        if ($validConditionID == null) {
            return [
                'state' => false,
                'message' => 'Não é possível adicionar uma nova condição o limite é de [<b>4</b>]'
            ];
        }

        $params = $post;
        unset($params['sid']);

        $params['QuestID'] = $qid;
        $params['isOpitional'] = isset($post['isOpitional']) ? 1 : 0;
        $params['CondictionID'] = $validConditionID;

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao adicionar condição.'
            ];
        }

        log_system(
            $this->user->id,
            "Adicionou condição [<b></b>] na missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Condição criada com sucesso.'
        ];
    }

    public function update(): array
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $qid = filter_var($post['QuestID'], FILTER_VALIDATE_INT);
        $conditionID = filter_var($post['CondictionID'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $condition = (new Quest($server->dbData))
          ->conditions()
          ->where([
            ['QuestID', $qid],
            ['CondictionID', $conditionID],
          ]);

        if (!$condition->first()) {
            return [
                'state' => false,
                'message' => 'Condição não encontrada, atualize a página e tente novamente.'
            ];
        }

        $params = $post;
        $params['isOpitional'] = isset($post['isOpitional']) ? 1 : 0;
        unset($params['sid']);
        unset($params['QuestID']);
        unset($params['CondictionID']);

        if (!$condition->update($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar condição.'
            ];
        }

        log_system(
            $this->user->id,
            "Editou condição [<b>{$post['CondictionTitle']}</b>] da missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Condição atualizada com sucesso.'
        ];
    }

    public function delete(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $qid = filter_var($post['QuestID'], FILTER_VALIDATE_INT);
        $conditionID = filter_var($post['CondictionID'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $whereDelete = ($conditionID != 0) ? [
            ['QuestID', $qid],
            ['CondictionID', $conditionID]
        ] : [['QuestID', $qid]];


        $quest = (new Quest($server->dbData))->conditions()->where($whereDelete);
        if (!$quest->count()) {
            return [
                'state' => false,
                'message' => 'Condição não encontrada.'
            ];
        }

        if (!$quest->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao deletar condição'
            ];
        }

        log_system(
            $this->user->id,
            "Removeu condição [<b>{$conditionID}</b>] da missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => $conditionID != 0 ? 'Condição removida com sucesso' : 'Todas as condições foram removidas com sucesso.'
        ];
    }

    protected function validCondition($questCondition, $questID, int $version): ?int
    {
        $ids = ($version < 5000) ? [1, 2, 3, 4] : [1, 2, 3, 4, 5, 6, 7, 8];
        $conditions = $questCondition->where('QuestID', $questID)->get();
        foreach ($conditions as $condition) {
            if (in_array($condition->CondictionID, $ids)) {
                unset($ids[($condition->CondictionID - 1)]);
            }
        }

        return array_values($ids)[0] ?? null;
    }
}
