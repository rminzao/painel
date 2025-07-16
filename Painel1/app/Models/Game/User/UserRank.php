<?php

namespace App\Models\Game\User;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    protected $table = 'dbo.Sys_User_Rank';

    protected $primaryKey = 'ID';

    protected $hidden = [];

    protected $guarded = [];

    public $timestamps = false;
}
