<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Quest;
use App\Models\QuestGoods as ModelsQuestGoods;
use App\Models\Server;
use App\Models\ShopGoods;
use stdClass;

class QuestGoods extends Api
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

        $data = (new Quest($server->dbData))
          ->rewards($id)
          ->get()
          ?->toArray();

        foreach ($data as &$reward) {
            if (!$itemData = (new ShopGoods($server->dbData))->find($reward['RewardItemID'])) {
                continue;
            }

            $reward['Icon'] = $itemData->image();
            $reward = array_merge($reward, $itemData->detail());
        }

        return [
            'state' => true,
            'data' => $data
        ];
    }

    public function create()
    {
        $post = $this->request->post(false);

        $sid = $post['sid'] ?? '';
        $qid = $post['QuestID'] ?? '';

        if (!$sid or !$qid) {
            return [
                'state' => false,
                'message' => 'ID do servidor e da missão é obrigatório.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $params = $post;

        if ($server->version == 4100) {
            $params['RewardItemCount'] = $params['RewardItemCount'] ?? 1;
        }

        if ($server->version >= 11000) {
            unset($params['RewardItemCount']);
        }

        if (($server->version >= 5500) && !isset($params['IsMultipleCount'])) {
            $params['RewardItemCount2'] = $params['RewardItemCount1'];
            $params['RewardItemCount3'] = $params['RewardItemCount1'];
            $params['RewardItemCount4'] = $params['RewardItemCount1'];
            $params['RewardItemCount5'] = $params['RewardItemCount1'];
            if ($server->version >= 6000) {
                $params['MagicAttack']      = $params['MagicAttack'] ?? 0;
                $params['MagicDefence']     = $params['MagicDefence'] ?? 0;
            }
        }

        $params['IsSelect']         = isset($params['IsSelect']) ? 1 : 0;
        $params['IsBind']           = isset($params['IsBind']) ? 1 : 0;
        $params['IsCount']          = isset($params['IsCount']) ? 1 : 0;
        $params['StrengthenLevel']  = $params['StrengthenLevel'] ?? 0;
        $params['AttackCompose']    = $params['AttackCompose'] ?? 0;
        $params['DefendCompose']    = $params['DefendCompose'] ?? 0;
        $params['AgilityCompose']   = $params['AgilityCompose'] ?? 0;
        $params['LuckCompose']      = $params['LuckCompose'] ?? 0;

        unset($params['IsMultipleCount']);
        unset($params['sid']);

        $reward = (new Quest($server->dbData))->rewards();
        if (!$reward->create($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao adicionar recompensa.'
            ];
        }

        log_system(
            $this->user->id,
            "Adicionou recompensa [<b>{$post['RewardItemID']}</b>] na missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Recompensa adicionada com sucesso.'
        ];
    }

    public function update()
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $qid = filter_var($post['QuestID'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server or !$qid) {
            return [
                'state' => false,
                'message' => 'Verifique os dados e tente novamente.'
            ];
        }

        $model = (new Quest($server->dbData))->rewards();
        $questGoods = $model->where([
            ['QuestID', '=', $qid],
            ['RewardItemID', '=', $post['RewardItemID']]
        ]);

        if (!$questGoods) {
            return [
                'state' => false,
                'message' => 'Quest Não Encontrada, atualize a página e tente novamente.'
            ];
        }

        $params = $post;

        if ($server->version == 4100) {
            $params['RewardItemCount'] = $params['RewardItemCount'] ?? 1;
        }

        if ($server->version >= 11000) {
            unset($params['RewardItemCount']);
        }

        if (($server->version >= 5500) && !isset($params['IsMultipleCount'])) {
            $params['RewardItemCount2'] = $params['RewardItemCount1'];
            $params['RewardItemCount3'] = $params['RewardItemCount1'];
            $params['RewardItemCount4'] = $params['RewardItemCount1'];
            $params['RewardItemCount5'] = $params['RewardItemCount1'];
            if ($server->version >= 6000) {
                $params['MagicAttack']      = $params['MagicAttack'] ?? 0;
                $params['MagicDefence']     = $params['MagicDefence'] ?? 0;
            }
        }

        $params['IsSelect']         = isset($params['IsSelect']) ? 1 : 0;
        $params['IsBind']           = isset($params['IsBind']) ? 1 : 0;
        $params['IsCount']          = isset($params['IsCount']) ? 1 : 0;
        $params['StrengthenLevel']  = $params['StrengthenLevel'] ?? 0;
        $params['AttackCompose']    = $params['AttackCompose'] ?? 0;
        $params['DefendCompose']    = $params['DefendCompose'] ?? 0;
        $params['AgilityCompose']   = $params['AgilityCompose'] ?? 0;
        $params['LuckCompose']      = $params['LuckCompose'] ?? 0;

        unset($params['IsMultipleCount']);
        unset($params['sid']);

        if (!$questGoods->update($params)) {
            return [
                'state' => false,
                'message' => 'Erro ao editar recompensa.'
            ];
        }

        log_system(
            $this->user->id,
            "Alterou a recompensa [<b>{$post['RewardItemID']}</b>] da missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Recompensa atualizada com sucesso.'
        ];
    }

    public function delete(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $qid = filter_var($post['QuestID'], FILTER_VALIDATE_INT);
        $rewardID = filter_var($post['RewardItemID'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $whereDelete = ($rewardID != 0) ? [
            ['QuestID', '=', $qid],
            ['RewardItemID', '=', $rewardID]
        ] : [['QuestID', '=', $qid]];


        $quest = (new Quest($server->dbData))->rewards()->where($whereDelete);
        if (!$quest) {
            return [
                'state' => false,
                'message' => 'Recompensa não encontrada.'
            ];
        }

        if (!$quest->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao deletar recompensa'
            ];
        }

        log_system(
            $this->user->id,
            "Deletou recompensa [<b>{$rewardID}</b>] da missão [<b>{$qid}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'Recompensa deletada com sucesso'
        ];
    }
}
