<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = 'users_role';

    protected $fillable = [];

    public function getRoles($id)
    {
        return $this->where('uid', $id)->get();
    }

    public function check($id, $route, $action)
    {
        $roles = $this->where([
            ['uid' ,$id],
            ['route' ,$route],
            ['methods', 'LIKE' ,"%{$action}%"],
        ])->get();

        return $roles->count() > 0;
    }
}

