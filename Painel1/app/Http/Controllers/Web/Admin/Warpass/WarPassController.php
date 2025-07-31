<?php

namespace App\Http\Controllers\Web\Admin\Warpass;

use App\Http\Controllers\Web\Controller;
use App\Models\Warpass\PassTemplate;
use App\Models\ShopGoods;

class WarpassController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        try {
            $levels = PassTemplate::getAllLevels();
            $stats = [
                'total_levels' => count($levels),
                'max_level' => count($levels) > 0 ? max(array_column($levels, 'Level')) : 0,
                'with_normal_rewards' => 0,
                'with_extra_rewards' => 0
            ];
            
            foreach ($levels as $level) {
                if (!$this->isEmptyReward($level['NormalAward'])) {
                    $stats['with_normal_rewards']++;
                }
                if (!$this->isEmptyReward($level['ExtraAward'])) {
                    $stats['with_extra_rewards']++;
                }
            }
            
            return $this->view->render('admin.warpass.index', [
                'levels' => $levels,
                'stats' => $stats,
                'page_title' => 'War Pass - Gerenciar Níveis',
                'page_description' => 'ADM do WarPass'
            ]);
        } catch (\Exception $e) {
            error_log('Erro ao carregar War Pass: ' . $e->getMessage());
            return $this->view->render('admin.warpass.index', [
                'levels' => [],
                'stats' => ['total_levels' => 0, 'max_level' => 0, 'with_normal_rewards' => 0, 'with_extra_rewards' => 0],
                'error' => 'Erro ao carregar dados: ' . $e->getMessage(),
                'page_title' => 'War Pass - Erro',
                'page_description' => 'Erro ao carregar administração do War Pass'
            ]);
        }
    }

    /**
     * Listarb níveis
     */
    public function getData()
    {
        try {
            $levels = PassTemplate::getAllLevels();
            $processedLevels = [];
            
            foreach ($levels as $level) {
                $normalRewards = $this->parseRewards($level['NormalAward']);
                $extraRewards = $this->parseRewards($level['ExtraAward']);
                
                $processedLevels[] = [
                    'Level' => $level['Level'],
                    'NormalAward' => $level['NormalAward'],
                    'ExtraAward' => $level['ExtraAward'],
                    'normal_rewards' => $normalRewards,
                    'extra_rewards' => $extraRewards,
                    'has_normal_rewards' => !$this->isEmptyReward($level['NormalAward']),
                    'has_extra_rewards' => !$this->isEmptyReward($level['ExtraAward']),
                    'normal_rewards_count' => count($normalRewards),
                    'extra_rewards_count' => count($extraRewards)
                ];
            }
            
            return $this->jsonResponse([
                'success' => true,
                'data' => $processedLevels,
                'total' => count($processedLevels)
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao carregar níveis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar nível
     */
    public function show($level)
    {
        try {
            $passLevel = PassTemplate::find($level);
            
            if (!$passLevel) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nível não encontrado'
                ], 404);
            }
            
            $normalRewards = $this->parseRewards($passLevel->NormalAward);
            $extraRewards = $this->parseRewards($passLevel->ExtraAward);
            
            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'Level' => $passLevel->Level,
                    'NormalAward' => $passLevel->NormalAward,
                    'ExtraAward' => $passLevel->ExtraAward,
                    'normal_rewards' => $normalRewards,
                    'extra_rewards' => $extraRewards,
                    'has_normal_rewards' => !$this->isEmptyReward($passLevel->NormalAward),
                    'has_extra_rewards' => !$this->isEmptyReward($passLevel->ExtraAward)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao buscar nível: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar novo nível
     */
    public function store()
    {
        try {
            $data = $_POST;
            
            // Validação
            if (empty($data['Level']) || trim($data['Level']) === '') {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Level é obrigatório'
                ], 422);
            }
            
            $level = (int) $data['Level'];
            
            // Verificar se já existe
            if (PassTemplate::find($level)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => "Nível {$level} já existe"
                ], 422);
            }
            
            // Montar recompensas
            $normalAward = $this->buildRewardString([
                'template' => (int) ($data['normal_template'] ?? 0),
                'count' => (int) ($data['normal_count'] ?? 0),
                'days' => (int) ($data['normal_days'] ?? 0)
            ]);

            $extraAward = $this->buildRewardString([
                'template' => (int) ($data['extra_slot1_template'] ?? 0),
                'count' => (int) ($data['extra_slot1_count'] ?? 0),
                'days' => (int) ($data['extra_slot1_days'] ?? 0)
            ]) . '|' . $this->buildRewardString([
                'template' => (int) ($data['extra_slot2_template'] ?? 0),
                'count' => (int) ($data['extra_slot2_count'] ?? 0),
                'days' => (int) ($data['extra_slot2_days'] ?? 0)
            ]);
            
            // Criar nível
            $created = PassTemplate::createLevel([
                'Level' => $level,
                'NormalAward' => $normalAward,
                'ExtraAward' => $extraAward
            ]);
            
            if (!$created) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao criar nível'
                ], 500);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Nível criado com sucesso!',
                'data' => [
                    'Level' => $level,
                    'NormalAward' => $normalAward,
                    'ExtraAward' => $extraAward
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao criar nível: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar nível
     */
    public function update($level)
    {
        try {
            $data = $_POST;
            $level = (int) $level;
            
            if (!PassTemplate::find($level)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nível não encontrado'
                ], 404);
            }
            
            $updateData = [];

            // Recompensa normal
            if (isset($data['normal_template'])) {
                $updateData['NormalAward'] = $this->buildRewardString([
                    'template' => (int) ($data['normal_template'] ?? 0),
                    'count' => (int) ($data['normal_count'] ?? 0),
                    'days' => (int) ($data['normal_days'] ?? 0)
                ]);
            }

            // Recompensas vip
            if (isset($data['extra_slot1_template'])) {
                $extraSlot1 = $this->buildRewardString([
                    'template' => (int) ($data['extra_slot1_template'] ?? 0),
                    'count' => (int) ($data['extra_slot1_count'] ?? 0),
                    'days' => (int) ($data['extra_slot1_days'] ?? 0)
                ]);
                
                $extraSlot2 = $this->buildRewardString([
                    'template' => (int) ($data['extra_slot2_template'] ?? 0),
                    'count' => (int) ($data['extra_slot2_count'] ?? 0),
                    'days' => (int) ($data['extra_slot2_days'] ?? 0)
                ]);
                
                $updateData['ExtraAward'] = $extraSlot1 . '|' . $extraSlot2;
            }

            if (empty($updateData)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nenhum dado para atualizar'
                ], 400);
            }
            
            $updated = PassTemplate::updateLevel($level, $updateData);
            
            if (!$updated) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao atualizar nível'
                ], 500);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Nível atualizado com sucesso!',
                'data' => array_merge(['Level' => $level], $updateData)
            ]);
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao atualizar nível: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar nível
     */
    public function destroy($level)
    {
        try {
            $level = (int) $level;
            
            if (!PassTemplate::find($level)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Nível não encontrado'
                ], 404);
            }
            
            $deleted = PassTemplate::deleteLevel($level);
            
            if (!$deleted) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao deletar nível'
                ], 500);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Nível deletado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao deletar nível: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar itens
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
                    'id' => $item->TemplateID,
                    'text' => "[{$item->TemplateID}] {$item->Name}{$sexIcon}",
                    'pic' => $item->image(),
                    'data' => [
                        'TemplateID' => $item->TemplateID,
                        'Name' => $item->Name,
                        'CategoryID' => $item->CategoryID,
                        'NeedSex' => $item->NeedSex,
                        'Icon' => $item->image()
                    ]
                ];
            });

            return $this->jsonResponse([
                'success' => true,
                'items' => $results->toArray(),
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao buscar itens: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Informações de item
     */
    public function getItemInfo()
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
            $item = $shopGoods->where('TemplateID', $templateId)->first();

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
                'message' => 'Erro ao buscar item: ' . $e->getMessage()
            ], 500);
        }
    }

    // MÉTODOS AUXILIARES
    /**
     * Construir string de recompensa
     */
    private function buildRewardString($reward)
    {
        return "{$reward['template']},{$reward['count']},{$reward['days']}";
    }

    /**
     * Parsear string de recompensas
     */
    private function parseRewards($rewardString)
    {
        if (empty($rewardString)) {
            return [];
        }

        $rewards = [];
        $rewardParts = explode('|', $rewardString);
        
        foreach ($rewardParts as $rewardPart) {
            $parts = explode(',', $rewardPart);
            
            if (count($parts) >= 3) {
                $templateId = (int) $parts[0];
                $count = (int) $parts[1];
                $validDate = (int) $parts[2];
                
                $reward = [
                    'TemplateId' => $templateId,
                    'Count' => $count,
                    'ValidDate' => $validDate,
                    'IsEmpty' => ($templateId === 0 && $count === 0)
                ];
                
                if (!$reward['IsEmpty']) {
                    $itemInfo = $this->getItemInfo_($templateId);
                    $reward['ItemName'] = $itemInfo['name'];
                    $reward['Icon'] = $itemInfo['icon'];
                } else {
                    $reward['ItemName'] = 'Slot vazio';
                    $reward['Icon'] = '/assets/media/svg/files/blank-image.svg';
                }
                
                $reward['ValidityText'] = $validDate === 0 ? 'Permanente' : "{$validDate} dias";
                
                $rewards[] = $reward;
            }
        }

        return $rewards;
    }

    /**
     * Buscar informações de ite
     */
    private function getItemInfo_($templateId)
    {
        if ($templateId === 0) {
            return [
                'name' => 'Slot Vazio',
                'icon' => '/assets/media/svg/files/blank-image.svg'
            ];
        }

        try {
            $shopGoods = new ShopGoods('db_tank');
            $shopItem = $shopGoods->where('TemplateID', $templateId)->first();
            
            if ($shopItem) {
                return [
                    'name' => $shopItem->Name,
                    'icon' => $shopItem->image()
                ];
            }
        } catch (\Exception $e) {
        }
        
        return [
            'name' => "Item {$templateId}",
            'icon' => '/assets/media/svg/files/blank-image.svg'
        ];
    }

    private function isEmptyReward($rewardString)
    {
        if (empty($rewardString)) return true;
        
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

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}