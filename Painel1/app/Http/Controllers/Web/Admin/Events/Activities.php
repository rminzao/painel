<?php

namespace App\Http\Controllers\Web\Admin\Events;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Activities extends Controller
{
    /**
     * It returns a view with a list of servers and activity types
     * 
     * @return The view is being returned.
     */
    public function index()
    {
        return $this->view->render('admin.events.activities.index', [
            'servers' => Server::all(),
            'activityTypes' => getLanguage('activities.types.'),
        ]);
    }
}
