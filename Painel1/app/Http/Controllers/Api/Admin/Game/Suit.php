<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\SuitTemplate;
use Core\Routing\Request;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Suit extends Api
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

        $model = (new SuitTemplate())->setTable($server->dbData . '.dbo.SuitTemplateInfo');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('SuitId', $search) :
                $query->where('SuitName', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('SuitId', 'ASC');

        //pagination
        $pager = new Paginator(url($request->getUri()), onclick: "suit.list");
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $suits = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();

        return [
            'state' => true,
            'data' => $suits ?? [],
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

        $model = (new SuitTemplate())->setTable($server->dbData . '.dbo.SuitTemplateInfo');
        $model->SuitId = $model->max('SuitId') + 1;
        $model->SuitName = $post['SuitName'];
        $model->EqipCount1 = $post['EqipCount1'];
        $model->SkillDescribe1 = $post['SkillDescribe1'];
        $model->Skill1 = $post['Skill1'];
        $model->EqipCount2 = $post['EqipCount2'];
        $model->SkillDescribe2 = $post['SkillDescribe2'];
        $model->Skill2 = $post['Skill2'];
        $model->EqipCount3 = $post['EqipCount3'];
        $model->SkillDescribe3 = $post['SkillDescribe3'];
        $model->Skill3 = $post['Skill3'];
        $model->EqipCount4 = $post['EqipCount4'];
        $model->SkillDescribe4 = $post['SkillDescribe4'];
        $model->Skill4 = $post['Skill4'];
        $model->EqipCount5 = $post['EqipCount5'];
        $model->SkillDescribe5 = $post['SkillDescribe5'];
        $model->Skill5 = $post['Skill5'];

        if(!$model->save()){
            return [
                'state' => false,
                'message' => 'Falha ao criar conjunto.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Conjunto criado com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['SuitId'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new SuitTemplate())->setTable($server->dbData . '.dbo.SuitTemplateInfo');
        $model = $model->find($id);
        if (!$model) {
            return [
                'state' => false,
                'message' => 'Conjunto não encontrado.'
            ];
        }

        $model->SuitName = $post['SuitName'];
        $model->EqipCount1 = $post['EqipCount1'];
        $model->SkillDescribe1 = $post['SkillDescribe1'];
        $model->Skill1 = $post['Skill1'];
        $model->EqipCount2 = $post['EqipCount2'];
        $model->SkillDescribe2 = $post['SkillDescribe2'];
        $model->Skill2 = $post['Skill2'];
        $model->EqipCount3 = $post['EqipCount3'];
        $model->SkillDescribe3 = $post['SkillDescribe3'];
        $model->Skill3 = $post['Skill3'];
        $model->EqipCount4 = $post['EqipCount4'];
        $model->SkillDescribe4 = $post['SkillDescribe4'];
        $model->Skill4 = $post['Skill4'];
        $model->EqipCount5 = $post['EqipCount5'];
        $model->SkillDescribe5 = $post['SkillDescribe5'];
        $model->Skill5 = $post['Skill5'];

        if (!$model->save()) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar conjunto.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Conjunto atualizado com sucesso.'
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

        $model = (new SuitTemplate())->setTable($server->dbData . '.dbo.SuitTemplateInfo');
        if(!$model->find($id)->delete()){
            return [
                'state' => false,
                'message' => 'Falha ao deletar conjunto.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Conjunto deletado com sucesso.'
        ];
    }

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
            $res = $client->request('GET', $server->quest.'/SuitBuild.ashx');
            $client->request('GET', $server->quest.'/TemplateAllList.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'success')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar lista de conjuntos, verifique se o servidor de destino esta configurado corretamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Lista de conjuntos atualizada com sucesso.'
        ];
    }
}
