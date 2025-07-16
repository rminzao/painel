<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserRoles;
use Core\Session;

class RequireAdminView
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
        if (!$session->has('uid')) {
            redirect();
        }

        $user = User::find($session->uid);
        if (!$user || ($user->role >= 0 && $user->role <= 1)) {
            echo view('error.404');
            exit;
        }

        if (
            $user->role == 2 && !(new UserRoles())->check(
                $user->id,
                $request->getRouter()->getRouteName(),
                $request->getHttpMethod()
            )
        ) {
            http_response_code(403);
            exit;
        }

        return $next($request);
    }
}
