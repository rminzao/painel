<?php

namespace App\Http\Controllers\Web\Admin\Events;

use App\Http\Controllers\Web\Controller;
use App\Models\Events\EventRewardInfo;
use App\Models\Events\EventRewardGoods;
use App\Models\Events\SysUsersEventProcess;
use App\Models\ShopGoods;

class Mission extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     *  PÁGINA WEB - Lista de Missões
     */
    public function index()
    {
        try {
            $missions = $this->getMissionsFromDbTank();
            $missionTypes = EventRewardInfo::getMissionTypes();
            
            $servers = [
                (object)['id' => 1, 'name' => 'Servidor 1'],
                (object)['id' => 2, 'name' => 'Servidor 2']
            ];
            
            $organizedMissions = [];
            foreach ($missions as $mission) {
                $type = $mission['ActivityType'];
                $typeName = $missionTypes[$type] ?? "Tipo {$type}";

                if (!isset($organizedMissions[$type])) {
                    $organizedMissions[$type] = [
                        'name' => $typeName,
                        'missions' => []
                    ];
                }

                $organizedMissions[$type]['missions'][] = $mission;
            }

            return $this->view->render('admin.events.missions.index', [
                'missions' => $missions,
                'organizedMissions' => $organizedMissions,
                'missionTypes' => $missionTypes,
                'servers' => $servers,
                'totalMissions' => count($missions)
            ]);

        } catch (\Exception $e) {
            return $this->view->render('admin.events.missions.index', [
                'missions' => [],
                'organizedMissions' => [],
                'missionTypes' => EventRewardInfo::getMissionTypes(),
                'servers' => [],
                'totalMissions' => 0,
                'error_message' => 'Erro ao carregar missões: ' . $e->getMessage()
            ]);
        }
    }

    /**
     *  API JSON - Lista de Missões
     */
    public function getData()
    {
        try {
            $missions = $this->getMissionsFromDbTank();
            $missionTypes = EventRewardInfo::getMissionTypes();
            
            $organizedMissions = [];
            foreach ($missions as $mission) {
                $type = $mission['ActivityType'];
                $typeName = $missionTypes[$type] ?? "Tipo {$type}";

                if (!isset($organizedMissions[$type])) {
                    $organizedMissions[$type] = [
                        'name' => $typeName,
                        'missions' => []
                    ];
                }

                $organizedMissions[$type]['missions'][] = $mission;
            }

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'missions' => $missions,
                    'organized_missions' => $organizedMissions,
                    'mission_types' => $missionTypes,
                    'total_missions' => count($missions)
                ]
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao carregar missões: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     *  MOSTRAR MISSÃO ESPECÍFICA
     */
    public function show($activityType, $subActivityType)
    {
        try {
            $mission = EventRewardInfo::findByGroupAndSubgroup($activityType, $subActivityType);
            
            if (!$mission) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Missão não encontrada'
                ], 404);
            }

            $rewards = EventRewardGoods::getRewardsByMission($activityType, $subActivityType);

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'mission' => $mission,
                    'rewards' => $rewards,
                    'mission_type_name' => EventRewardInfo::getMissionTypes()[$activityType] ?? "Tipo {$activityType}"
                ]
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  CRIAR NOVA MISSÃO
     */
    public function store()
    {
        try {
            $data = $_POST;
            
            if (empty($data['ActivityType']) || !is_numeric($data['ActivityType'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'ActivityType é obrigatório e deve ser numérico'
                ], 422);
            }

            if (empty($data['SubActivityType']) || !is_numeric($data['SubActivityType'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'SubActivityType é obrigatório e deve ser numérico'
                ], 422);
            }

            if (empty($data['Condition']) || !is_numeric($data['Condition'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Condition é obrigatório e deve ser numérico'
                ], 422);
            }

            $activityType = (int) $data['ActivityType'];
            $subActivityType = (int) $data['SubActivityType'];
            $condition = (int) $data['Condition'];

            if (EventRewardInfo::missionExists($activityType, $subActivityType)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => "Missão já existe com ActivityType {$activityType} e SubActivityType {$subActivityType}"
                ], 409);
            }

            $missionData = [
                'ActivityType' => $activityType,
                'SubActivityType' => $subActivityType,
                'Condition' => $condition
            ];

            $mission = EventRewardInfo::createMission($missionData);

            if (!$mission) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao salvar missão no banco'
                ], 500);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Missão criada com sucesso!',
                'data' => $missionData
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao criar missão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  ATUALIZAR MISSÃO
     */
    public function update($activityType, $subActivityType)
    {
        try {
            $data = $_POST;

            if (!EventRewardInfo::missionExists($activityType, $subActivityType)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Missão não encontrada'
                ], 404);
            }

            if (empty($data['Condition']) || !is_numeric($data['Condition'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Condition é obrigatório e deve ser numérico'
                ], 400);
            }

            $updateData = ['Condition' => (int) $data['Condition']];
            
            $updated = EventRewardInfo::updateMission($activityType, $subActivityType, $updateData);

            if (!$updated) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao atualizar missão'
                ], 500);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Missão atualizada com sucesso!',
                'data' => $updateData
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
     *  DELETAR MISSÃO
     */
    public function destroy($activityType, $subActivityType)
    {
        try {
            if (!EventRewardInfo::missionExists($activityType, $subActivityType)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Missão não encontrada'
                ], 404);
            }

            $deleted = EventRewardInfo::deleteMission($activityType, $subActivityType);

            if (!$deleted) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao deletar missão'
                ], 500);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Missão deletada com sucesso!'
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

	public function resetProgress($activityType)
	{
		try {
			$activityType = (int) $activityType;
			
			// Validação do ActivityType
			if ($activityType < 1 || $activityType > 9) {
				return $this->jsonResponse([
					'success' => false,
					'message' => 'ActivityType deve estar entre 1 e 9'
				], 400);
			}

			// Usar o novo model para resetar
			$result = SysUsersEventProcess::resetProgressByType($activityType);
			
			if (!$result['success']) {
				return $this->jsonResponse([
					'success' => false,
					'message' => $result['message'],
					'data' => [
						'activity_type' => $activityType,
						'records_found' => $result['records_found'],
						'records_deleted' => $result['records_deleted'],
						'timestamp' => date('Y-m-d H:i:s')
					]
				], 404);
			}

			// Nomes das missões
			$missionTypes = [
				1 => "Recompensas por Aumentar de nível",
				2 => "Vitória no PvP", 
				3 => "Consumir Cupons - Acumulado",
				4 => "Força de combate",
				5 => "Recarga de cupons - Acumulado",
				6 => "Recarga de cupons - Diária",
				7 => "Vitória Gvg",
				8 => "Tempo Online",
				9 => "Consumir Cupons - Diária"
			];
			
			$missionTypeName = $missionTypes[$activityType] ?? "Tipo {$activityType}";

			// Resposta de sucesso
			return $this->jsonResponse([
				'success' => true,
				'message' => "Progresso resetado com sucesso para {$missionTypeName}!",
				'data' => [
					'activity_type' => $activityType,
					'mission_type_name' => $missionTypeName,
					'records_deleted' => $result['records_deleted'],
					'records_found_before' => $result['records_found'],
					'timestamp' => date('Y-m-d H:i:s'),
					'database_used' => 'db_tank'
				]
			]);

		} catch (\Exception $e) {
			return $this->jsonResponse([
				'success' => false,
				'message' => 'Erro interno: ' . $e->getMessage(),
				'debug' => [
					'file' => basename($e->getFile()),
					'line' => $e->getLine(),
					'activity_type' => $activityType ?? 'null'
				]
			], 500);
		}
	}

	/**
	 * FUNÇÃO PARA VERIFICAR QUANTOS REGISTROS EXISTEM POR TIPO - SIMPLIFICADA
	 */
	public function checkProgress()
	{
		try {
			// Usar o novo model para buscar stats
			$result = SysUsersEventProcess::getProgressStats();
			
			if (!$result['success']) {
				return $this->jsonResponse([
					'success' => false,
					'message' => $result['message']
				], 500);
			}

			// Adicionar nomes das missões
			$missionTypes = [
				1 => "Recompensas por Aumentar de nível",
				2 => "Vitória no PvP", 
				3 => "Consumir Cupons - Acumulado",
				4 => "Força de combate",
				5 => "Recarga de cupons - Acumulado",
				6 => "Recarga de cupons - Diária",
				7 => "Vitória Gvg",
				8 => "Tempo Online",
				9 => "Consumir Cupons - Diária"
			];
			
			$progressData = [];
			foreach ($result['progress_by_type'] as $row) {
				$activityType = $row['ActiveType'];
				$progressData[] = [
					'activity_type' => $activityType,
					'mission_name' => $missionTypes[$activityType] ?? "Tipo {$activityType}",
					'total_records' => (int) $row['total_records'],
					'unique_users' => (int) $row['unique_users']
				];
			}
			
			return $this->jsonResponse([
				'success' => true,
				'message' => 'Progresso verificado com sucesso',
				'data' => [
					'progress_by_type' => $progressData,
					'grand_total' => $result['grand_total'],
					'timestamp' => $result['timestamp'],
					'database_used' => 'db_tank'
				]
			]);
			
		} catch (\Exception $e) {
			return $this->jsonResponse([
				'success' => false,
				'message' => 'Erro: ' . $e->getMessage()
			], 500);
		}
	}
    /**
     * FUNÇÃO PARA VERIFICAR QUANTOS REGISTROS EXISTEM POR TIPO
     */
    /**
     *  LISTAR RECOMPENSAS DA MISSÃO
     */
    public function getItems($activityType, $subActivityType)
    {
        try {
            $rewards = EventRewardGoods::getRewardsByMission($activityType, $subActivityType);
            
            $rewardsWithIcons = array_map(function ($reward) {
                $shopGoods = new ShopGoods('db_tank');
                $shopItem = $shopGoods->where('TemplateID', $reward['TemplateId'])->first();
                
                $iconUrl = '/assets/media/svg/files/blank-image.svg';
                $itemName = "Item {$reward['TemplateId']}";
                
                if ($shopItem) {
                    $iconUrl = $shopItem->image();
                    $itemName = $shopItem->Name ?: "Item {$reward['TemplateId']}";
                }
                
                $reward['ItemName'] = $itemName;
                $reward['ItemNum'] = $reward['Count'];
                $reward['Icon'] = $iconUrl;
                
                return $reward;
            }, $rewards);

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'rewards' => $rewardsWithIcons,
                    'total_rewards' => count($rewardsWithIcons),
                    'activity_type' => $activityType,
                    'sub_activity_type' => $subActivityType
                ]
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  ADICIONAR RECOMPENSA
     */
    public function addItem($activityType, $subActivityType)
    {
        try {
            $data = $_POST;
            
            if (empty($data['template_id']) || !is_numeric($data['template_id'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'template_id é obrigatório e deve ser numérico'
                ], 422);
            }

            $templateId = (int) $data['template_id'];

            if (EventRewardGoods::rewardExists($activityType, $subActivityType, $templateId)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Recompensa já existe para esta missão'
                ], 409);
            }

            $shopGoods = new ShopGoods('db_tank');
            $shopItem = $shopGoods->select('TemplateID', 'Name', 'CategoryID', 'Pic', 'NeedSex')
                                 ->where('TemplateID', $templateId)
                                 ->first();

            if (!$shopItem) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Item não encontrado no ShopGoods'
                ], 404);
            }

            $rewardData = [
                'ActivityType' => $activityType,
                'SubActivityType' => $subActivityType,
                'TemplateId' => $templateId,
                'Count' => isset($data['count']) ? (int) $data['count'] : 1,
                'ValidDate' => isset($data['validity']) ? (int) $data['validity'] : 0,
                'StrengthLevel' => isset($data['strength_level']) ? (int) $data['strength_level'] : 0,
                'AttackCompose' => isset($data['attack_compose']) ? (int) $data['attack_compose'] : 0,
                'DefendCompose' => isset($data['defend_compose']) ? (int) $data['defend_compose'] : 0,
                'LuckCompose' => isset($data['luck_compose']) ? (int) $data['luck_compose'] : 0,
                'AgilityCompose' => isset($data['agility_compose']) ? (int) $data['agility_compose'] : 0,
                'IsBind' => isset($data['is_bind']) ? (int) $data['is_bind'] : 1
            ];

            $reward = EventRewardGoods::createReward($rewardData);

            if (!$reward) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao salvar recompensa'
                ], 500);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Recompensa adicionada com sucesso!',
                'data' => $rewardData
            ], 201);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  ATUALIZAR RECOMPENSA
     */
    public function testUpdateItem($activityType, $subActivityType, $templateId)
    {
        try {
            $activityType = (int) $activityType;
            $subActivityType = (int) $subActivityType;
            $templateId = (int) $templateId;
            
            $updateData = [];
            
            if (isset($_POST['count']) && is_numeric($_POST['count'])) {
                $updateData['Count'] = max(1, (int) $_POST['count']);
            }
            if (isset($_POST['validity']) && is_numeric($_POST['validity'])) {
                $updateData['ValidDate'] = (int) $_POST['validity'];
            }
            if (isset($_POST['strength_level']) && is_numeric($_POST['strength_level'])) {
                $updateData['StrengthLevel'] = (int) $_POST['strength_level'];
            }
            if (isset($_POST['attack_compose']) && is_numeric($_POST['attack_compose'])) {
                $updateData['AttackCompose'] = (int) $_POST['attack_compose'];
            }
            if (isset($_POST['defend_compose']) && is_numeric($_POST['defend_compose'])) {
                $updateData['DefendCompose'] = (int) $_POST['defend_compose'];
            }
            if (isset($_POST['luck_compose']) && is_numeric($_POST['luck_compose'])) {
                $updateData['LuckCompose'] = (int) $_POST['luck_compose'];
            }
            if (isset($_POST['agility_compose']) && is_numeric($_POST['agility_compose'])) {
                $updateData['AgilityCompose'] = (int) $_POST['agility_compose'];
            }
            if (isset($_POST['is_bind'])) {
                $updateData['IsBind'] = ($_POST['is_bind'] == '1') ? 1 : 0;
            }
            
            if (empty($updateData)) {
                $updateData['Count'] = 1;
            }
            
            $updated = EventRewardGoods::where('ActivityType', $activityType)
                                      ->where('SubActivityType', $subActivityType)
                                      ->where('TemplateId', $templateId)
                                      ->update($updateData);
            
            if ($updated > 0) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Recompensa atualizada com sucesso!',
                    'data' => ['rows_affected' => $updated]
                ]);
            } else {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nenhuma linha foi atualizada.'
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  DELETAR RECOMPENSA
     */
    public function testDeleteItem($activityType, $subActivityType, $templateId)
    {
        try {
            $activityType = (int) $activityType;
            $subActivityType = (int) $subActivityType;
            $templateId = (int) $templateId;
            
            $deleted = EventRewardGoods::where('ActivityType', $activityType)
                                      ->where('SubActivityType', $subActivityType)
                                      ->where('TemplateId', $templateId)
                                      ->delete();
            
            if ($deleted > 0) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Recompensa deletada com sucesso!',
                    'data' => ['rows_affected' => $deleted]
                ]);
            } else {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nenhuma linha foi deletada.'
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    //  UTILITÁRIOS

    /**
     *  BUSCAR ITENS NO SHOPGOODS
     */
    public function searchItems()
    {
        try {
            $search = $_GET['search'] ?? '';
            
            if (empty($search) || strlen($search) < 2) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Digite pelo menos 2 caracteres para buscar'
                ], 400);
            }

            $shopGoods = new ShopGoods('db_tank');
            
            $items = $shopGoods->select('TemplateID', 'Name', 'CategoryID', 'Pic', 'NeedSex')
                              ->where('Name', 'like', '%' . $search . '%')
                              ->orWhere('TemplateID', 'like', '%' . $search . '%')
                              ->limit(20)
                              ->get();

            $results = $items->map(function ($item) {
                $sexIcon = '';
                if ($item->NeedSex == "1") {
                    $sexIcon = ' 👨';
                } else if ($item->NeedSex == "2") {
                    $sexIcon = ' 👩';
                }

                return [
                    'TemplateID' => $item->TemplateID,
                    'Name' => $item->Name . $sexIcon,
                    'NeedSex' => $item->NeedSex,
                    'Icon' => $item->image()
                ];
            });

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'items' => $results->toArray(),
                    'total' => $results->count(),
                    'search_term' => $search
                ]
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *  INFORMAÇÕES DO ITEM SHOPGOODS
     */
    public function getShopItemInfo()
    {
        try {
            $templateId = $_GET['template_id'] ?? '';
            
            if (empty($templateId)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'template_id não fornecido'
                ], 400);
            }

            $shopGoods = new ShopGoods('db_tank');
            
            $item = $shopGoods->select('TemplateID', 'Name', 'CategoryID', 'Pic', 'NeedSex')
                              ->where('TemplateID', $templateId)
                              ->first();

            if (!$item) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Item não encontrado'
                ], 404);
            }

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'TemplateID' => $item->TemplateID,
                    'Name' => $item->Name,
                    'CategoryID' => $item->CategoryID,
                    'NeedSex' => $item->NeedSex,
                    'Icon' => $item->image()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // MÉTODOS AUXILIARES

    /**
     * BUSCAR MISSÕES DB_TANK
     */
    private function getMissionsFromDbTank()
    {
        try {
            $modelResults = EventRewardInfo::getAllMissions();
            if (!empty($modelResults)) {
                return $modelResults;
            }
        } catch (\Exception $e) {
            // Se falhar, usar dados de fallback
        }
        
        return $this->getFallbackMissions();
    }

     // DADOS FALLBACK PARA TESTES
     
    private function getFallbackMissions()
    {
        return [
            ['ActivityType' => 1, 'SubActivityType' => 1, 'Condition' => 5],
            ['ActivityType' => 1, 'SubActivityType' => 2, 'Condition' => 10],
            ['ActivityType' => 1, 'SubActivityType' => 3, 'Condition' => 15],
            ['ActivityType' => 2, 'SubActivityType' => 1, 'Condition' => 3],
            ['ActivityType' => 2, 'SubActivityType' => 2, 'Condition' => 5],
            ['ActivityType' => 3, 'SubActivityType' => 1, 'Condition' => 100],
            ['ActivityType' => 4, 'SubActivityType' => 1, 'Condition' => 1000]
        ];
    }

     //RESPOSTA JSON
     
    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}