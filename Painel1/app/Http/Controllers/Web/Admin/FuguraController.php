<?php
namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\Controller;
use App\Models\ClothProperty;
use Core\Routing\Response;

class FuguraController extends Controller
{
    public function index()
    {
        try {
            return $this->view->render('admin.fuguras.index', [
                'fuguras' => ClothProperty::all()
            ]);
        } catch (\Exception $e) {
            return "Erro ao carregar a lista: " . $e->getMessage();
        }
    }
    
    // MÉTODO SHOW - Para buscar uma figura específica via AJAX
    public function show($id)
    {
        try {
            error_log('=== SHOW FUGURA DEBUG ===');
            error_log('Buscando ID: ' . $id);
            
            $fugura = ClothProperty::find($id);
            
            if (!$fugura) {
                error_log('Figura não encontrada para ID: ' . $id);
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Figura não encontrada'
                ], 404);
            }
            
            error_log('Figura encontrada: ' . $fugura->Name);
            
            $responseData = [
                'success' => true,
                'data' => [
                    'ID' => $fugura->ID,
                    'Name' => $fugura->Name,
                    'Sex' => $fugura->Sex,
                    'Type' => $fugura->Type,
                    'Attack' => $fugura->Attack,
                    'Defend' => $fugura->Defend,
                    'Agility' => $fugura->Agility,
                    'Luck' => $fugura->Luck,
                    'Blood' => $fugura->Blood,
                    'Damage' => $fugura->Damage,
                    'Guard' => $fugura->Guard,
                    'Cost' => $fugura->Cost
                ]
            ];
            
