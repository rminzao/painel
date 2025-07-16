<?php

namespace App\Http\Middleware;

use Core\Session;

class UserIsLogged
{
  /**
   * execute the middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
    public function handle($request, $next)
    {
        //start session instance
        $obSession = new Session();

        //check if session exist
        if ($obSession->has('uid')) {
            redirect('/app/lobby');
        }

        //execute next middleware level
        return $next($request);
    }
}
