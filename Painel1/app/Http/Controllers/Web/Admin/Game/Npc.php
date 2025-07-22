<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\NpcInfo;
use Core\Database;

class Npc extends Controller
{
    public function index()
    {
        try {
            $npcs = NpcInfo::getAllNpcs();
            
            return $this->view->render('admin.game.npc.index', [
                'npcs' => $npcs,
                'total_npcs' => count($npcs ?? [])
            ]);
        } catch (\Exception $e) {
            return $this->view->render('admin.game.npc.index', [
                'npcs' => [],
                'total_npcs' => 0,
                'error_message' => 'Erro ao carregar a lista: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $npc = $this->findNpcById($id);
            
            if (!$npc) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'NPC não encontrado'
                ], 404);
            }
            
            $responseData = [
                'success' => true,
                'data' => [
                    'ID' => $npc->ID,
                    'Name' => $npc->Name,
                    'Level' => $npc->Level,
                    'Type' => $npc->Type,
                    'Attack' => $npc->Attack,
                    'Defence' => $npc->Defence,
                    'MagicAttack' => $npc->MagicAttack,
                    'MagicDefence' => $npc->MagicDefence,
                    'BaseDamage' => $npc->BaseDamage,
                    'BaseGuard' => $npc->BaseGuard,
                    'Blood' => $npc->Blood,
                    'Agility' => $npc->Agility,
                    'Lucky' => $npc->Lucky,
                    'MoveMin' => $npc->MoveMin,
                    'MoveMax' => $npc->MoveMax,
                    'speed' => $npc->speed,
                    'Camp' => $npc->Camp
                ]
            ];
            
            return $this->jsonResponse($responseData);
            
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store()
    {
        try {
            $isAjax = $this->isAjaxRequest();
            $data = $_POST;
            
            // Validações básicas
            $validation = $this->validateNpcData($data);
            if (!$validation['valid']) {
                return $this->handleError($validation['message'], $isAjax, 422);
            }
            
            // Verificar se ID já existe
            $exists = $this->findNpcById($data['ID']);
            if ($exists) {
                $errorMsg = "ID {$data['ID']} já existe";
                return $this->handleError($errorMsg, $isAjax, 422);
            }
            
            // Preparar dados com valores padrão seguros
            $npcData = $this->prepareNpcData($data);
            
            // Salvar NPC
            $saved = $this->createNpc($npcData);
            
            if (!$saved) {
                $errorMsg = 'Falha ao salvar no banco de dados';
                return $this->handleError($errorMsg, $isAjax, 500);
            }
            
            $successMsg = 'NPC criado com sucesso!';
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => $successMsg,
                    'data' => $npcData
                ], 201);
            }
            
