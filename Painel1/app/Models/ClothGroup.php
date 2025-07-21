<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClothGroup extends Model
{
    // Tabela correta com esquema completo
    protected $table = 'db_tank.dbo.ClothGroupTemplateInfo';
    
    // Chave primária auto-increment
    protected $primaryKey = 'ItemID'; 
    
    // Sem timestamps
    public $timestamps = false;
    
    // Colunas preenchíveis
    protected $fillable = [
        'ID',              // ID da fugura
        'TemplateID',      // ID do item real
        'Sex',             // Sexo do item
        'Description',     // Descrição
        'Cost',            // Custo
        'Type',            // Tipo
        'OtherTemplateID'  // Outro Template ID
    ];
    
    // Relacionamento com ClothProperty (figura principal)
    public function property()
    {
        return $this->belongsTo(ClothProperty::class, 'ID', 'ID');
    }
    
    // Accessor para imagem do item
    public function getIconAttribute()
    {
        return image_item($this->TemplateID, 'db_tank');
    }
}