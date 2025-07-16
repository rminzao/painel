<?php

namespace App\Http\Controllers\Api;

use App\Models\Server;
use App\Models\User;
use GuzzleHttp\Client;

class Play extends Api
{
    /**
     * @param id The server id.
     * @return The user hash and the hash of the question.
     */
    public function info($id): array
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return [
                'state'   => false,
                'message' => 'Id do servidor é inválido.'
            ];
        }
		
		

        // return [
        //     'state' => true,
        //     'data'  => [
        //         'user' => 'notyourbae',
        //         'hash' => 'ys2lqj48cvon5dwg1i7rp39umb0xethf',
        //     ]
        // ];

        if (!$server = Server::find($id)) {
            return [
                'state'   => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        if (
            ($_ENV['APP_MAINTENANCE'] == 'true' or !$server->active) &&
            $this->user->role == 1
        ) {
            return [
                'state'   => false,
                'message' => 'O servidor está em manutenção, tente novamente mais tarde.'
            ];
        }

        if (
            !$hash = $this->getHashUser(
                $this->user->u_hash,
                $this->user->p_hash,
                $server->quest
            )

            // !$hash = $this->getHashUserNew(
            //     $this->user->u_hash,
            //     $server->quest
            // )
        ) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro interno, atualize a página e tente novamente.'
            ];
        }

        return [
            'state' => true,
            'data'  => [
                'user' => $this->user->u_hash,
                'hash' => $hash,
            ]
        ];
    }

    /**
     * @param $id
     * @param $uid
     * @return array
     */
    public function getPlayerInfoByAdmin($id, $uid): array
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return [
                'state' => false,
                'message' => 'Id do servidor é inválido.'
            ];
        }

        $server = Server::find($id);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $user = User::find($uid);
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Usuário não encontrado.'
            ];
        }

        if (
            !$hash = $this->getHashUser(
                $user->u_hash,
                $user->p_hash,
                $server->quest
            )

            // !$hash = $this->getHashUserNew(
            //     $user->u_hash,
            //     $server->quest
            // )
        ) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro interno, atualize a página e tente novamente.'
            ];
        }

        return [
            'state' => true,
            'data' => [
                'user' => $user->u_hash,
                'hash' => $hash,
            ]
        ];
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

    /**
     * @param string $user
     * @param string $key
     * @param string $quest
     * @return string|null
     */
    protected function getHashUserNew(string $user, string $quest): ?string
    {
        $key = str_hash(15);
        $time = strtotime(date('Y-m-d H:i:s'));
        $hash = md5($user . $key . $time . $_ENV['APP_LOGIN_KEY']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $quest . '/CreateLogin.aspx?content=' . urlencode("{$user}|{$key}|{$time}|{$hash}"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        if ($response['http_code'] != 200 or in_array($content, ['-91010', '-1900'])) {
            return null;
        }

        return $key;
    }
}
