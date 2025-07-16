<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class GameMap extends Model
{
    protected $table = 'dbo.Game_Map';

    protected $primaryKey = 'ID';

    protected $hidden = [];

    protected $guarded = [];

    public $timestamps = false;
}
