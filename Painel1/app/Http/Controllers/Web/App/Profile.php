<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;
use App\Models\Character;
use App\Models\Invoice;

class Profile extends Controller
{
    public function characters()
    {
        $charsList = [];

        foreach (Server::all() as $server) {
            $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
            foreach ($model->where('UserName', '=', $this->user->u_hash)->get()?->toArray() as $character) {
                $character['equipment'] = image_equipment(
                    $character['Style'],
                    $server->dbData,
                    $character['Sex'] ? 'm' : 'f'
                );
                $charsList[] = array_merge(['server' => $server->toArray()], $character);
            }
        }

        return $this->view->render('app.account.characters', [
            'charsList' => $charsList,
            'page' => 'characters'
        ]);
    }

    public function invoices()
    {
        $invoices = Invoice::where('uid', '=', $this->user->id)->get();
        return $this->view->render('app.account.invoices', [
            'invoiceList' => $invoices,
            'page' => 'my-invoices'
        ]);
    }

    public function overview()
    {
        return $this->view->render('app.account.overview', [
            'page' => 'overview'
        ]);
    }

    public function settings()
    {
        return $this->view->render('app.account.settings', [
            'page' => 'settings'
        ]);
    }

    public function referrals()
    {
        return $this->view->render('app.account.referrals', [
            'page' => 'referrals'
        ]);
    }
}
