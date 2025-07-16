<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public function server()
    {
        return $this->belongsTo(Server::class, 'sid', 'id');
    }
}
