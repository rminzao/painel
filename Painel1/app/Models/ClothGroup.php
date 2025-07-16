<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothGroup extends Model
{
    protected $table = 'ClothGroupTemplateInfo';
    protected $primaryKey = 'ID'; 

    public $timestamps = false;

    protected $fillable = [
        'ID',
        'TemplateID',
        'Sex',
        'Description',
        'Cost',
        'Type',
        'OtherTemplateID'
    ];

    public function property()
    {
        // campo ID em ambas as tabelas
        return $this->belongsTo(ClothProperty::class, 'ID', 'ID');
    }
}
