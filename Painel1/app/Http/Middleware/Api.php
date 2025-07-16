<?php

namespace App\Http\Middleware;

/* A middleware that changes the content type of the request to application/json. */
class Api
{
    /**
     * The handle function is called when the middleware is executed.
     * It takes in two parameters, the request and the next middleware.
     * The request is the request object that is passed to the middleware.
     * The next middleware is the next middleware in the middleware chain.
     * The handle function returns the next middleware in the chain
     *
     * @param request The request object.
     * @param next The next middleware in the chain.
     *
     * @return Nothing.
     */
    public function handle($request, $next)
    {
        //change contentType from json
        $request->getRouter()->setContentType('application/json');

        //execute next middleware level
        return $next($request);
    }
}
