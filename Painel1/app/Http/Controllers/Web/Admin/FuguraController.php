<?php
namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\Controller;
use App\Models\ClothProperty;
use Core\Routing\Response;
use App\Models\ClothGroup;
use App\Models\ShopGoods;

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
    
    // MÉTODO SHOW - Para buscar uma fugura específica via AJAX
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
            
            // FORÇAR INSERÇÃO COM ID ESPECÍFICO
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
            
            // Criar instância e forçar ID
            $fugura = new ClothProperty();
            
            // FORÇA O ID A SER ACEITO
            $fugura->incrementing = false;  // Desabilita auto-increment
            $fugura->timestamps = false;    // Desabilita timestamps se não existirem
            
            // Preencher todos os dados
            foreach ($fuguraData as $key => $value) {
                $fugura->$key = $value;
            }
            
            // FORÇAR SAVE COM ID ESPECÍFICO
            $saveResult = $fugura->save();
            
            if (!$saveResult) {
                error_log('ERRO: Save retornou false');
                $errorMsg = 'Falha ao salvar no banco de dados';
                
                if ($isAjax) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => $errorMsg
                    ], 500);
                }
                return redirect('/admin/gameutils/fugura')->with('error', $errorMsg);
            }
            
            error_log('Figura criada com sucesso: ID ' . $fugura->ID);
            
            $successMsg = 'Fúgura criada com sucesso!';
            
            if ($isAjax) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => $successMsg,
                    'data' => [
                        'ID' => $fugura->ID,
                        'Name' => $fugura->Name,
                        'Sex' => $fugura->Sex,
                        'Type' => $fugura->Type
                    ]
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
            
            // Validação - só verificar se Name não está vazio
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
            // Verificar se é requisição AJAX
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

    // Listar itens de uma figura
    public function getItems($id)
    {
        try {
            error_log('=== GET ITEMS DEBUG ===');
            error_log('Figura ID: ' . $id);
            
            $fugura = ClothProperty::find($id);
            if (!$fugura) {
                error_log('Figura não encontrada para ID: ' . $id);
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Figura não encontrada'
                ], 404);
            }

            error_log('Figura encontrada: ' . $fugura->Name);

            // Buscar itens relacionados na tabela ClothGroup
            $items = ClothGroup::where('ID', $id)->get();
            
            error_log('Itens encontrados: ' . $items->count());

            // Processar itens com imagens
            $itemsWithImages = $items->map(function ($item) {
                error_log('Processando item: ' . $item->TemplateID);
                
                // Buscar dados do item na ShopGoods para pegar imagem
                $shopGoods = new ShopGoods('db_tank');
                $shopItem = $shopGoods->where('TemplateID', $item->TemplateID)->first();
                
                $iconUrl = '/assets/media/svg/files/blank-image.svg'; // fallback
                
                if ($shopItem) {
                    $iconUrl = $shopItem->image();
                    error_log('Imagem encontrada para item ' . $item->TemplateID . ': ' . $iconUrl);
                } else {
                    error_log('Item não encontrado no shop: ' . $item->TemplateID);
                }
                
                return [
                    'ItemID' => $item->ItemID,
                    'ID' => $item->ID,
                    'TemplateID' => $item->TemplateID,
                    'Sex' => $item->Sex,
                    'Description' => $item->Description,
                    'Cost' => $item->Cost,
                    'Type' => $item->Type,
                    'OtherTemplateID' => $item->OtherTemplateID,
                    'Icon' => $iconUrl
                ];
            });

            error_log('Retornando ' . $itemsWithImages->count() . ' itens processados');

            return $this->jsonResponse([
                'success' => true,
                'items' => $itemsWithImages->toArray()
            ]);

        } catch (\Exception $e) {
            error_log('Erro ao buscar itens: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // Adicionar item à fugura
    public function addItem($id)
    {
        try {
            $data = $_POST;
            
            error_log('=== ADD ITEM DEBUG ===');
            error_log('Figura ID: ' . $id);
            error_log('POST data: ' . print_r($data, true));
            
            // Validações
            if (empty($data['TemplateID'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'TemplateID é obrigatório'
                ], 422);
            }

            $fugura = ClothProperty::find($id);
            if (!$fugura) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Figura não encontrada'
                ], 404);
            }

            // Criar novo item
            $item = new ClothGroup();
            $item->ID = $id;
            $item->TemplateID = (int) $data['TemplateID'];
            $item->Sex = isset($data['Sex']) ? (int) $data['Sex'] : 0;
            $item->Description = $data['Description'] ?? '';
            $item->Cost = isset($data['Cost']) ? (int) $data['Cost'] : 0;
            $item->Type = isset($data['Type']) ? (int) $data['Type'] : 1;
            $item->OtherTemplateID = isset($data['OtherTemplateID']) ? (int) $data['OtherTemplateID'] : 0;

            $saveResult = $item->save();

            if (!$saveResult) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao salvar item'
                ], 500);
            }

            error_log('Item adicionado com sucesso: ItemID ' . $item->ItemID);

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Item adicionado com sucesso!',
                'item' => [
                    'ItemID' => $item->ItemID,
                    'TemplateID' => $item->TemplateID,
                    'Description' => $item->Description,
                    'Sex' => $item->Sex,
                    'Cost' => $item->Cost,
                    'Type' => $item->Type,
                    'Icon' => '/assets/media/svg/files/blank-image.svg'
                ]
            ]);

        } catch (\Exception $e) {
            error_log('Erro ao adicionar item: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // Atualizar item
    public function updateItem($id, $itemId)
    {
        try {
            $data = $_POST;
            
            $item = ClothGroup::where('ID', $id)->where('ItemID', $itemId)->first();
            if (!$item) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Item não encontrado'
                ], 404);
            }

            // Atualizar campos
            if (isset($data['TemplateID'])) $item->TemplateID = (int) $data['TemplateID'];
            if (isset($data['Sex'])) $item->Sex = (int) $data['Sex'];
            if (isset($data['Description'])) $item->Description = $data['Description'];
            if (isset($data['Cost'])) $item->Cost = (int) $data['Cost'];
            if (isset($data['Type'])) $item->Type = (int) $data['Type'];
            if (isset($data['OtherTemplateID'])) $item->OtherTemplateID = (int) $data['OtherTemplateID'];

            $saveResult = $item->save();

            if (!$saveResult) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha ao atualizar item'
                ], 500);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Item atualizado com sucesso!',
                'item' => [
                    'ItemID' => $item->ItemID,
                    'TemplateID' => $item->TemplateID,
                    'Description' => $item->Description,
                    'Icon' => '/assets/media/svg/files/blank-image.svg'
                ]
            ]);

        } catch (\Exception $e) {
            error_log('Erro ao atualizar item: ' . $e->getMessage());
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // Deletar item
    public function deleteItem($id, $itemId)
    {
        try {
            error_log('=== DELETE ITEM DEBUG ===');
            error_log('Figura ID: ' . $id);
            error_log('Item ID: ' . $itemId);
            
            $item = ClothGroup::where('ID', $id)->where('ItemID', $itemId)->first();
            if (!$item) {
                error_log('Item não encontrado');
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Item não encontrado'
                ], 404);
            }

            $item->delete();
            error_log('Item deletado com sucesso');

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Item deletado com sucesso!'
            ]);

        } catch (\Exception $e) {
            error_log('Erro ao deletar item: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // MÉTODO HELPER PARA RESPOSTAS JSON
    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        error_log('=== JSON RESPONSE DEBUG ===');
        error_log('Status Code: ' . $statusCode);
        error_log('Data: ' . json_encode($data));
        
        echo json_encode($data);
        exit;
    }

    public function searchItems()
    {
        try {
            $search = $_GET['search'] ?? '';
            
            error_log('=== SEARCH ITEMS DEBUG ===');
            error_log('Search term: ' . $search);
            
            if (empty($search) || strlen($search) < 2) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Digite pelo menos 2 caracteres para buscar'
                ], 400);
            }

            // Buscar na tabela ShopGoods
            $shopGoods = new ShopGoods('db_tank');
            
            $items = $shopGoods->select('TemplateID', 'Name', 'CategoryID', 'Pic', 'NeedSex')
                              ->where('Name', 'like', '%' . $search . '%')
                              ->orWhere('TemplateID', 'like', '%' . $search . '%')
                              ->limit(20)
                              ->get();

            error_log('Items encontrados: ' . $items->count());

            // Processar resultados para Select2
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
                    'pic' => $item->image(), // Usar método do modelo
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
                'items' => $results
            ]);

        } catch (\Exception $e) {
            error_log('Erro ao buscar itens: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getShopItemInfo()
    {
        try {
            $templateID = $_GET['templateID'] ?? '';
            
            error_log('=== GET ITEM INFO DEBUG ===');
            error_log('TemplateID: ' . $templateID);

            if (empty($templateID)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'TemplateID não fornecido'
                ], 400);
            }

            // Buscar item específico
            $shopGoods = new ShopGoods('db_tank');
            
            $item = $shopGoods->select('TemplateID', 'Name', 'CategoryID', 'Pic', 'NeedSex')
                              ->where('TemplateID', $templateID)
                              ->first();

            if (!$item) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Item não encontrado'
                ], 404);
            }

            error_log('Item encontrado: ' . $item->Name);

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
            error_log('Erro ao buscar item: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
}