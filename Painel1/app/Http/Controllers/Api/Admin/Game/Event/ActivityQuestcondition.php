<?php

namespace App\Http\Controllers\Api\Admin\Game\Event;

use App\Http\Controllers\Api\Api;
use App\Models\Events\ActivityQuest;
use App\Models\Server;

class ActivityQuestcondition extends Api
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
          ->conditions($id)
          ->get()
          ?->toArray();

        return [
            'state' => true,
            'data' => $data ?? [],
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);

        $sid = intval($post['sid'] ?? null);
        $questID = $post['QuestID'] ?? null;

        if (in_array('', $post)) {
            return [
            'state' => false,
            'message' => 'Preencha todos os campos.',
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivityQuest($server->dbData))->conditions();

        if (
            $model->where([
            ['QuestID', $questID],
            ['CondictionID', $post['CondictionID']],
            ])->first()
        ) {
            return [
              'state' => false,
              'message' => 'Ja existe uma condição de id <span class="text-danger">' .
              $post["CondictionID"] . '</span> para esta missão.'
            ];
        }

        unset($post['sid']);
        if (!$model->insert($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao adicionar uma nova condição.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Condição adicionada com sucesso.'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);
        $sid = $post['sid'] ?? null;
        $QuestID = $post['QuestID'] ?? null;
        $CondictionID = $post['CondictionID'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
              'state' => false,
              'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivityQuest($server->dbData))->conditions();

        $condition = $model->where([
          ['QuestID', $QuestID],
          ['CondictionID', $CondictionID],
        ]);

        if (!$condition->first()) {
            return [
              'state' => false,
              'message' => 'Essa condição não existe mais, atualize a página e tente novamente.'
            ];
        }

        unset($post['sid']);
        unset($post['QuestID']);
        if (!$condition->update($post)) {
            return [
              'state' => false,
              'message' => 'Ocorreu um erro ao atualizar essa condição.'
            ];
        }

        return [
          'state' => true,
          'message' => 'Condição atualizada com sucesso.'
        ];
    }

    public function delete()
    {
        $get = $this->request->get();
        $sid = $get['sid'] ?? null;
        $QuestID = $get['QuestID'] ?? null;
        $CondictionID = $get['CondictionID'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ActivityQuest($server->dbData))->conditions();

        $query = $CondictionID != 0 ? $model->where([
          ['QuestID', $QuestID],
          ['CondictionID', $CondictionID],
        ]) : $model->where('QuestID', $QuestID);

        if (!$query->delete()) {
            return [
              'state' => false,
              'message' => 'Falha ao apagar condição.'
            ];
        }

        return [
          'state' => true,
          'message' => $CondictionID != 0 ?
            'Condição apagada com sucesso.' :
            'Todos as condições foram apagadas com sucesso.'
        ];
    }
}
