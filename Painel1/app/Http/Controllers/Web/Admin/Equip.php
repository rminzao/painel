<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;

/**
 * Equip class
 */
class Equip extends Controller
{
    /**
     * Get userEquip list
     *
     * @return string
     */
    public function getList()
    {
        return $this->view->render('admin.users.equip');
    }
}
