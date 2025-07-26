<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class EventRewardGoods extends Model
{
    // Tabela com esquema completo
    protected $table = 'db_tank.dbo.EventReward_Goods';
    
    public $timestamps = false;
    public $incrementing = false;
    
    // CAMPOS CORRETOS
    protected $fillable = [
        'ActivityType',
        'SubActivityType',
        'TemplateId',
        'StrengthLevel',
        'AttackCompose',
        'DefendCompose',
        'LuckCompose',
        'AgilityCompose',
        'IsBind',
        'ValidDate',
        'Count'
    ];

    protected $casts = [
        'ActivityType' => 'integer',
        'SubActivityType' => 'integer',
        'TemplateId' => 'integer',
        'StrengthLevel' => 'integer',
        'AttackCompose' => 'integer',
        'DefendCompose' => 'integer',
        'LuckCompose' => 'integer',
        'AgilityCompose' => 'integer',
        'IsBind' => 'integer',
        'ValidDate' => 'integer',
        'Count' => 'integer'
    ];

    /**
     * BUSCAR TODAS AS RECOMPENSAS
     */
    public static function getAllRewards()
    {
        try {
            return self::orderBy('ActivityType', 'asc')
                       ->orderBy('SubActivityType', 'asc')
                       ->orderBy('TemplateId', 'asc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao buscar recompensas: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * BUSCAR RECOMPENSAS POR MISSÃO
     */
    public static function getRewardsByMission($activityType, $subActivityType)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->orderBy('TemplateId', 'asc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao buscar por missão: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * BUSCAR RECOMPENSA ESPECÍFICA
     */
    public static function findSpecificReward($activityType, $subActivityType, $templateId)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->where('TemplateId', $templateId)
                       ->first();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao buscar recompensa específica: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * VERIFICAR SE RECOMPENSA EXISTE
     */
    public static function rewardExists($activityType, $subActivityType, $templateId)
    {
        try {
            error_log("🔍 [DEBUG] Verificando recompensa: ActivityType={$activityType}, SubActivityType={$subActivityType}, TemplateId={$templateId}");
            
            // Garantir que são inteiros
            $activityType = (int) $activityType;
            $subActivityType = (int) $subActivityType;
            $templateId = (int) $templateId;
            
            error_log("🔍 [DEBUG] Após conversão: ActivityType={$activityType}, SubActivityType={$subActivityType}, TemplateId={$templateId}");
            
            // Buscar diretamente no banco
            $result = self::where('ActivityType', $activityType)
                         ->where('SubActivityType', $subActivityType)
                         ->where('TemplateId', $templateId)
                         ->first();
            
            $exists = $result !== null;
            
            error_log("🔍 [DEBUG] Resultado da consulta: " . ($exists ? 'ENCONTRADO' : 'NÃO ENCONTRADO'));
            
            if ($result) {
                error_log("🔍 [DEBUG] Dados encontrados: " . json_encode($result->toArray()));
            } else {
                // Listar todas as recompensas da missão para debug
                $allRewards = self::select('TemplateId')
                                ->where('ActivityType', $activityType)
                                ->where('SubActivityType', $subActivityType)
                                ->get();
                
                error_log("🔍 [DEBUG] Recompensas disponíveis na missão {$activityType}-{$subActivityType}:");
                foreach ($allRewards as $reward) {
                    error_log("  - TemplateId: {$reward->TemplateId}");
                }
            }
            
            return $exists;
            
        } catch (\Exception $e) {
            error_log('❌ [DEBUG] Erro no rewardExists: ' . $e->getMessage());
            error_log('❌ [DEBUG] Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * CRIAR NOVA RECOMPENSA
     */
    public static function createReward($data)
    {
        try {
            error_log('🔍 [CREATE REWARD DEBUG] Dados recebidos: ' . print_r($data, true));
            return self::create($data);
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao criar recompensa: ' . $e->getMessage());
            error_log('❌ [REWARD MODEL DEBUG] Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * ATUALIZAR RECOMPENSA
     */
    public static function updateReward($activityType, $subActivityType, $templateId, $data)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->where('TemplateId', $templateId)
                       ->update($data);
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao atualizar recompensa: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETAR RECOMPENSA
     */
    public static function deleteReward($activityType, $subActivityType, $templateId)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->where('TemplateId', $templateId)
                       ->delete();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao deletar recompensa: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETAR TODAS RECOMPENSAS DE UMA MISSÃO
     */
    public static function deleteAllMissionRewards($activityType, $subActivityType)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->where('SubActivityType', $subActivityType)
                       ->delete();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao deletar recompensas da missão: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * BUSCAR RECOMPENSAS POR TIPO DE ATIVIDADE
     */
    public static function getRewardsByActivityType($activityType)
    {
        try {
            return self::where('ActivityType', $activityType)
                       ->orderBy('SubActivityType', 'asc')
                       ->orderBy('TemplateId', 'asc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao buscar por tipo: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * CONTAR RECOMPENSAS POR MISSÃO
     */
    public static function getRewardCountByMission()
    {
        try {
            return self::selectRaw('ActivityType, SubActivityType, COUNT(*) as count')
                       ->groupBy('ActivityType', 'SubActivityType')
                       ->orderBy('ActivityType')
                       ->orderBy('SubActivityType')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao contar por missão: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * ESTATÍSTICAS GERAIS DE RECOMPENSAS
     */
    public static function getRewardStats()
    {
        try {
            return [
                'total_rewards' => self::count(),
                'unique_templates' => self::distinct('TemplateId')->count('TemplateId'),
                'rewards_by_mission' => self::getRewardCountByMission(),
                'count_stats' => [
                    'min' => self::min('Count'),
                    'max' => self::max('Count'),
                    'avg' => round(self::avg('Count'), 2)
                ]
            ];
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao gerar stats: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * BUSCAR POR TEMPLATE ID
     */
    public static function getByTemplateId($templateId)
    {
        try {
            return self::where('TemplateId', $templateId)
                       ->orderBy('ActivityType', 'asc')
                       ->orderBy('SubActivityType', 'asc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao buscar por template: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * DUPLICAR RECOMPENSAS DE UMA MISSÃO PARA OUTRA
     */
    public static function duplicateRewards($fromActivityType, $fromSubActivityType, $toActivityType, $toSubActivityType)
    {
        try {
            $rewards = self::where('ActivityType', $fromActivityType)
                          ->where('SubActivityType', $fromSubActivityType)
                          ->get();
            
            foreach ($rewards as $reward) {
                $newReward = $reward->toArray();
                $newReward['ActivityType'] = $toActivityType;
                $newReward['SubActivityType'] = $toSubActivityType;
                
                self::create($newReward);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log('❌ [REWARD MODEL DEBUG] Erro ao duplicar recompensas: ' . $e->getMessage());
            return false;
        }
    }
}