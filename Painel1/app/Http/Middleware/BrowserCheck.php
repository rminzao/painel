<?php

namespace App\Http\Middleware;

/**
 *
 */
class BrowserCheck
{
    /* This is a class property. It is a list of browsers that are not allowed to access the site. */
    protected $browsers = [
    ];

    /**
     * If the current browser is not in the list of accepted browsers, then display the view
     * 'unbearable' with the browser name as a parameter
     *
     * @param request The incoming request object.
     * @param next The next middleware in the chain.
     *
     * @return Nothing.
     */
    public function handle($request, $next)
    {
        //check if current browser is accepted
        foreach ($this->browsers as $a) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $a)) {
                echo view('unbearable', [
                  'browser' => $a
                ]);
                exit;
            }
        }

        //execute next middleware level
        return $next($request);
    }
}
