<?php

namespace App\Http\Middleware;

class RequireDeveloper
{
  /**
   * execute the middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
    public function handle($request, $next)
    {
        if (!isset($request->user)) {
            http_response_code(403);
            exit;
        }

        if ($request->user['role'] != 3) {
            http_response_code(403);
            exit;
        }

        //execute next middleware level
        return $next($request);
    }
}
