<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\Active;
use App\Models\Events\ActiveAward;
use App\Models\Server;
use Core\Routing\Request;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Activity extends Api
{
   /**
    * It's a function that returns an array of event data from a database
    *
    * @param Request request The request object.
    */
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

        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('ActiveID', 'LIKE', "%{$search}%") :
                $query->where('Title', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('ActiveID', 'ASC');

        //pagination
        $pager = new Paginator(url($request->getUri()), onclick: "events.list");
        $pager->pager($query->count(), $limit, $page, 1);

        //get item list
        $events = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();

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

    /**
     * It creates an event in the database
     *
     * @param Request request The request object.
     *
     * @return The return is a json object with the following structure:
     */
    public function create(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //validate data
        if (!isset($post['Title']) || !isset($post['StartDate']) || !isset($post['EndDate'])) {
            return [
                'state' => false,
                'message' => 'Preencha os campos obrigatórios.'
            ];
        }

        if (strtotime($post['StartDate']) > strtotime($post['EndDate'])) {
            return [
                'state' => false,
                'message' => 'A data de início não pode ser maior que a data de término.'
            ];
        }

        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $model->ActiveID = $this->getAvailableId($server);
        $model->Title = $post['Title'] ?? 'event unknown'; //req
        $model->Description = $post['Description'];
        $model->Content = $post['Content'];
        $model->AwardContent = $post['AwardContent'];
        $model->HasKey = $post['HasKey'] ?? 3; //req
        $model->StartDate = date_fmt($post["StartDate"], 'Y-m-d 00:00:00.000');
        $model->EndDate = date_fmt($post["EndDate"], 'Y-m-d 23:59:59.000');
        $model->IsOnly = isset($post['IsOnly']) ? 1 : 0; //req
        $model->Type = $post['Type'] ?? 1; //req
        $model->ActionTimeContent = $post['ActionTimeContent'];
        $model->IsAdvance = isset($post['IsAdvance']) ? 1 : 0; //req
        $model->GoodsExchangeTypes = $post['GoodsExchangeTypes'];
        $model->GoodsExchangeNum = $post['GoodsExchangeNum'];
        $model->limitType = $post['limitType'];
        $model->limitValue = $post['limitValue'];
        $model->IsShow = isset($post['IsShow']) ? 1 : 0; //req
        $model->ActiveType = $post['ActiveType'] ?? 0; //req
        $model->IconID = $post['IconID'] ?? 0; //req

        if (!$model->save()) {
            return [
                'state' => false,
                'message' => 'Falha ao criar evento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Evento criado com sucesso.'
        ];
    }

    /**
     * It updates the event in the database.
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $activeID = $post['activeID'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //validate fields required
        if (!isset($post['Title']) || !isset($post['StartDate']) || !isset($post['EndDate'])) {
            return [
                'state' => false,
                'message' => 'Preencha os campos obrigatórios.'
            ];
        }

        //validate data
        if (strtotime($post['StartDate']) > strtotime($post['EndDate'])) {
            return [
                'state' => false,
                'message' => 'A data de início não pode ser maior que a data de término.'
            ];
        }

        //find event
        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $model = $model->find($activeID);

        if (!$model) {
            return [
                'state' => false,
                'message' => 'Evento não encontrado, talvez tenha sido excluído, atualize a página.'
            ];
        }
        
        //update data
        if (
            !$model->update([
            'Title' => $post['Title'] ?? 'event unknown', //req
            'Description' => $post['Description'],
            'Content' => $post['Content'],
            'AwardContent' => $post['AwardContent'],
            'HasKey' => $post['HasKey'] ?? 3, //req
            'StartDate' => date_fmt($post["StartDate"], 'Y-m-d 00:00:00.000'),
            'EndDate' => date_fmt($post["EndDate"], 'Y-m-d 23:59:59.000'),
            'IsOnly' => isset($post['IsOnly']) ? 1 : 0, //req
            'Type' => $post['Type'] ?? 1, //req
            'ActionTimeContent' => $post['ActionTimeContent'],
            'IsAdvance' => isset($post['IsAdvance']) ? 1 : 0, //req
            'GoodsExchangeTypes' => $post['GoodsExchangeTypes'],
            'GoodsExchangeNum' => $post['GoodsExchangeNum'],
            'limitType' => $post['limitType'],
            'limitValue' => $post['limitValue'],
            'IsShow' => isset($post['IsShow']) ? 1 : 0, //req
            'ActiveType' => $post['ActiveType'] ?? 0, //req
            'IconID' => $post['IconID'] ?? 0, //req
            ])
        ) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar evento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Evento atualizado com sucesso.'
        ];
    }

    /**
     * It deletes an event from the database
     *
     * @param Request request The request object
     *
     * @return The return is a json with the following structure:
     */
    public function delete(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $activeID = $post['id'] ?? null;

        //validate server
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //delete event
        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $model = $model->where('ActiveID', $activeID);

        if (!$model->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao excluir evento.'
            ];
        }

        //delete rewards
        $rewards = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $rewards->where('ActiveID', $activeID)->delete();

        return [
            'state' => true,
            'message' => 'Evento excluído com sucesso.'
        ];
    }

    /**
     * It takes the ID of an event, finds the event, creates a new ID for the new event, changes the
     * title of the new event, inserts the new event, finds the rewards of the old event, changes the
     * ID of the rewards to the new ID, and inserts the new rewards
     *
     * @param Request request The request object.
     *
     * @return The data is being returned as an array.
     */
    public function duplicate(Request $request)
    {
        $post = $request->post(false);
        $id = $post['id'] ?? null;
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $newID = $model->max('ActiveID') + 1;

        $active = $model->where('ActiveID', $id)->first()->replicate()?->toArray();
        if (!$active) {
            return [
                'state' => false,
                'message' => 'Evento não encontrado, talvez tenha sido excluído, atualize a página.'
            ];
        }

        $active['ActiveID'] =  $newID;
        $active['Title'] =  $active['Title'] . ' #duplicado';

        $model->insert($active);

        $modelRewards = (new ActiveAward())->setTable($server->dbData . '.dbo.Active_Award');
        $rewards = $modelRewards->where('ActiveID', $id)->get()?->toArray();

        if ($rewards) {
            foreach ($rewards as &$reward) {
                unset($reward['ID']);
                $reward['ActiveID'] = $newID;
            }
            $modelRewards->insert($rewards);
        }

        return [
            'state' => true,
            'message' => 'Evento duplicado com sucesso.'
        ];
    }

    /**
     * It deletes a row from a table
     *
     * @param Request request The request object.
     *
     * @return The return is a json with the following structure:
     */
    public function reset(Request $request)
    {
        $post = $request->post(false);
        $id = $post['id'] ?? null;
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new Active())->setTable($server->dbUser . '.dbo.Active_Number');
        $model = $model->where('ActiveID', $id);

        if (!$model->delete()) {
            return [
                'state' => true,
                'message' => 'Falha ao resetar evento.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Evento resetado com sucesso.'
        ];
    }

    /**
     * It sends a request to the server and checks if the response contains the word "success"
     *
     * @param Request request The request object.
     *
     * @return The server is returning a JSON object with the following structure:
     * <code>{
     *     "state": true,
     *     "message": "Lista de atividades atualizada com sucesso."
     * }
     * </code>
     */
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
            $res = $client->request('GET', $server->quest . '/ActiveList.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'success')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar evento belo, verifique se o servidor de destino esta configurado corretamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Evento belo atualizado com sucesso.'
        ];
    }

    /**
     * It gets the highest ID from the Active table, adds one to it, and returns it
     *
     * @param Server server The server object
     *
     * @return The max value of the ActiveID column in the Active table plus 1. If there are no rows in
     * the table, it returns 1.
     */
    protected function getAvailableId(Server $server)
    {
        $model = (new Active())->setTable($server->dbData . '.dbo.Active');
        $id = $model->max('ActiveID') + 1 ?? 1;
        return $id;
    }
}
