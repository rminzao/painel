<?php

namespace App\Http\Controllers\Api;

use App\Models\Auth;
use App\Models\User;
use Core\Routing\Request;
use Core\Session;
use Core\Utils\Message;
use Firebase\JWT\JWT;

class Api
{
    /** @var Session */
    public $session;

    /** @var Message */
    protected $message;

    /** @var Request */
    protected $request;

    /** @var User|null */
    protected $user;

    /** @var array|null */
    protected $response;

    public function __construct()
    {
        $this->session = new Session();
        $this->message = new Message();
        $this->request = new Request();

        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();

        if ($this->session->has('uid')) {
            $user = User::find($this->session->uid);
            if (!$user) {
                $this->call(
                    403,
                    "access_denied",
                    "Acesso negado"
                )->back();

                $this->session->unset('flash');
                $this->session->unset('uid');
                exit;
            }

            $this->user = $user;
        }
    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return CafeApi
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): Api
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array|null $response
     * @return CafeApi
     */
    protected function back(array $response = null): Api
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * @param string $endpoint
     * @param int $limit
     * @param int $seconds
     * @param bool $attempt
     * @return bool
     */
    protected function requestLimit(string $endpoint, int $limit, int $seconds, bool $attempt = false): bool
    {
        $userToken = isset($this->headers['Authorization']) ? base64_encode(
            str_replace('Bearer ', '', $this->headers['Authorization'])
        ) : '';

        if (!$userToken) {
            $this->call(
                400,
                "invalid_data",
                "Você precisa informar seu token para continuar"
            )->back();

            return false;
        }

        $cacheDir = __DIR__ . "/../../../../storage/framework/requests";
        if (!file_exists($cacheDir) || !is_dir($cacheDir)) {
            mkdir($cacheDir, 0755);
        }

        $cacheFile = "{$cacheDir}/{$userToken}.json";
        if (!file_exists($cacheFile) || !is_file($cacheFile)) {
            fopen($cacheFile, "w");
        }

        $userCache = json_decode(file_get_contents($cacheFile));
        $cache = (array)$userCache;

        $save = function ($cacheFile, $cache) {
            $saveCache = fopen($cacheFile, "w");
            fwrite($saveCache, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fclose($saveCache);
        };

        if (empty($cache[$endpoint]) || $cache[$endpoint]->time <= time()) {
            if (!$attempt) {
                $cache[$endpoint] = [
                    "limit" => $limit,
                    "requests" => 1,
                    "time" => time() + $seconds
                ];

                $save($cacheFile, $cache);
            }

            return true;
        }

        if ($cache[$endpoint]->requests >= $limit) {
            $this->call(
                400,
                "request_limit",
                "Você excedeu o limite de requisições para essa ação"
            )->back();

            return false;
        }

        if (!$attempt) {
            $cache[$endpoint] = [
                "limit" => $limit,
                "requests" => $cache[$endpoint]->requests + 1,
                "time" => $cache[$endpoint]->time
            ];

            $save($cacheFile, $cache);
        }
        return true;
    }
}
