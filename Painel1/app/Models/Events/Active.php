<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class Active extends Model
{
    protected $table = 'dbo.Active';
    protected $primaryKey = 'ActiveID';
    protected $hidden = [];
    protected $guarded = [];
    protected $fillable = [];
    public $timestamps = false;
}
