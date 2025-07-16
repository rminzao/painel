<?php

namespace App\Http\Middleware;

use App\Models\Auth;
use App\Models\UserRoles;
use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Core\Routing\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user() || Auth::user()->role <= 1) {
            return redirect('/');
        }

        if (
            Auth::user()->role == 2 && !(new UserRoles())->check(
                Auth::user()->id,
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
