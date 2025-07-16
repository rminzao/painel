<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table = 'servers';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $hidden = [];

    protected $guarded = [];

    public static function all($columns = ['*'])
    {
        return self::select($columns)->where('visible', true)->get();
    }
}
