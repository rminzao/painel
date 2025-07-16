<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Models\User;
use Core\Session;

/**
 * Maintenance
 * @package App\Http\Middleware
 */
class Maintenance
{
    /**
     * If the application is in maintenance mode, then the user must be logged in as an administrator
     * to view the page
     *
     * @param request The incoming request object.
     * @param next The next middleware in the chain.
     * @return Nothing.
     */
    public function handle($request, $next)
    {
        //start session instance
        $session = new Session();

        //check maintenance page state
        if ($_ENV['APP_MAINTENANCE'] == 'true') {
            if ($session->has('uid')) {
                $user = User::find($session->uid);
                if ($user and $user->role == 1) {
                    $this->viewMaintenance();
                }

                return $next($request);
            }

            $this->viewMaintenance();
        }

        //execute next middleware level
        return $next($request);
    }

    /**
     * This function is used to display the maintenance page
     */
    protected function viewMaintenance()
    {
        echo view('others.maintenance');
        exit;
    }
}
