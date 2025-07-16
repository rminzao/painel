<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;
use GuzzleHttp\Client;

class Play extends Controller
{
	public function index($sid)
    {
        $int = filter_var($sid, FILTER_VALIDATE_INT);
        if (!$int) {
            redirect('/');
        }

        $server = Server::find($sid);
        if (!$server) {
            redirect('/');
        }

        $cookie = "{$server->name};{$server->id};" . date('Y-m-d H:i:s');

        $server->settings = json_decode(unserialize($server['settings']));

        $data = [
            'server' => $server,
            'type' => 'user',
            'cookie' => $cookie,
            'hash' => $this->getHashUser($this->user->u_hash, $this->user->p_hash, $server->quest)
            //'hash' => $this->getHashUserNew($this->user->u_hash, $server->quest)

        ];

        $view = isset($_SESSION['clientUser']) ? 'playDirectGame' : 'playgame';

        return $this->view->render("others.{$view}", $data);
    }
	
	/**
     * @param string $user
     * @param string $key
     * @param string $quest
     *
     * @return The hash of the user.
     */

    protected function getHashUser(string $user, string $key, string $quest): ?string
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $quest . '/CreateLoginMec.aspx?content=' . urlencode($user . '|' . $key));
	
            $http_code = $response->getStatusCode();
            $content = $response->getBody()->getContents();

            if ($http_code != 200 or in_array($content, ['0', '-1900'])) {
                return throw new \Exception("Error Processing Request", 403);
            }

            return $content;
        } catch (\Throwable $th) {
            return throw new \Exception("Error Processing Request", 403);
        }
    }
}
