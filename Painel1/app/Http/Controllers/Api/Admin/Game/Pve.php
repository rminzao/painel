<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\PveInfo;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Utils\Wsdl;
use Core\View\Paginator;

class Pve extends Api
{
    private function getRealItemsForDifficulties(array $pve, $server): array
    {
        $itemModel = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $difficulties = ['Simple', 'Normal', 'Hard', 'Terror', 'Nightmare', 'Epic'];
        $items = [];

        foreach ($difficulties as $difficulty) {
            $templateField = $difficulty . 'TemplateIds';
            $templateIds = [];
            
            if (!empty($pve[$templateField])) {
                $templateIds = array_filter(array_map('trim', explode(',', $pve[$templateField])));
            }

            try {
                if (!empty($templateIds)) {
                    $itemsData = $itemModel
                        ->whereIn('TemplateID', $templateIds)
                        ->select(['TemplateID', 'Name', 'NeedSex', 'Icon'])
                        ->get();
                    
                    if ($itemsData) {
                        $items[strtolower($difficulty)] = $itemsData->map(function($item) {
                            return [
                                'TemplateID' => $item->TemplateID,
                                'Name' => $item->Name,
                                'NeedSex' => $item->NeedSex,
                                'Image' => $item->Icon ?: '/assets/media/svg/files/blank-image.svg'
                            ];
                        })->toArray();
                    } else {
                        $items[strtolower($difficulty)] = [];
                    }
                } else {
                    $items[strtolower($difficulty)] = [];
                }
            } catch (\Throwable $th) {
                $items[strtolower($difficulty)] = [];
            }
        }

        return $items;
    }

    public function getSuggestedId(): array
    {
        $post = $this->request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');
        $suggestedId = $model->max('ID') + 1;

        return [
            'state' => true,
            'suggestedId' => $suggestedId
        ];
    }

    public function list(): array
    {
        $params = $this->request->get();

        $page = isset($params['page']) ? filter_var($params['page'], FILTER_VALIDATE_INT) : 1;
        $sid = isset($params['sid']) ? filter_var($params['sid'], FILTER_VALIDATE_INT) : null;
        $search = $params['search'] ?? '';
        $limit = isset($params['limit']) ? filter_var($params['limit'], FILTER_VALIDATE_INT) : 10;
        $type = $params['type'] ?? 0;

        if ($page === false || $page < 1) {
            $page = 1;
        }

        if ($limit === false || $limit < 1) {
            $limit = 10;
        }

        if (!$sid) {
            return [
                'state' => false,
                'message' => 'Servidor não informado.'
            ];
        }

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        try {
            $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');
            
            $query = $model->select([
                'ID',
                'Name', 
                'Type',
                'LevelLimits',
                'SimpleTemplateIds',
                'NormalTemplateIds', 
                'HardTemplateIds',
                'TerrorTemplateIds',
                'NightmareTemplateIds',
                'EpicTemplateIds',
                'Pic',
                'Description',
                'SimpleGameScript',
                'NormalGameScript',
                'HardGameScript', 
                'TerrorGameScript',
                'EpicGameScript',
                'Ordering',
                'AdviceTips',
                'BossFightNeedMoney'
            ]);

            if ($search != '') {
                if (filter_var($search, FILTER_VALIDATE_INT)) {
                    $query = $query->where('ID', 'LIKE', "%{$search}%");
                } else {
                    $query = $query->where('Name', 'LIKE', "%{$search}%");
                }
            }

            if ($type != 0 && $type != 'all') {
                $query = $query->where('Type', $type);
            }

            $query = $query->orderBy('Type', 'ASC')->orderBy('Ordering', 'ASC');

            $totalCount = $query->count();

            $pager = new Paginator(url($this->request->getUri()), onclick: "pve.list");
            $pager->pager($totalCount, $limit, $page, 1);

            $items = $query->limit($pager->limit())->offset($pager->offset())->get();
            
            if (!$items) {
                $items = [];
            } else {
                $items = $items->toArray();
            }

            $pveList = array_map(function ($item) use ($server) {
                $item['ImageDefault'] = $server->resource . '/image/map/0/samll_map.png';
                $item['Image'] = $server->resource . '/image/map/' . $item['ID'] . '/samll_map.png';
                
                $item['Items'] = $this->getRealItemsForDifficulties($item, $server);
                $item['Difficulties'] = $this->getDifficultyInfo($item);
                $item['CostArray'] = $this->parseBossFightCosts($item['BossFightNeedMoney'] ?? '');
                
                $item['Type'] = $item['Type'] ?? 1;
                
                if (!isset($item['NightmareGameScript'])) {
                    $item['NightmareGameScript'] = null;
                }
                
                return $item;
            }, $items);

            return [
                'state' => true,
                'items' => $pveList,
                'paginator' => [
                    'total' => $pager->pages(),
                    'current' => $pager->page(),
                    'rendered' => $pager->render()
                ]
            ];

        } catch (\Throwable $e) {
            return [
                'state' => false,
                'message' => 'Erro ao buscar PVEs: ' . $e->getMessage()
            ];
        }
    }

