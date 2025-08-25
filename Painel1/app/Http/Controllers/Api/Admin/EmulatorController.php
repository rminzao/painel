<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;

class EmulatorController
{
    private function getConfig()
    {
        return [
                'vps_ip' => env('EMULATOR_VPS_IP'),
                'vps_port' => env('EMULATOR_VPS_PORT', '8080'),
                'emulator_path' => env('EMULATOR_PATH'),
                'xml_url' => env('XML_UPDATE_URL'),
                'timeout' => 30,
            ];
    }

    private function getBaseUrl()
    {
        $config = $this->getConfig();
        return "http://{$config['vps_ip']}:{$config['vps_port']}";
    }

    private function makeRequest($url, $method = 'GET', $data = null)
    {
        $config = $this->getConfig();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $headers = [
            'Accept: application/json',
            'User-Agent: LegionTank-Panel/1.0'
        ];

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("Erro de conexão: $error");
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error $httpCode");
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Resposta inválida da VPS');
        }
        
        return $decoded;
    }

    public function reloadXml($request)
    {
        try {
            $config = $this->getConfig();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config['xml_url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $config['timeout']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);

            if (!$error && $httpCode === 200) {
                return [
                    'success' => true,
                    'message' => 'XML do jogo atualizado com sucesso',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'updated_by' => $request->user->first_name ?? 'Admin'
                ];
            } else {
                throw new Exception($error ?: "HTTP Error $httpCode");
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar XML: ' . $e->getMessage()
            ];
        }
    }

    public function status($request)
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $data = $this->makeRequest("{$baseUrl}/status");

            if (isset($data['emulators'])) {
                $emulators = array_map(function ($emu) {
                    return [
                        'name' => $emu['name'],
                        'process' => $emu['process'] ?? $emu['name'] . '.Service',
                        'isRunning' => $emu['isRunning'] ?? false,
                        'pid' => $emu['pid'] ?? null,
                        'memoryMB' => round($emu['memoryMB'] ?? 0, 1),
                        'startTime' => $emu['startTime'] ?? null,
                        'port' => $this->getPortByName($emu['name'])
                    ];
                }, $data['emulators']);

                $onlineCount = count(array_filter($emulators, fn($e) => $e['isRunning']));
                $totalMemory = array_sum(array_column($emulators, 'memoryMB'));

                return [
                    'success' => true,
                    'message' => "Status obtido: $onlineCount/" . count($emulators) . " online",
                    'emulators' => $emulators,
                    'server_info' => [
                        'vps_ip' => $this->getConfig()['vps_ip'],
                        'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s'),
                        'total_emulators' => count($emulators),
                        'online_count' => $onlineCount,
                        'total_memory_mb' => round($totalMemory, 1),
                        'all_online' => $onlineCount === count($emulators)
                    ]
                ];
            } else {
                throw new Exception('Resposta inválida da VPS');
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage(),
                'emulators' => $this->getDefaultEmulatorStatus(),
                'server_info' => [
                    'vps_ip' => $this->getConfig()['vps_ip'],
                    'timestamp' => date('Y-m-d H:i:s'),
                    'total_emulators' => 3,
                    'online_count' => 0,
                    'total_memory_mb' => 0,
                    'all_online' => false
                ]
            ];
        }
    }

    public function start($request)
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $data = $this->makeRequest("{$baseUrl}/start", 'POST');
            $success = isset($data['success']) ? $data['success'] : false;
            $results = isset($data['results']) ? $data['results'] : [];

            if ($success) {
                $successCount = count(array_filter($results, function($r) {
                    return isset($r['status']) && in_array($r['status'], ['started', 'already_running']);
                }));

                return [
                    'success' => true,
                    'status' => 'started',
                    'message' => 'Sequência de inicialização iniciada com sucesso',
                    'details' => $results,
                    'sequence_info' => 'Center (3s) ? Fighting (15s) ? Road',
                    'estimated_time' => '~25 segundos para inicialização completa',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'started_by' => $request->user->first_name ?? 'Admin',
                    'results' => $results,
                    'progress' => [
                        'total' => count($results) > 0 ? count($results) : 3,
                        'success' => $successCount,
                        'percentage' => count($results) > 0 ? round(($successCount / count($results)) * 100, 0) : 0
                    ]
                ];
            } else {
                return [
                    'success' => true,
                    'status' => 'command_sent',
                    'message' => 'Comando de inicialização enviado!',
                    'details' => 'O comando foi enviado com sucesso. Monitore o status para verificar o progresso.',
                    'sequence_info' => 'Center (3s) ? Fighting (15s) ? Road',
                    'estimated_time' => '~25 segundos para inicialização completa',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'started_by' => $request->user->first_name ?? 'Admin',
                    'results' => [],
                    'progress' => [
                        'total' => 3,
                        'success' => 0,
                        'percentage' => 0
                    ]
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Erro ao conectar com VPS: ' . $e->getMessage(),
                'error_details' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }

    public function stop($request)
    {
        try {
            $baseUrl = $this->getBaseUrl();
            $data = $this->makeRequest("{$baseUrl}/stop", 'POST');

            if (isset($data['success']) && $data['success']) {
                $results = $data['results'] ?? [];
                $successCount = count(array_filter($results, function($r) {
                    return in_array($r['status'], ['stopped', 'not_running']);
                }));

                return [
                    'success' => true,
                    'message' => 'Emuladores parados com sucesso',
                    'details' => $results,
                    'warning' => 'Todos os jogadores online foram desconectados',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'stopped_by' => $request->user->first_name ?? 'Admin',
                    'results' => $results,
                    'progress' => [
                        'total' => count($results),
                        'success' => $successCount,
                        'percentage' => count($results) > 0 ? round(($successCount / count($results)) * 100, 0) : 0
                    ]
                ];
            } else {
                throw new Exception($data['message'] ?? 'Falha ao parar emuladores');
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao parar emuladores: ' . $e->getMessage()
            ];
        }
    }

    public function restart($request)
    {
        try {
            $stopResult = $this->stop($request);
            
            if (!$stopResult['success']) {
                throw new Exception('Falha ao parar emuladores: ' . $stopResult['message']);
            }

            sleep(5);

            $startResult = $this->start($request);

            if (!$startResult['success']) {
                throw new Exception('Falha ao iniciar emuladores: ' . $startResult['message']);
            }

            return [
                'success' => true,
                'message' => 'Emuladores reiniciados com sucesso',
                'stop_result' => $stopResult,
                'start_result' => $startResult,
                'total_time' => '~30 segundos',
                'timestamp' => date('Y-m-d H:i:s'),
                'restarted_by' => $request->user->first_name ?? 'Admin'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao reiniciar emuladores: ' . $e->getMessage()
            ];
        }
    }

    public function ping($request)
    {
        try {
            $config = $this->getConfig();
            $startTime = microtime(true);
            
            $socket = @fsockopen($config['vps_ip'], $config['vps_port'], $errno, $errstr, 5);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            $isOnline = $socket !== false;
            
            if ($socket) {
                fclose($socket);
            }

            return [
                'success' => $isOnline,
                'message' => $isOnline ? 'VPS conectada e acessível' : 'VPS não acessível',
                'response_time' => "{$responseTime}ms",
                'vps_url' => $this->getBaseUrl(),
                'status' => $isOnline ? 'online' : 'offline',
                'timestamp' => date('Y-m-d H:i:s'),
                'error_detail' => !$isOnline ? "$errno: $errstr" : null
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro no teste de conectividade: ' . $e->getMessage(),
                'response_time' => 'error',
                'vps_url' => $this->getBaseUrl(),
                'status' => 'offline',
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }

    private function getPortByName($name)
    {
        $ports = [
            'Center' => '7000',
            'Fighting' => '7001', 
            'Road' => '7002'
        ];

        return $ports[$name] ?? 'N/A';
    }

    private function getDefaultEmulatorStatus()
    {
        return [
            [
                'name' => 'Center',
                'process' => 'Center.Service',
                'isRunning' => false,
                'pid' => null,
                'memoryMB' => 0,
                'startTime' => null,
                'port' => '7000'
            ],
            [
                'name' => 'Fighting',
                'process' => 'Fighting.Service',
                'isRunning' => false,
                'pid' => null,
                'memoryMB' => 0,
                'startTime' => null,
                'port' => '7001'
            ],
            [
                'name' => 'Road',
                'process' => 'Road.Service',
                'isRunning' => false,
                'pid' => null,
                'memoryMB' => 0,
                'startTime' => null,
                'port' => '7002'
            ]
        ];
    }
}