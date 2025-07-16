<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreRegistration extends Model
{
    protected $table = 'PreRegistration';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'last_call',
        'created_at',
        'updated_at',
    ];
}