    public function create(): array
    {
        $post = $this->request->post(false);

        $required = ['sid', 'name', 'type', 'levelLimits'];
        foreach ($required as $field) {
            if (empty($post[$field])) {
                return [
                    'state' => false,
                    'message' => 'Preencha todos os campos obrigatórios.'
                ];
            }
        }

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');

        $pveId = $post['id'] ?? null;
        if (!$pveId || $pveId == 0) {
            $pveId = $model->max('ID') + 1;
        } else {
            if ($model->find($pveId)) {
                return [
                    'state' => false,
                    'message' => 'ID ' . $pveId . ' já está em uso. ID sugerido: ' . ($model->max('ID') + 1)
                ];
            }
        }
        $nextOrdering = $model->where('Type', $post['type'])->max('Ordering') + 1;

        $costs = $post['costs'] ?? ['100', '100', '100', '100', '100', '100'];
        $costString = implode('|', $costs);

        $data = [
            'ID' => $pveId,
            'Name' => $post['name'],
            'Type' => $post['type'],
            'LevelLimits' => $post['levelLimits'],
            'Ordering' => $nextOrdering,
            'Description' => $post['description'] ?? '',
            'AdviceTips' => $post['adviceTips'] ?? '',
            'BossFightNeedMoney' => $costString,
            'Pic' => $post['pic'] ?? '1072',
        ];

        $scriptsAvailable = ['Simple', 'Normal', 'Hard', 'Terror', 'Epic'];
        foreach ($scriptsAvailable as $difficulty) {
            $scriptField = $difficulty . 'GameScript';
            $scriptKey = strtolower($difficulty) . 'Script';
            
            if (!empty($post[$scriptKey])) {
                $data[$scriptField] = 'GameServerScript.AI.Game.' . $post[$scriptKey];
            }
        }

        if (!$model->insert($data)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar PVE, verifique os dados e tente novamente.'
            ];
        }

