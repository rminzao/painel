<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\SuitPartSkillsAtributes;
use Core\Routing\Request;
use Core\View\Paginator;

class SuitSkills extends Api
{
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $page = $post['page'] ?? 1;
        $sid = $post['sid'] ?? null;
        $search = $post['search'] ?? '';
        $limit = $post['limit'] ?? 10;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartSkillsAtributes())->setTable($server->dbData . '.dbo.Suit_Part_Skills_Atributes');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = $query->where('SkilID', $search);
        }

        $query = $query->orderBy('SkilID', 'ASC');

        //pagination
        $pager = new Paginator(url($request->getUri()), onclick: "suitSkill.list");
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $data = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();

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
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartSkillsAtributes())->setTable($server->dbData . '.dbo.Suit_Part_Skills_Atributes');
        $lastId = $model->max('SkilID');

        $model->SkilID = $lastId + 1;
        $model->Attack = $post['Attack'] ?? 0;
        $model->Defence = $post['Defence'] ?? 0;
        $model->Agility = $post['Agility'] ?? 0;
        $model->Blood = $post['Blood'] ?? 0;
        $model->Armor = $post['Armor'] ?? 0;
        $model->Damage = $post['Damage'] ?? 0;
        $model->Luck = $post['Luck'] ?? 0;
        $model->MagickAttack = $post['MagickAttack'] ?? 0;
        $model->MagickDefence = $post['MagickDefence'] ?? 0;

        if(!$model->save()){
            return [
                'state' => false,
                'message' => 'Erro ao criar skill.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Skill criada com sucesso.'
        ];

    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['SkilID'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitPartSkillsAtributes())->setTable($server->dbData . '.dbo.Suit_Part_Skills_Atributes');

        $query = $model->find($id);
        if(!$query){
            return [
                'state' => false,
                'message' => 'Suit Part Skills not found.'
            ];
        }

        $query->Attack = $post['Attack'] ?? 0;
        $query->Defence = $post['Defence'] ?? 0;
        $query->Agility = $post['Agility'] ?? 0;
        $query->Blood = $post['Blood'] ?? 0;
        $query->Armor = $post['Armor'] ?? 0;
        $query->Damage = $post['Damage'] ?? 0;
        $query->Luck = $post['Luck'] ?? 0;
        $query->MagickAttack = $post['MagickAttack'] ?? 0;
        $query->MagickDefence = $post['MagickDefence'] ?? 0;

        if(!$query->save()){
            return [
                'state' => false,
                'message' => 'Suit Part Skills not updated.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Suit Part Skills updated.'
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

        $model = (new SuitPartSkillsAtributes())->setTable($server->dbData . '.dbo.Suit_Part_Skills_Atributes');
        $query = $model->find($id);
        if(!$query){
            return [
                'state' => false,
                'message' => 'Suit Part Skills not found.'
            ];
        }

        if(!$query->delete()){
            return [
                'state' => false,
                'message' => 'Suit Part Skills not deleted.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Suit Part Skills deleted.'
        ];
    }
}
