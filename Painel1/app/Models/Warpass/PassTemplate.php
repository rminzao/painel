<?php

namespace App\Models\Warpass;

use Illuminate\Database\Eloquent\Model;

class PassTemplate extends Model
{
    protected $connection = 'db_tank';
    protected $table = 'TS_ForcesBattle_PassTemplate';
    protected $primaryKey = 'Level';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'Level',
        'NormalAward',
        'ExtraAward'
    ];
    protected $casts = [
        'Level' => 'integer'
    ];

    public static function levelExists($level)
    {
        return self::where('Level', $level)->exists();
    }

    public static function getAllLevels()
    {
        return self::orderBy('Level', 'asc')->get()->toArray();
    }

    public static function findByLevel($level)
    {
        $result = self::where('Level', $level)->first();
        return $result ? $result->toArray() : null;
    }

    public static function createLevel($data)
    {
        try {
            $passTemplate = new self();
            $passTemplate->Level = $data['Level'];
            $passTemplate->NormalAward = $data['NormalAward'];
            $passTemplate->ExtraAward = $data['ExtraAward'];
            
            return $passTemplate->save();
        } catch (\Exception $e) {
            \Log::error('Erro ao criar nível PassTemplate: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateLevel($level, $data)
    {
        try {
            return self::where('Level', $level)->update($data);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar nível PassTemplate: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteLevel($level)
    {
        try {
            return self::where('Level', $level)->delete();
        } catch (\Exception $e) {
            \Log::error('Erro ao deletar nível PassTemplate: ' . $e->getMessage());
            return false;
        }
    }

    public static function copyRewards($fromLevel, $toLevel)
    {
        try {
            $sourceLevel = self::where('Level', $fromLevel)->first();
            if (!$sourceLevel) {
                return false;
            }

            return self::where('Level', $toLevel)->update([
                'NormalAward' => $sourceLevel->NormalAward,
                'ExtraAward' => $sourceLevel->ExtraAward
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao copiar recompensas PassTemplate: ' . $e->getMessage());
            return false;
        }
    }

    public static function getLevelRange($startLevel, $endLevel)
    {
        return self::whereBetween('Level', [$startLevel, $endLevel])
                   ->orderBy('Level', 'asc')
                   ->get()
                   ->toArray();
    }

    public static function getTotalLevels()
    {
        return self::count();
    }

    public static function getMaxLevel()
    {
        $maxLevel = self::max('Level');
        return $maxLevel ?? 0;
    }

    public static function getNextAvailableLevel()
    {
        $maxLevel = self::getMaxLevel();
        return $maxLevel + 1;
    }

    public static function isEmptyReward($rewardString)
    {
        if (empty($rewardString)) return true;
        
        // Verificar se todos os slots estão zerados
        $parts = explode('|', $rewardString);
        foreach ($parts as $part) {
            $components = explode(',', $part);
            if (count($components) >= 3) {
                $templateId = (int) $components[0];
                $count = (int) $components[1];
                if ($templateId > 0 || $count > 0) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Acessor para NormalAward parseado
     */
    public function getNormalRewardsAttribute()
    {
        return $this->parseRewards($this->NormalAward);
    }

    /**
     * Acessor para ExtraAward parseado
     */
    public function getExtraRewardsAttribute()
    {
        return $this->parseRewards($this->ExtraAward);
    }

    /**
     * Parser de recompensas
     */
    private function parseRewards($rewardString)
    {
        if (empty($rewardString)) {
            return [];
        }

        $rewards = [];
        $rewardParts = explode('|', $rewardString);
        
        foreach ($rewardParts as $index => $rewardPart) {
            $parts = explode(',', $rewardPart);
            
            if (count($parts) >= 3) {
                $rewards[] = [
                    'slot' => $index + 1,
                    'template_id' => (int) $parts[0],
                    'count' => (int) $parts[1],
                    'valid_days' => (int) $parts[2],
                    'is_empty' => ((int) $parts[0] === 0 && (int) $parts[1] === 0)
                ];
            }
        }

        return $rewards;
    }

    public function hasNormalRewards()
    {
        return !self::isEmptyReward($this->NormalAward);
    }

    public function hasExtraRewards()
    {
        return !self::isEmptyReward($this->ExtraAward);
    }

    public function getTotalRewardsCount()
    {
        $count = 0;
        
        if ($this->hasNormalRewards()) {
            $count++;
        }
        
        if ($this->hasExtraRewards()) {
            $extraRewards = explode('|', $this->ExtraAward);
            foreach ($extraRewards as $reward) {
                $parts = explode(',', $reward);
                if (count($parts) >= 3 && ((int) $parts[0] > 0 || (int) $parts[1] > 0)) {
                    $count++;
                }
            }
        }
        
        return $count;
    }

    /**
     * Validar formato de recompensa
     */
    public static function validateRewardFormat($rewardString)
    {
        if (empty($rewardString)) {
            return true; // Recompensa vazia válida
        }

        $parts = explode('|', $rewardString);
        
        foreach ($parts as $part) {
            $components = explode(',', $part);
            
            // Deve ter exatamente 3 componentes: templateId,count,days
            if (count($components) !== 3) {
                return false;
            }
            
            // Todos devem ser números
            foreach ($components as $component) {
                if (!is_numeric($component)) {
                    return false;
                }
            }
        }
        
        return true;
    }

    public function validate()
    {
        $errors = [];
        
        // Level deve ser >= 0
        if ($this->Level < 0) {
            $errors[] = 'Level deve ser maior q 0';
        }
        
        // Verificar formato das recompensas
        if (!self::validateRewardFormat($this->NormalAward)) {
            $errors[] = 'Formato inválido';
        }
        
        if (!self::validateRewardFormat($this->ExtraAward)) {
            $errors[] = 'Formato da recompensa vip inválido';
        }
        
        return $errors;
    }

    /**
     * Boot do model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Validar antes de salvar
        static::saving(function ($model) {
            $errors = $model->validate();
            if (!empty($errors)) {
                throw new \Exception('Erro de validação: ' . implode(', ', $errors));
            }
        });
    }
}