            return redirect('/admin/game/npc')->with('success', $successMsg);
            
        } catch (\Exception $e) {
            $errorMsg = 'Erro ao criar NPC: ' . $e->getMessage();
            return $this->handleError($errorMsg, $this->isAjaxRequest(), 500);
        }
    }

    public function updateDetails($id)
    {
        try {
            $isAjax = $this->isAjaxRequest();
            $data = $_POST;
            
            // Verificar se NPC existe
            $npc = $this->findNpcById($id);
            if (!$npc) {
                return $this->handleError('NPC não encontrado.', $isAjax, 404);
            }
            
            // Validação
            if (empty($data['name']) || trim($data['name']) === '') {
                return $this->handleError('Nome é obrigatório', $isAjax, 400);
            }
            
            // Dados para atualização
            $updateData = [
                'Name'       => trim($data['name']),
                'Level'      => isset($data['level']) ? max(1, (int) $data['level']) : 1,
                'Type'       => isset($data['type']) ? max(0, (int) $data['type']) : 0,
                'Blood'      => isset($data['blood']) ? max(1, (int) $data['blood']) : 10,
            ];
            
            // Atualizar
            $updated = $this->updateNpc($id, $updateData);
            
            if (!$updated) {
                return $this->handleError('Falha ao salvar no banco de dados', $isAjax, 500);
            }
            
            $successResponse = [
                'success' => true,
                'message' => 'Detalhes do NPC atualizados com sucesso!',
                'data' => array_merge(['ID' => $id], $updateData)
            ];
            
            if ($isAjax) {
                return $this->jsonResponse($successResponse, 200);
            }
            
            return redirect('/admin/game/npc')->with('success', 'NPC atualizado com sucesso!');
            
        } catch (\Exception $e) {
            return $this->handleError('Erro interno: ' . $e->getMessage(), $this->isAjaxRequest(), 500);
        }
    }

    public function updateAttributes($id)
    {
        try {
            $isAjax = $this->isAjaxRequest();
            $data = $_POST;
            
            // Verificar se NPC existe
            $npc = $this->findNpcById($id);
            if (!$npc) {
                return $this->handleError('NPC não encontrado.', $isAjax, 404);
            }
            
            // Dados para atualização com validação
            $updateData = [
                'Attack'       => isset($data['attack']) ? max(0, (int) $data['attack']) : 0,
                'Defence'      => isset($data['defence']) ? max(0, (int) $data['defence']) : 0,
                'MagicAttack'  => isset($data['magicattack']) ? max(0, (int) $data['magicattack']) : 0,
                'MagicDefence' => isset($data['magicdefence']) ? max(0, (int) $data['magicdefence']) : 0,
                'BaseDamage'   => isset($data['basedamage']) ? max(0, (int) $data['basedamage']) : 0,
                'BaseGuard'    => isset($data['baseguard']) ? max(0, (int) $data['baseguard']) : 0,
                'Agility'      => isset($data['agility']) ? max(0, (int) $data['agility']) : 0,
                'Lucky'        => isset($data['lucky']) ? max(0, (int) $data['lucky']) : 0,
                'MoveMin'      => isset($data['movemin']) ? max(0, (int) $data['movemin']) : 0,
                'MoveMax'      => isset($data['movemax']) ? max(0, (int) $data['movemax']) : 0,
                'speed'        => isset($data['speed']) ? max(0, (int) $data['speed']) : 0,
            ];
            
            // Atualizar
            $updated = $this->updateNpc($id, $updateData);
            
            if (!$updated) {
                return $this->handleError('Falha ao salvar no banco de dados', $isAjax, 500);
            }
            
            $successResponse = [
                'success' => true,
                'message' => 'Atributos do NPC atualizados com sucesso!',
                'data' => array_merge(['ID' => $id], $updateData)
            ];
            
            if ($isAjax) {
                return $this->jsonResponse($successResponse, 200);
            }
            
            return redirect('/admin/game/npc')->with('success', 'Atributos atualizados com sucesso!');
            
        } catch (\Exception $e) {
            return $this->handleError('Erro interno: ' . $e->getMessage(), $this->isAjaxRequest(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $isAjax = $this->isAjaxRequest();
            
            $npc = $this->findNpcById($id);
            
            if (!$npc) {
                return $this->handleError('NPC não encontrado', $isAjax, 404);
            }
            
            $npcName = $npc->Name ?? "NPC #{$id}";
            $deleted = $this->deleteNpc($id);
            
            if (!$deleted) {
                return $this->handleError('Falha ao deletar NPC', $isAjax, 500);
            }
            
            $successMsg = "NPC \"{$npcName}\" deletado com sucesso!";
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => $successMsg
                ]);
            }
            
            return redirect('/admin/game/npc')->with('success', $successMsg);
            
        } catch (\Exception $e) {
            return $this->handleError('Erro ao deletar NPC: ' . $e->getMessage(), $this->isAjaxRequest(), 500);
        }
    }

    /**
     * Calculadora automática de atributos baseada nos jogadores mais fortes
     */
    public function calculateAttributes()
    {
        try {
            $difficulty = $_GET['difficulty'] ?? 'médio';
            
            // Definir multiplicadores de dificuldade
            $multipliers = [
                'fácil' => 0.6,
                'médio' => 0.9,
                'difícil' => 1.1,
                'insano' => 1.3
            ];
            
            $multiplier = $multipliers[$difficulty] ?? 0.9;
            
            // Buscar os 10 jogadores mais fortes
            $topPlayers = $this->getTopPlayers();
            
            if (count($topPlayers) < 4) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Não há jogadores suficientes no servidor para calcular atributos (mínimo 4 jogadores).'
                ], 400);
            }
            
            // Selecionar 4 jogadores intermediários (posições 3-6, ou 2-5 em array 0-indexed)
            $selectedPlayers = array_slice($topPlayers, 2, 4);
            
            if (count($selectedPlayers) < 4) {
                // Se não temos 4 na posição 3-6, pegar os primeiros 4
                $selectedPlayers = array_slice($topPlayers, 0, 4);
            }
            
            // Calcular médias dos atributos
            $attributes = $this->calculateAverageAttributes($selectedPlayers, $multiplier);
            
            // Buscar maior HP para calcular Blood
            $maxHp = $this->getMaxHpFromAllPlayers();
            $baseBlood = max(100, $maxHp * 10); // Mínimo 100
            $attributes['Blood'] = max(100, round($baseBlood * $multiplier));
            
            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'attributes' => $attributes,
                    'difficulty' => $difficulty,
                    'multiplier' => $multiplier,
                    'playersUsed' => count($selectedPlayers),
                    'maxHp' => $maxHp,
                    'baseBlood' => $baseBlood
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
     * Buscar os 10 jogadores mais fortes baseado na soma dos atributos
     */
    private function getTopPlayers()
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]);

            $sql = "
                SELECT TOP 10 
                    ISNULL(Attack, 0) as Attack, 
                    ISNULL(Defence, 0) as Defence, 
                    ISNULL(Luck, 0) as Luck, 
                    ISNULL(Agility, 0) as Agility, 
                    ISNULL(MagicAttack, 0) as MagicAttack, 
                    ISNULL(MagicDefence, 0) as MagicDefence, 
                    ISNULL(hp, 100) as hp,
                    (ISNULL(Attack, 0) + ISNULL(Defence, 0) + ISNULL(Luck, 0) + ISNULL(Agility, 0) + ISNULL(MagicAttack, 0) + ISNULL(MagicDefence, 0) + ISNULL(hp, 100)) as TotalPower
                FROM Sys_Users_Fight 
                WHERE (Attack > 0 OR Defence > 0) AND hp > 0
                ORDER BY TotalPower DESC
            ";
            
            $stmt = $pdo->query($sql);
            $players = $stmt->fetchAll();
            
            return $players ?: [];
            
        } catch (\Exception $e) {
            // Retornar dados mock se não conseguir acessar o banco
            return [
                (object)['Attack' => 100, 'Defence' => 100, 'Luck' => 50, 'Agility' => 50, 'MagicAttack' => 75, 'MagicDefence' => 75, 'hp' => 1000],
                (object)['Attack' => 90, 'Defence' => 90, 'Luck' => 45, 'Agility' => 45, 'MagicAttack' => 70, 'MagicDefence' => 70, 'hp' => 900],
                (object)['Attack' => 80, 'Defence' => 80, 'Luck' => 40, 'Agility' => 40, 'MagicAttack' => 65, 'MagicDefence' => 65, 'hp' => 800],
                (object)['Attack' => 70, 'Defence' => 70, 'Luck' => 35, 'Agility' => 35, 'MagicAttack' => 60, 'MagicDefence' => 60, 'hp' => 700],
            ];
        }
    }

    /**
     * Calcular médias dos atributos dos jogadores selecionados
     */
    private function calculateAverageAttributes($players, $multiplier)
    {
        $totals = [
            'Attack' => 0,
            'Defence' => 0,
            'Luck' => 0,
            'Agility' => 0,
            'MagicAttack' => 0,
            'MagicDefence' => 0
        ];
        
        $count = count($players);
        
        // Somar cada atributo separadamente
        foreach ($players as $player) {
            $totals['Attack'] += max(0, $player->Attack ?? 0);
            $totals['Defence'] += max(0, $player->Defence ?? 0);
            $totals['Luck'] += max(0, $player->Luck ?? 0);
            $totals['Agility'] += max(0, $player->Agility ?? 0);
            $totals['MagicAttack'] += max(0, $player->MagicAttack ?? 0);
            $totals['MagicDefence'] += max(0, $player->MagicDefence ?? 0);
        }
        
        // Calcular médias e aplicar multiplicador
        $averages = [];
        foreach ($totals as $attr => $total) {
            $average = $count > 0 ? $total / $count : 1;
            $finalValue = max(1, round($average * $multiplier));
            $averages[$attr] = $finalValue;
        }
        
        return $averages;
    }

    /**
     * Buscar o maior HP de todos os jogadores para calcular Blood
     */
    private function getMaxHpFromAllPlayers()
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $stmt = $pdo->query("SELECT MAX(ISNULL(hp, 100)) as max_hp FROM Sys_Users_Fight WHERE hp > 0");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            
            $maxHp = $result->max_hp ?? 100;
            
            return max(100, $maxHp); // Mínimo 100
            
        } catch (\Exception $e) {
            return 1000; // Valor padrão mais alto
        }
    }

    // ===== MÉTODOS AUXILIARES =====

    private function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function validateNpcData($data)
    {
        if (empty($data['Name']) || trim($data['Name']) === '') {
            return ['valid' => false, 'message' => 'Nome é obrigatório'];
        }
        
        if (empty($data['ID']) || trim($data['ID']) === '') {
            return ['valid' => false, 'message' => 'ID é obrigatório'];
        }
        
        if (!is_numeric($data['ID']) || $data['ID'] <= 0) {
            return ['valid' => false, 'message' => 'ID deve ser um número positivo'];
        }
        
        return ['valid' => true, 'message' => ''];
    }

    private function prepareNpcData($data)
    {
        return [
            'ID'           => (int) $data['ID'],
            'Name'         => trim($data['Name']),
            'Level'        => max(1, isset($data['Level']) ? (int) $data['Level'] : 1),
            'Type'         => max(0, isset($data['Type']) ? (int) $data['Type'] : 0),
            'Blood'        => max(1, isset($data['Blood']) ? (int) $data['Blood'] : 10),
            'Attack'       => max(0, isset($data['Attack']) ? (int) $data['Attack'] : 1),
            'Defence'      => max(0, isset($data['Defence']) ? (int) $data['Defence'] : 1),
            'MagicAttack'  => max(0, isset($data['MagicAttack']) ? (int) $data['MagicAttack'] : 0),
            'MagicDefence' => max(0, isset($data['MagicDefence']) ? (int) $data['MagicDefence'] : 0),
            'BaseDamage'   => max(0, isset($data['BaseDamage']) ? (int) $data['BaseDamage'] : 1),
            'BaseGuard'    => max(0, isset($data['BaseGuard']) ? (int) $data['BaseGuard'] : 1),
            'Agility'      => max(0, isset($data['Agility']) ? (int) $data['Agility'] : 0),
            'Lucky'        => max(0, isset($data['Lucky']) ? (int) $data['Lucky'] : 0),
            'MoveMin'      => max(0, isset($data['MoveMin']) ? (int) $data['MoveMin'] : 0),
            'MoveMax'      => max(0, isset($data['MoveMax']) ? (int) $data['MoveMax'] : 0),
            'speed'        => max(0, isset($data['speed']) ? (int) $data['speed'] : 0),
            'Camp'         => 2,
            // Campos adicionais necessários para a tabela
            'X'            => max(0, isset($data['X']) ? (int) $data['X'] : 0),
            'Y'            => max(0, isset($data['Y']) ? (int) $data['Y'] : 0),
            'Width'        => max(0, isset($data['Width']) ? (int) $data['Width'] : 0),
            'Height'       => max(0, isset($data['Height']) ? (int) $data['Height'] : 0),
            'ModelID'      => max(0, isset($data['ModelID']) ? (int) $data['ModelID'] : 0),
            'ResourcesPath' => isset($data['ResourcesPath']) ? trim($data['ResourcesPath']) : '',
            'DropRate'     => max(0, isset($data['DropRate']) ? (int) $data['DropRate'] : 0),
            'Experience'   => max(1, isset($data['Experience']) ? (int) $data['Experience'] : 5),
            'Delay'        => max(0, isset($data['Delay']) ? (int) $data['Delay'] : 0),
            'Immunity'     => max(0, isset($data['Immunity']) ? (int) $data['Immunity'] : 0),
            'Alert'        => max(0, isset($data['Alert']) ? (int) $data['Alert'] : 0),
            'Range'        => max(0, isset($data['Range']) ? (int) $data['Range'] : 0),
            'Preserve'     => max(0, isset($data['Preserve']) ? (int) $data['Preserve'] : 0),
            'Script'       => isset($data['Script']) ? trim($data['Script']) : '',
            'FireX'        => max(0, isset($data['FireX']) ? (int) $data['FireX'] : 0),
            'FireY'        => max(0, isset($data['FireY']) ? (int) $data['FireY'] : 0),
            'DropId'       => max(0, isset($data['DropId']) ? (int) $data['DropId'] : 0),
            'CurrentBallId' => max(0, isset($data['CurrentBallId']) ? (int) $data['CurrentBallId'] : 0),
        ];
    }

    private function findNpcById($id)
    {
        try {
            return NpcInfo::find($id);
        } catch (\Exception $e) {
            return $this->findNpcByIdPDO($id);
        }
    }

    private function findNpcByIdPDO($id)
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]);

            $stmt = $pdo->prepare("SELECT * FROM NPC_Info WHERE ID = ?");
            $stmt->execute([$id]);
            
            return $stmt->fetch();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function createNpc($data)
    {
        try {
            $npc = new NpcInfo();
            $npc->incrementing = false;
            $npc->timestamps = false;
            
            foreach ($data as $key => $value) {
                $npc->$key = $value;
            }
            
            return $npc->save();
        } catch (\Exception $e) {
            return $this->createNpcPDO($data);
        }
    }

    private function createNpcPDO($data)
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $sql = "INSERT INTO NPC_Info (
                ID, Name, Level, Type, Blood, Attack, Defence, MagicAttack, MagicDefence, 
                BaseDamage, BaseGuard, Agility, Lucky, MoveMin, MoveMax, speed, Camp,
                X, Y, Width, Height, ModelID, ResourcesPath, DropRate, Experience,
                Delay, Immunity, Alert, Range, Preserve, Script, FireX, FireY, DropId, CurrentBallId
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['ID'], $data['Name'], $data['Level'], $data['Type'],
                $data['Blood'], $data['Attack'], $data['Defence'],
                $data['MagicAttack'], $data['MagicDefence'], $data['BaseDamage'], 
                $data['BaseGuard'], $data['Agility'], $data['Lucky'], 
                $data['MoveMin'], $data['MoveMax'], $data['speed'], $data['Camp'],
                $data['X'], $data['Y'], $data['Width'], $data['Height'],
                $data['ModelID'], $data['ResourcesPath'], $data['DropRate'],
                $data['Experience'], $data['Delay'], $data['Immunity'],
                $data['Alert'], $data['Range'], $data['Preserve'],
                $data['Script'], $data['FireX'], $data['FireY'],
                $data['DropId'], $data['CurrentBallId']
            ]);
        } catch (\Exception $e) {
            error_log("Erro ao criar NPC: " . $e->getMessage());
            return false;
        }
    }

    private function updateNpc($id, $data)
    {
        try {
            $npc = NpcInfo::find($id);
            if ($npc) {
                foreach ($data as $key => $value) {
                    $npc->$key = $value;
                }
                return $npc->save();
            }
            return false;
        } catch (\Exception $e) {
            return $this->updateNpcPDO($id, $data);
        }
    }

    private function updateNpcPDO($id, $data)
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $setParts = [];
            $values = [];
            foreach ($data as $column => $value) {
                $setParts[] = "$column = ?";
                $values[] = $value;
            }
            $values[] = $id;

            $sql = "UPDATE NPC_Info SET " . implode(', ', $setParts) . " WHERE ID = ?";
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute($values);
        } catch (\Exception $e) {
            error_log("Erro ao atualizar NPC: " . $e->getMessage());
            return false;
        }
    }

    private function deleteNpc($id)
    {
        try {
            $npc = NpcInfo::find($id);
            if ($npc) {
                return $npc->delete();
            }
            return false;
        } catch (\Exception $e) {
            return $this->deleteNpcPDO($id);
        }
    }

    private function deleteNpcPDO($id)
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $stmt = $pdo->prepare("DELETE FROM NPC_Info WHERE ID = ?");
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            error_log("Erro ao deletar NPC: " . $e->getMessage());
            return false;
        }
    }

    private function handleError($message, $isAjax, $statusCode = 500)
    {
        if ($isAjax) {
            return $this->jsonResponse([
                'success' => false,
                'message' => $message
            ], $statusCode);
        }
        
        return redirect('/admin/game/npc')->with('error', $message);
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        // Limpar qualquer output anterior
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}