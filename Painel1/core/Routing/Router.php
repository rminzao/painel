<?php

namespace Core\Routing;

use Closure;
use Exception;
use ReflectionFunction;
use Core\Routing\Middleware\Queue as MiddlewareQueue;

class Router
{
    /**
     * Router base url
     *
     * @var string
     */
    private $url = '';

    /**
     * Router prefix
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Routes indices
     *
     * @var array
     */
    private $routes = [];

    /**
     * Request instance
     *
     * @var Request
     */
    private $request;

    /**
     * Default Content-type instance
     *
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Start router class
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Change value from contentType
     *
     * @method setContentType
     * @param  string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Define prefix from routes
     */
    private function setPrefix()
    {
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Add route on class
     *
     * @param string $method
     * @param string $route
     * @param array  $params
     */
    private function addRoute($method, $route, $params = [], $name = null)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['middleware'] = $params['middleware'] ?? [];

        $params['variables'] = [];

        $params['name'] = $name;

        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //remove / on end route
        $route = rtrim($route, '/');

        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Define get route
     *
     * @param string $route
     * @param array  $params
     */
    public function get($route, $params = [], $name = null)
    {
        $this->addRoute('GET', $route, $params, $name);
    }

    /**
     * Define post route
     *
     * @param string $route
     * @param array  $params
     */
    public function post($route, $params = [], $name = null)
    {
        return $this->addRoute('POST', $route, $params, $name);
    }

    /**
     * Define put route
     *
     * @param string $route
     * @param array  $params
     */
    public function put($route, $params = [], $name = null)
    {
        return $this->addRoute('PUT', $route, $params, $name);
    }

    /**
     * Define delete route
     *
     * @param string $route
     * @param array  $params
     */
    public function delete($route, $params = [], $name = null)
    {
        return $this->addRoute('DELETE', $route, $params, $name);
    }

    /**
     * Return uri
     *
     * @return string
     */
    public function getUri()
    {
        $uri = $this->request->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return rtrim(end($xUri), '/');
    }

    /**
     * Get current route
     *
     * @return array
     */
    private function getRoute()
    {
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    return $methods[$httpMethod];
                }
                throw new Exception(!strpos($uri, 'api/') ? view('error.404') : "method not allowed.", 405);
            }
        }
        throw new Exception(!strpos($uri, 'api/') ? view('error.404') : 'not found', 404);
    }

    /**
     * Execute current route
     *
     * @return Response
     */
    public function run()
    {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception("Url cannot be processed.", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return (new MiddlewareQueue($route['middleware'], $route['controller'], $args))->next($this->request);
        } catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    /**
     * Return error message by contentType
     *
     * @method getErrorMessage
     * @param  string $message
     * @return mixed
     */
    private function getErrorMessage($message)
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];

            default:
                return $message;
        }
    }

    public function getCurrentUrl()
    {
        return $this->url . $this->getUri();
    }

    public function getRouteByName($name)
    {
        foreach ($this->routes as $methods) {
            $route = $methods[$this->request->getHttpMethod()];
            if (isset($route['name']) && $route['name'] == $name) {
                return $methods;
            }
        }
        return null;
    }

    public function getRouteName()
    {
        $route = $this->getRoute();
        return $route['name'] ?? null;
    }

    /**
     * Redirect url
     *
     * @method redirect
     * @param  string $route
     */
    public function redirect($route)
    {
        //url
        $url = $this->url . $route;

        //redirect
        header('Location: ' . $url);
        exit;
    }
}
