<?php

namespace Core\Routing\Middleware;

use Closure;
use Core\Routing\Request;
use Core\Routing\Response;

/**
 * Responsible for processing the middleware
 *
 * @author Gabriel Amorim <redetank18@gmail.com>
 */
class Queue
{
    /**
     * Middleware map
     *
     * @var array
     **/
    private static $map = [];

    /**
     * Default middleware map
     *
     * @var array
     */
    private static $default = [];

    /**
     * Row from middleware executables
     *
     * @var array
     **/
    private $middleware = [];

    /**
     * Executable function controller
     *
     * @var Closure
     */
    private $controller;

    /**
     * Executable function controller arguments
     *
     * @var array
     */
    private $controllerArgs = [];

    /**
     * Build the middleware queue class
     *
     * @param array   $middleware
     * @param Closure $controller
     * @param array   $controllerArgs
     */
    public function __construct($middleware, $controller, $controllerArgs)
    {
        $this->middleware = array_merge(self::$default, $middleware);
        $this->controller = Closure::fromCallable($controller);
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Set middleware map
     *
     * @param array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

    /**
     * Set default middleware map
     *
     * @param array $default
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }

    /**
     * Execute next level from row middleware
     *
     * @param  Request $request
     * @return Response
     */
    public function next($request)
    {
        if (empty($this->middleware)) {
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        $middleware = array_shift($this->middleware);

        //check map
        if (!isset(self::$map[$middleware])) {
            throw new \Exception("Problems processing middleware on request.", 500);
        }

        //next
        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        //run middleware
        return (new self::$map[$middleware]())->handle($request, $next);
    }
}
