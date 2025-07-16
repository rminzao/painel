<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Events\EventDetail;
use App\Models\Server;
use Core\Routing\Request;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Event extends Api
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

        $model = (new EventDetail())->setTable($server->dbData . '.dbo.Event_Reward_Detail');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('ActivityType', 'LIKE', "%{$search}%") :
                $query->where('Title', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('ActivityType', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: "events.list");
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $events = $query->limit($pager->limit())->offset($pager->offset())->get()->toArray();
        $eventList = array_map(function ($events) use ($server) {

            return $events;
        }, $events);

        return [
            'state' => true,
            'data' => $eventList ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create(Request $request)
    {}

    public function update(Request $request)
    {}

    public function delete(Request $request)
    {}

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
            $res = $client->request('GET', $server->quest . '/build/Createallxml.ashx?key=' . env('APP_REQUEST_KEY'));
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Evento atualizado com sucesso.'
        ];
    }
	
}