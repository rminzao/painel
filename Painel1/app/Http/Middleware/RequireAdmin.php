<?php

namespace App\Http\Middleware;

use App\Models\User;
use Core\Session;

class RequireAdmin
{
  /**
   * execute the middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
    public function handle($request, $next)
    {
        $session = new Session();

        if (!isset($request->user) or !$session->has('uid')) {
            http_response_code(403);
            exit;
        }

        $user = User::find($session->uid);

        if ($request->user['role'] != 2 or !$user or $user->role != 2) {
            http_response_code(403);
            exit;
        }

        //execute next middleware level
        return $next($request);
    }
}
