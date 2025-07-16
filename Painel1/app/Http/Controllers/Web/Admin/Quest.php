<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

class Quest extends Controller
{
    public function index()
    {
        return $this->view->render('admin.quest.index', [
            'servers' => Server::all(),
            'questTypes' => getLanguage('quest.types.'),
            'questConditions' => getLanguage('quest.conditions.')
        ]);
    }
}