            error_log('Response data: ' . json_encode($responseData));
            return $this->jsonResponse($responseData);
            
        } catch (\Exception $e) {
            error_log('Erro ao buscar figura: ' . $e->getMessage());
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function store()
    {
        try {
            // Verificar se é requisição AJAX
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            error_log('=== STORE FUGURA DEBUG ===');
            error_log('Is AJAX: ' . ($isAjax ? 'true' : 'false'));
            error_log('POST data: ' . print_r($_POST, true));
            
            $data = $_POST;
            
            // Nome é obrigatório
            if (empty($data['Name']) || trim($data['Name']) === '') {
                $errorMsg = "Nome é obrigatório";
                error_log('Erro de validação: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => $errorMsg
                    ], 422);
                }
                return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
            }
            
            // ID é obrigatório nesta tabela
            if (empty($data['ID']) || trim($data['ID']) === '') {
                $errorMsg = "ID é obrigatório";
                error_log('Erro de validação: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => $errorMsg
                    ], 422);
                }
                return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
            }
            
            // Verificar se ID já existe
            $exists = ClothProperty::find($data['ID']);
            if ($exists) {
                $errorMsg = "ID {$data['ID']} já existe";
                error_log('Erro: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => $errorMsg
                    ], 422);
                }
                return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
            }
            
            // Sempre usar o ID fornecido, nunca NULL
            $fuguraData = [
                'ID'      => (int) $data['ID'],
                'Name'    => trim($data['Name']),
                'Sex'     => isset($data['Sex']) ? (int) $data['Sex'] : 0,
                'Type'    => isset($data['Type']) ? (int) $data['Type'] : 0,
                'Attack'  => isset($data['Attack']) ? (int) $data['Attack'] : 0,
                'Defend'  => isset($data['Defend']) ? (int) $data['Defend'] : 0,
                'Agility' => isset($data['Agility']) ? (int) $data['Agility'] : 0,
                'Luck'    => isset($data['Luck']) ? (int) $data['Luck'] : 0,
                'Blood'   => isset($data['Blood']) ? (int) $data['Blood'] : 0,
                'Damage'  => isset($data['Damage']) ? (int) $data['Damage'] : 0,
                'Guard'   => isset($data['Guard']) ? (int) $data['Guard'] : 0,
                'Cost'    => isset($data['Cost']) ? (int) $data['Cost'] : 0,
            ];
            
            error_log('Dados para criação: ' . print_r($fuguraData, true));
            
            $fugura = ClothProperty::create($fuguraData);
            
            error_log('Figura criada com sucesso: ID ' . $fugura->ID);
            
            $successMsg = 'Fúgura criada com sucesso!';
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => $successMsg,
                    'data' => $fugura
                ], 201);
            }
            
            return redirect('/admin/gameutils/fugura')->with('success', $successMsg);
            
        } catch (\Exception $e) {
            error_log('Erro ao criar figura: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            $errorMsg = 'Erro ao criar fúgura: ' . $e->getMessage();
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }
            
            return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
        }
    }
    
    public function update($id)
    {
        try {
            // Verificar se é uma requisição AJAX
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            error_log('=== UPDATE FUGURA DEBUG ===');
            error_log('ID: ' . $id);
            error_log('Is AJAX: ' . ($isAjax ? 'true' : 'false'));
            error_log('POST data: ' . print_r($_POST, true));
            
            $data = $_POST;
            
            // Verificar se a figura existe
            $fugura = ClothProperty::find($id);
            if (!$fugura) {
                error_log('Figura não encontrada para ID: ' . $id);
                
                $errorResponse = [
                    'success' => false,
                    'message' => 'Figura não encontrada.'
                ];
                
                if ($isAjax) {
                    return $this->jsonResponse($errorResponse, 404);
                }
                return redirect('/admin/gameutils/fugura')->with('error', 'Figura não encontrada.');
            }
            
            error_log('Figura encontrada: ' . $fugura->Name);
            
            // Validação
            if (empty($data['Name']) || trim($data['Name']) === '') {
                error_log('Erro: Nome está vazio');
                
                $errorResponse = [
                    'success' => false,
                    'message' => 'Nome é obrigatório'
                ];
                
                if ($isAjax) {
                    return $this->jsonResponse($errorResponse, 400);
                }
                return redirect('/admin/gameutils/fugura')->with('error', 'Nome é obrigatório');
            }
            
            // Atualizar os campos
            $fugura->Name    = trim($data['Name']);
            $fugura->Sex     = isset($data['Sex']) ? (int) $data['Sex'] : 0;
            $fugura->Type    = isset($data['Type']) ? (int) $data['Type'] : 0;
            $fugura->Attack  = isset($data['Attack']) ? (int) $data['Attack'] : 0;
            $fugura->Defend  = isset($data['Defend']) ? (int) $data['Defend'] : 0;
            $fugura->Agility = isset($data['Agility']) ? (int) $data['Agility'] : 0;
            $fugura->Luck    = isset($data['Luck']) ? (int) $data['Luck'] : 0;
            $fugura->Blood   = isset($data['Blood']) ? (int) $data['Blood'] : 0;
            $fugura->Damage  = isset($data['Damage']) ? (int) $data['Damage'] : 0;
            $fugura->Guard   = isset($data['Guard']) ? (int) $data['Guard'] : 0;
            $fugura->Cost    = isset($data['Cost']) ? (int) $data['Cost'] : 0;
            
            error_log('Salvando dados...');
            
            // Salvar no banco
            $saveResult = $fugura->save();
            
            error_log('Save result: ' . ($saveResult ? 'SUCCESS' : 'FAILED'));
            
            if (!$saveResult) {
                error_log('ERRO: Save retornou false');
                
                $errorResponse = [
                    'success' => false,
                    'message' => 'Falha ao salvar no banco de dados'
                ];
                
                if ($isAjax) {
                    return $this->jsonResponse($errorResponse, 500);
                }
                return redirect('/admin/gameutils/fugura')->with('error', 'Falha ao salvar');
            }
            
            error_log('Figura atualizada com sucesso!');
            
            $successResponse = [
                'success' => true,
                'message' => 'Figura atualizada com sucesso!',
                'data' => [
                    'ID' => $fugura->ID,
                    'Name' => $fugura->Name,
                    'Sex' => $fugura->Sex,
                    'Type' => $fugura->Type,
                    'Attack' => $fugura->Attack,
                    'Defend' => $fugura->Defend,
                    'Agility' => $fugura->Agility,
                    'Luck' => $fugura->Luck,
                    'Blood' => $fugura->Blood,
                    'Damage' => $fugura->Damage,
                    'Guard' => $fugura->Guard,
                    'Cost' => $fugura->Cost
                ]
            ];
            
            if ($isAjax) {
                return $this->jsonResponse($successResponse, 200);
            }
            
            return redirect('/admin/gameutils/fugura')->with('success', 'Figura atualizada com sucesso!');
            
        } catch (\Exception $e) {
            error_log('=== ERRO CRÍTICO ===');
            error_log('Mensagem: ' . $e->getMessage());
            error_log('Arquivo: ' . $e->getFile() . ':' . $e->getLine());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            $errorResponse = [
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ];
            
            if ($isAjax) {
                return $this->jsonResponse($errorResponse, 500);
            }
            
            return redirect('/admin/gameutils/fugura')->with('error', 'Erro ao atualizar figura: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            error_log('=== DELETE FUGURA DEBUG ===');
            error_log('ID: ' . $id);
            
            $fugura = ClothProperty::find($id);
            
            if (!$fugura) {
                $errorMsg = 'Figura não encontrada';
                error_log('Erro: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => $errorMsg
                    ], 404);
                }
                return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
            }
            
            $fugura->delete();
            error_log('Figura deletada com sucesso: ' . $fugura->Name);
            
            $successMsg = 'Fúgura deletada com sucesso!';
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => $successMsg
                ]);
            }
            
            return redirect('/admin/gameutils/fugura')->with('success', $successMsg);
            
        } catch (\Exception $e) {
            error_log('Erro ao deletar figura: ' . $e->getMessage());
            
            $errorMsg = 'Erro ao deletar fúgura: ' . $e->getMessage();
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }
            
            return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
        }
    }
    
    // MÉTODO HELPER PARA RESPOSTAS JSON
    private function jsonResponse($data, $statusCode = 200)
    {
        try {
            error_log('=== JSON RESPONSE ===');
            error_log('Status: ' . $statusCode);
            error_log('Data: ' . json_encode($data));
            
            // Headers
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($statusCode);
            
            if (class_exists('Core\Routing\Response')) {
                return new Response($statusCode, json_encode($data), 'application/json');
            }
            
            // Fallback
            echo json_encode($data);
            exit;
            
        } catch (\Exception $e) {
            error_log('Erro ao criar resposta JSON: ' . $e->getMessage());
            
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
            exit;
        }
    }
}