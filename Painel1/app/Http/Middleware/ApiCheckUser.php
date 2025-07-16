<?php

namespace App\Http\Middleware;

use Core\Session;

/**
 *
 */
class ApiCheckUser
{
    /**
     * If the session is not set, then the user is not logged in
     *
     * @param request The request object.
     * @param next The next middleware function to be called.
     * @return Nothing.
     */
    public function handle($request, $next)
    {
        //start session instance
        $obSession = new Session();

        //check if session exist
        if (!$obSession->has('uid')) {
            //change http response
            http_response_code(403);
            exit;
        }

        //execute next middleware level
        return $next($request);
    }
}
