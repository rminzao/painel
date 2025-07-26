<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventRewardInfo extends Model
{
    // Tabela com esquema completo
    protected $table = 'db_tank.dbo.EventReward_Info';
    
    public $timestamps = false;
    public $incrementing = false;
    
    protected $fillable = [
        'ActivityType',
        'SubActivityType', 
        'Condition'
    ];

    protected $casts = [
        'ActivityType' => 'integer',
        'SubActivityType' => 'integer',
        'Condition' => 'integer'
    ];

    /**
     * 📋BUSCAR TODAS AS MISSÕES
     */
    public static function getAllMissions()
    {
        try {
            return self::orderBy('ActivityType', 'asc')
                       ->orderBy('SubActivityType', 'asc')
                       ->get()
                       ->toArray(); // sempre retornar array
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao buscar missões: ' . $e->getMessage());
            return []; // retornar array vazio, não collection
        }
    }

    /**
     * TIPOS DE MISSÃO FIXOS
     */
    public static function getMissionTypes()
    {
        return [
            1 => 'Recompensas por Aumentar de nível',
            2 => 'Recompensas por Vitórias do PvP',
            3 => 'Recompensas por Consumir Cupons',
            4 => 'Recompensas por Força de Combate',
            5 => 'Recompensas por Recarregar Cupons',
            6 => 'Recompensas diária por Recarregar Cupons',
            7 => 'Recompensas por Vitórias de GvG',
            8 => 'Recompensas por Tempo Online',
            9 => 'Recompensas diária por Consumir Cupons'
        ];
    }

    /**
     * BUSCAR MISSÃO ESPECÍFICA
     */
    public static function findByGroupAndSubgroup($activityType, $subActivityType)
    {
        try {
            $result = self::where('ActivityType', $activityType)
                         ->where('SubActivityType', $subActivityType)
                         ->first();
            
            return $result ? $result->toArray() : null; // retornar array
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao buscar missão: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * VERIFICAR SE MISSÃO EXISTE 
     */
    public static function missionExists($activityType, $subActivityType)
    {
        try {
            error_log("🔍 [DEBUG] Verificando missão: ActivityType={$activityType}, SubActivityType={$subActivityType}");
            
            // Garantir que são inteiros
            $activityType = (int) $activityType;
            $subActivityType = (int) $subActivityType;
            
            error_log("🔍 [DEBUG] Após conversão: ActivityType={$activityType}, SubActivityType={$subActivityType}");
            
            // Buscar diretamente no banco
            $result = self::where('ActivityType', $activityType)
                         ->where('SubActivityType', $subActivityType)
                         ->first();
            
            $exists = $result !== null;
            
            error_log("🔍 [DEBUG] Resultado da consulta: " . ($exists ? 'ENCONTRADO' : 'NÃO ENCONTRADO'));
            
            if ($result) {
                error_log("🔍 [DEBUG] Dados encontrados: " . json_encode($result->toArray()));
            } else {
                // Listar todas as missões para debug
                $allMissions = self::select('ActivityType', 'SubActivityType')
                                 ->orderBy('ActivityType')
                                 ->orderBy('SubActivityType')
                                 ->get();
                
                error_log("🔍 [DEBUG] Missões disponíveis no banco:");
                foreach ($allMissions as $mission) {
                    error_log("  - {$mission->ActivityType}-{$mission->SubActivityType}");
                }
            }
            
            return $exists;
            
        } catch (\Exception $e) {
            error_log('❌ [DEBUG] Erro no missionExists: ' . $e->getMessage());
            error_log('❌ [DEBUG] Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * ➕ CRIAR NOVA MISSÃO
     */
    public static function createMission($data)
    {
        try {
            return self::create($data);
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao criar missão: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * ATUALIZAR MISSÃO
     */
    public static function updateMission($activityType, $subActivityType, $data)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->update($data);
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao atualizar missão: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETAR MISSÃO E SUAS RECOMPENSAS
     */
    public static function deleteMission($activityType, $subActivityType)
    {
        try {
            // Deletar recompensas primeiro
            EventRewardGoods::where('ActivityType', $activityType)
                           ->where('SubActivityType', $subActivityType)
                           ->delete();
            
            // Deletar missão
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->delete();
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao deletar missão: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * BUSCAR MISSÕES POR TIPO
     */
    public static function getMissionsByType($activityType)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->orderBy('SubActivityType', 'asc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao buscar por tipo: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * CONTAR MISSÕES POR TIPO
     */
    public static function getMissionCountByType()
    {
        try {
            $counts = self::selectRaw('ActivityType, COUNT(*) as count')
                         ->groupBy('ActivityType')
                         ->orderBy('ActivityType')
                         ->get()
                         ->pluck('count', 'ActivityType')
                         ->toArray();
            
            // Garantir que todos os tipos tenham contagem
            $types = self::getMissionTypes();
            $result = [];
            
            foreach ($types as $typeId => $typeName) {
                $result[$typeId] = [
                    'name' => $typeName,
                    'count' => $counts[$typeId] ?? 0
                ];
            }
            
            return $result;
            
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao contar por tipo: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * ESTATÍSTICAS GERAIS
     */
    public static function getStats()
    {
        try {
            return [
                'total_missions' => self::count(),
                'missions_by_type' => self::getMissionCountByType(),
                'condition_stats' => [
                    'min' => self::min('Condition'),
                    'max' => self::max('Condition'),
                    'avg' => round(self::avg('Condition'), 2)
                ]
            ];
        } catch (\Exception $e) {
            error_log('❌ [MODEL DEBUG] Erro ao gerar stats: ' . $e->getMessage());
            return [];
        }
    }
}