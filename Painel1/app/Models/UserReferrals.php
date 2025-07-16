<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReferrals extends Model
{
    protected $table = 'users_referrals';

    protected $primaryKey = 'id';

    protected $hidden = ['id'];

    protected $fillable = [];

    protected $guarded = [];
}