        return [
            'state' => true,
            'message' => 'PVE criado com sucesso.',
            'pveId' => $pveId
        ];
    }

    public function update(): array
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $pveId = filter_var($post['pveId'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');
        $pve = $model->where('ID', $pveId);
        
        $currentPve = $pve->first();
        if (!$currentPve) {
            return [
                'state' => false,
                'message' => 'PVE não encontrado.'
            ];
        }

        $updateData = [];
        
        if (isset($post['Name'])) {
            $updateData['Name'] = $post['Name'];
        }
        
        if (isset($post['Type'])) {
            $updateData['Type'] = $post['Type'];
        }
        
        if (isset($post['LevelLimits'])) {
            $updateData['LevelLimits'] = $post['LevelLimits'];
        }
        
        if (isset($post['Description'])) {
            $updateData['Description'] = $post['Description'];
        }
        
        if (isset($post['AdviceTips'])) {
            $updateData['AdviceTips'] = $post['AdviceTips'];
        }
        
        if (isset($post['Pic'])) {
            $updateData['Pic'] = $post['Pic'];
        }
        
        if (isset($post['Ordering'])) {
            $updateData['Ordering'] = $post['Ordering'];
        }

        if (isset($post['costs'])) {
            $updateData['BossFightNeedMoney'] = implode('|', $post['costs']);
        }

        $scriptsAvailable = ['Simple', 'Normal', 'Hard', 'Terror', 'Epic'];
        foreach ($scriptsAvailable as $difficulty) {
            $scriptField = $difficulty . 'GameScript';
            
            if (isset($post[$scriptField])) {
                $scriptValue = $post[$scriptField];
                $updateData[$scriptField] = !empty($scriptValue) 
                    ? (strpos($scriptValue, 'GameServerScript.AI.Game.') === 0 
                       ? $scriptValue 
                       : 'GameServerScript.AI.Game.' . $scriptValue)
                    : null;
            }
        }

        $difficulties = ['Simple', 'Normal', 'Hard', 'Terror', 'Nightmare', 'Epic'];
        foreach ($difficulties as $difficulty) {
            $templateField = $difficulty . 'TemplateIds';
            
            if (isset($post[$templateField])) {
                $templateValue = $post[$templateField];
                $updateData[$templateField] = is_array($templateValue) 
                    ? implode(',', array_filter($templateValue))
                    : $templateValue;
            }
        }

        if (empty($updateData)) {
            return [
                'state' => false,
                'message' => 'Nenhum dado foi enviado para atualização.'
            ];
        }

        try {
            $result = $pve->update($updateData);
            
            if (!$result) {
                return [
                    'state' => false,
                    'message' => 'Falha ao atualizar PVE, verifique os dados e tente novamente.'
                ];
            }

            return [
                'state' => true,
                'message' => 'PVE atualizado com sucesso.'
            ];
            
        } catch (\Throwable $e) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar PVE: ' . $e->getMessage()
            ];
        }
    }

    public function delete(): array
    {
        $post = $this->request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $pveId = filter_var($post['pveId'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');
        $pve = $model->where('ID', $pveId);
        
        if (!$pve->first()) {
            return [
                'state' => false,
                'message' => 'PVE não encontrado.'
            ];
        }

        if (!$pve->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao deletar PVE, verifique os dados e tente novamente.'
            ];
        }

        log_system(
            $this->user->id,
            "Removeu PVE [<b>{$pveId}</b>]",
            $this->request->getUri()
        );

        return [
            'state' => true,
            'message' => 'PVE removido com sucesso.'
        ];
    }

    public function updateOrdering(): array
    {
        $post = $this->request->post(false);

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $type = filter_var($post['type'], FILTER_VALIDATE_INT);
        $orderedIds = $post['orderedIds'] ?? [];

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new PveInfo())->setTable($server->dbData . '.dbo.Pve_Info');

        foreach ($orderedIds as $index => $pveId) {
            $model->where('ID', $pveId)
                  ->where('Type', $type)
                  ->update(['Ordering' => $index + 1]);
        }

        return [
            'state' => true,
            'message' => 'Ordenação atualizada com sucesso.'
        ];
    }

    public function getAvailableItems(): array
    {
        $post = $this->request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $search = $post['search'] ?? '';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $query = $model->select(['TemplateID', 'Name', 'NeedSex', 'Icon']);

        if (!empty($search)) {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('TemplateID', 'LIKE', "%{$search}%") :
                $query->where('Name', 'LIKE', "%{$search}%");
        }

        $items = $query->limit(50)->get()?->toArray();

        $formattedItems = array_map(function ($item) {
            return [
                'id' => $item['TemplateID'],
                'name' => $item['Name'],
                'sex' => $item['NeedSex'],
                'pic' => $item['Icon'] ?: '/assets/media/svg/files/blank-image.svg'
            ];
        }, $items);

        return [
            'state' => true,
            'items' => $formattedItems
        ];
    }

    public function updateOnGame(): array
    {
        try {
            $post = $this->request->get();
            $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

            if (!$sid) {
                return [
                    'state' => false,
                    'message' => 'Servidor não informado.'
                ];
            }

            $server = Server::find($sid);
            if (!$server) {
                return [
                    'state' => false,
                    'message' => 'Servidor informado, não foi encontrado.'
                ];
            }

            $wsdl = new Wsdl();
            
            if (defined('Core\Utils\Wsdl::MAP')) {
                $result = $wsdl->reload(Wsdl::MAP, $server);
            } 
            elseif (defined('Core\Utils\Wsdl::SERVER_CONFIG')) {
                $result = $wsdl->reload(Wsdl::SERVER_CONFIG, $server);
            }
            elseif (defined('Core\Utils\Wsdl::MAP_SERVER')) {
                $result = $wsdl->reload(Wsdl::MAP_SERVER, $server);
            }
            else {
                return [
                    'state' => false,
                    'message' => 'Sistema de reload não suporta PVE. Use reload manual do servidor.'
                ];
            }

            if ($result === false || (is_array($result) && isset($result['success']) && !$result['success'])) {
                return [
                    'state' => false,
                    'message' => 'Falha na comunicação com o servidor do jogo.'
                ];
            }

            return [
                'state' => true,
                'message' => 'PVEs atualizados no jogo com sucesso.'
            ];

        } catch (\Exception $e) {
            return [
                'state' => false,
                'message' => 'Erro na atualização do jogo: ' . $e->getMessage()
            ];
        }
    }

    public function getTypes(): array
    {
        return [
            'state' => true,
            'types' => [
                1 => 'Tipo 1',
                2 => 'Tipo 2', 
                3 => 'Tipo 3',
                4 => 'Tipo 4',
                5 => 'Tipo 5',
            ]
        ];
    }

    private function getDifficultyInfo(array $pve): array
    {
        $difficulties = ['Simple', 'Normal', 'Hard', 'Terror', 'Nightmare', 'Epic'];
        $info = [];

        foreach ($difficulties as $difficulty) {
            $templateField = $difficulty . 'TemplateIds';
            $scriptField = $difficulty . 'GameScript';
            
            $templateIds = !empty($pve[$templateField]) ? 
                explode(',', $pve[$templateField]) : [];
            
            $scriptValue = '';
            if ($difficulty === 'Nightmare') {
                $scriptValue = '';
            } else {
                $scriptValue = $pve[$scriptField] ?? '';
            }
            
            $info[strtolower($difficulty)] = [
                'hasTemplates' => !empty($templateIds),
                'templateCount' => count($templateIds),
                'hasScript' => !empty($scriptValue),
                'script' => $scriptValue
            ];
        }

        return $info;
    }

    private function parseBossFightCosts(string $costs): array
    {
        $costArray = explode('|', $costs);
        
        return [
            'simple' => $costArray[0] ?? '0',
            'normal' => $costArray[1] ?? '0',
            'hard' => $costArray[2] ?? '0', 
            'terror' => $costArray[3] ?? '0',
            'nightmare' => $costArray[4] ?? '0',
            'epic' => $costArray[5] ?? '0',
        ];
    }
}