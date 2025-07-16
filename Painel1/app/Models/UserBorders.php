<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserBorders extends Model
{
    protected $table = 'users_border';

    protected $primaryKey = 'id';

    protected $hidden = [];

    protected $fillable = [];

    protected $guarded = [];

    public function getByUser(int $id)
    {
        return $this->where('uid', $id)->get();
    }
}
