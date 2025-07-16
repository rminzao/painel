<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothProperty extends Model
{
    // tabelas usadas
    protected $table = 'db_tank.dbo.ClothPropertyTemplateInfo';

    // chave primaria
    protected $primaryKey = 'ID';

    public $timestamps = false;

    // colunas
    protected $fillable = [
        'Name', 'Attack', 'Defend', 'Agility', 'Luck',
        'Blood', 'Damage', 'Guard', 'Cost', 'Sex', 'Type'
    ];

    // Relação entre tabelas
    public function groups()
    {
        return $this->hasMany(ClothGroup::class, 'ID', 'ID'); 
    }
}