<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class SysUsersEventProcess extends Model
{
    protected $table = 'db_tank.dbo.Sys_Users_EventProcess';
    
    public $timestamps = false;
    public $incrementing = false;
    
    protected $fillable = [
        'UserID',
        'ActiveType',
        'Conditions', 
        'AwardGot'
    ];

    protected $casts = [
        'UserID' => 'integer',
        'ActiveType' => 'integer',
        'Conditions' => 'integer',
        'AwardGot' => 'integer'
    ];

    /**
     *  RESETAR PROGRESSO POR TIPO DE ATIVIDADE
     */
    public static function resetProgressByType($activeType)
	{
		try {
			error_log("🔍 [PROGRESS MODEL] Iniciando reset para ActiveType: {$activeType}");
			
			$activeType = (int) $activeType;
			
			// Contar registros antes de deletar
			$countBefore = self::where('ActiveType', $activeType)->count();
			error_log("🔍 [PROGRESS MODEL] Registros encontrados: {$countBefore}");
			
			if ($countBefore === 0) {
				error_log("🔍 [PROGRESS MODEL] Nenhum registro encontrado para deletar");
				return [
					'success' => true, // ✅ MUDANÇA: success = true mesmo sem dados
					'message' => 'Nenhum progresso encontrado para resetar',
					'records_found' => 0,
					'records_deleted' => 0,
					'info' => 'Missão já estava resetada ou nunca teve progresso'
				];
			}
			
			// Executar delete
			$deletedRows = self::where('ActiveType', $activeType)->delete();
			error_log("🔍 [PROGRESS MODEL] Registros deletados: {$deletedRows}");
			
			return [
				'success' => true,
				'message' => 'Progresso resetado com sucesso',
				'records_found' => $countBefore,
				'records_deleted' => $deletedRows
			];
			
		} catch (\Exception $e) {
			error_log('❌ [PROGRESS MODEL] Erro no reset: ' . $e->getMessage());
			error_log('❌ [PROGRESS MODEL] Stack trace: ' . $e->getTraceAsString());
			
			return [
				'success' => false,
				'message' => 'Erro interno: ' . $e->getMessage(),
				'records_found' => 0,
				'records_deleted' => 0
			];
		}
	}

    /**
     *  VERIFICAR PROGRESSO POR TODOS OS TIPOS
     */
    public static function getProgressStats()
    {
        try {
            error_log("🔍 [PROGRESS MODEL] Gerando estatísticas de progresso");
            
            // Contar por tipo de atividade
            $progressByType = self::selectRaw('ActiveType, COUNT(*) as total_records, COUNT(DISTINCT UserID) as unique_users')
                                ->groupBy('ActiveType')
                                ->orderBy('ActiveType')
                                ->get()
                                ->toArray();
            
            // Total geral
            $grandTotal = self::count();
            
            error_log("🔍 [PROGRESS MODEL] Stats geradas - Total: {$grandTotal}");
            
            return [
                'success' => true,
                'progress_by_type' => $progressByType,
                'grand_total' => $grandTotal,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao gerar stats: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao gerar estatísticas: ' . $e->getMessage(),
                'progress_by_type' => [],
                'grand_total' => 0
            ];
        }
    }

    /**
     *  CONTAR PROGRESSO POR TIPO ESPECÍFICO
     */
    public static function countByType($activeType)
    {
        try {
            $activeType = (int) $activeType;
            
            $result = [
                'total_records' => self::where('ActiveType', $activeType)->count(),
                'unique_users' => self::where('ActiveType', $activeType)->distinct('UserID')->count('UserID'),
                'awarded_users' => self::where('ActiveType', $activeType)->where('AwardGot', 1)->distinct('UserID')->count('UserID')
            ];
            
            error_log("🔍 [PROGRESS MODEL] Count para tipo {$activeType}: " . json_encode($result));
            
            return $result;
            
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao contar tipo: ' . $e->getMessage());
            return [
                'total_records' => 0,
                'unique_users' => 0,
                'awarded_users' => 0
            ];
        }
    }

    public static function getUserProgress($userId, $activeType = null)
    {
        try {
            $query = self::where('UserID', $userId);
            
            if ($activeType !== null) {
                $query->where('ActiveType', $activeType);
            }
            
            return $query->orderBy('ActiveType')
                        ->get()
                        ->toArray();
                        
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao buscar progresso do usuário: ' . $e->getMessage());
            return [];
        }
    }

    public static function getCompletedUsers($activeType, $requiredCondition)
    {
        try {
            return self::where('ActiveType', $activeType)
                      ->where('Conditions', '>=', $requiredCondition)
                      ->orderBy('Conditions', 'desc')
                      ->get()
                      ->toArray();
                      
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao buscar usuários completos: ' . $e->getMessage());
            return [];
        }
    }

    public static function resetUserProgress($userId, $activeType = null)
    {
        try {
            $query = self::where('UserID', $userId);
            
            if ($activeType !== null) {
                $query->where('ActiveType', $activeType);
            }
            
            $deletedRows = $query->delete();
            
            error_log("🔍 [PROGRESS MODEL] Reset usuário {$userId}: {$deletedRows} registros deletados");
            
            return [
                'success' => true,
                'records_deleted' => $deletedRows
            ];
            
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao resetar usuário: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'records_deleted' => 0
            ];
        }
    }

    public static function getTopUsers($activeType, $limit = 10)
    {
        try {
            return self::where('ActiveType', $activeType)
                      ->orderBy('Conditions', 'desc')
                      ->limit($limit)
                      ->get()
                      ->toArray();
                      
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro ao buscar top usuários: ' . $e->getMessage());
            return [];
        }
    }

    public static function resetAllProgress()
    {
        try {
            error_log("⚠️ [PROGRESS MODEL] ATENÇÃO: Deletando TODOS os registros de progresso!");
            
            $totalBefore = self::count();
            $deletedRows = self::truncate(); // ou self::delete() se truncate não funcionar
            
            error_log("⚠️ [PROGRESS MODEL] RESET TOTAL: {$totalBefore} registros deletados");
            
            return [
                'success' => true,
                'message' => 'TODOS os progressos foram resetados',
                'records_deleted' => $totalBefore
            ];
            
        } catch (\Exception $e) {
            error_log('❌ [PROGRESS MODEL] Erro no reset total: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro no reset total: ' . $e->getMessage(),
                'records_deleted' => 0
            ];
        }
    }
}