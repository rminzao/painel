<?php

namespace Core\Routing;

use Illuminate\Support\Arr;
use stdClass;

class Request
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $postVars = [];

    /**
     * @var array
     */
    private $headers = [];

    public function __construct($router = '')
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }

    /**
     * Set vars from post method
     * @method setPostVars
     */
    private function setPostVars()
    {
        //check request method
        if ($this->httpMethod == 'GET') {
            return false;
        }

        //default post
        $this->postVars = $_POST ?? [];

        //json post
        $inputRaw = file_get_contents('php://input');

        //set post parameters
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    private function setUri()
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        $xURI = explode('?', $this->uri);

        $this->uri = $xURI[0];
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function get(bool $isObject = false)
    {
        return $isObject ? (object)$this->queryParams : $this->queryParams;
    }

    public function post(bool $filter = true, bool $isObject = false)
    {
        if ($filter) $this->filterPost();
        return $isObject ? (object)$this->postVars : $this->postVars;
    }

    public function only($keys)
    {
        $results = [];

        $input = $_REQUEST;

        $placeholder = new stdClass;

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            $value = data_get($input, $key, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }

    public static function ip()
    {
        $keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($keys as $k) {
            if (!empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
                return $_SERVER[$k];
            }
        }
        return "0.0.0.0";
    }

    protected function filterPost(): void
    {
        foreach ($this->postVars ?? [] as $key => $value) {
            if (is_array($this->postVars[$key])) {
                $this->postVars[$key] = filter_var_array(array_map('trim', $value), FILTER_SANITIZE_SPECIAL_CHARS);
                continue;
            }
            $this->postVars[$key] = filter_var(trim($value), FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }
}
