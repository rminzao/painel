<?php

namespace App\Http\Middleware;

use Core\Routing\Request;
use Core\Session;

class UserIsUnlogged
{
    /**
     * execute the middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, $next)
    {
        $session = new Session();

        if (!$session->has('uid')) {
            redirect();
        }

        $user = \App\Models\User::find($session->uid);

        if (!$user) {
            $session->destroy();
            redirect();
        }

        if (!$user->active) {
            $session->destroy();
            echo view('auth.banned');
            exit;
        }

        return $next($request);
    }
}
