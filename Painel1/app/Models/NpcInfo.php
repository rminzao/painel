<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NpcInfo extends Model
{
    /**
     * Nome da tabela no banco de dados
     */
    protected $table = 'NPC_Info';

    /**
     * Chave primária da tabela
     */
    protected $primaryKey = 'ID';

    /**
     * Indica se a chave primária é auto-incrementável
     */
    public $incrementing = false;

    /**
     * Tipo da chave primária
     */
    protected $keyType = 'int';

    /**
     * Indica se o modelo deve gerenciar timestamps
     */
    public $timestamps = false;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'ID',
        'Name',
        'Level',
        'Camp',
        'Type',
        'X',
        'Y',
        'Width',
        'Height',
        'Blood',
        'MoveMin',
        'MoveMax',
        'BaseDamage',
        'BaseGuard',
        'Defence',
        'Agility',
        'Lucky',
        'Attack',
        'ModelID',
        'ResourcesPath',
        'DropRate',
        'Experience',
        'Delay',
        'Immunity',
        'Alert',
        'Range',
        'Preserve',
        'Script',
        'FireX',
        'FireY',
        'DropId',
        'CurrentBallId',
        'speed',
        'MagicAttack',
        'MagicDefence'
    ];

    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected $casts = [
        'ID' => 'integer',
        'Level' => 'integer',
        'Camp' => 'integer',
        'Type' => 'integer',
        'X' => 'integer',
        'Y' => 'integer',
        'Width' => 'integer',
        'Height' => 'integer',
        'Blood' => 'integer',
        'MoveMin' => 'integer',
        'MoveMax' => 'integer',
        'BaseDamage' => 'integer',
        'BaseGuard' => 'integer',
        'Defence' => 'integer',
        'Agility' => 'integer',
        'Lucky' => 'integer',
        'Attack' => 'integer',
        'Experience' => 'integer',
        'Delay' => 'integer',
        'Immunity' => 'integer',
        'Alert' => 'integer',
        'Range' => 'integer',
        'Preserve' => 'integer',
        'FireX' => 'integer',
        'FireY' => 'integer',
        'DropId' => 'integer',
        'CurrentBallId' => 'integer',
        'speed' => 'integer',
        'MagicAttack' => 'integer',
        'MagicDefence' => 'integer'
    ];

    /**
     * Método estático para buscar todos os NPCs
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public static function getAllNpcs()
    {
        try {
            // Tentar usar Eloquent primeiro - SEM LIMITE
            $npcs = static::orderBy('ID', 'asc')->get();
            
            if ($npcs->isEmpty()) {
                error_log('⚠️ Nenhum NPC encontrado via Eloquent');
                // Tentar busca direta via SQL
                return static::getAllNpcsDirectly();
            }
            
            error_log('✅ NPCs carregados via Eloquent: ' . $npcs->count());
            return $npcs;
            
        } catch (\Exception $e) {
            error_log('❌ Erro Eloquent getAllNpcs: ' . $e->getMessage());
            // Fallback para busca direta
            return static::getAllNpcsDirectly();
        }
    }

    /**
     * Método de fallback para buscar NPCs diretamente via PDO - SEM LIMITE
     * @return array
     */
    public static function getAllNpcsDirectly()
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]);

            // REMOVIDO O LIMITE TOP 200 - agora carrega TODOS os NPCs
            $stmt = $pdo->query("
                SELECT 
                    ID, Name, Level, Camp, Type, X, Y, Width, Height, Blood, 
                    MoveMin, MoveMax, BaseDamage, BaseGuard, Defence, Agility, Lucky, Attack, 
                    ModelID, ResourcesPath, DropRate, Experience, Delay, Immunity, Alert, Range, 
                    Preserve, Script, FireX, FireY, DropId, CurrentBallId, speed, MagicAttack, MagicDefence
                FROM NPC_Info 
                ORDER BY ID ASC
            ");
            
            $npcs = $stmt->fetchAll();
            error_log('✅ NPCs carregados via PDO: ' . count($npcs));
            return $npcs;
            
        } catch (\Exception $e) {
            error_log('❌ Erro PDO getAllNpcsDirectly: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar se a tabela existe no banco
     * @return bool
     */
    public static function tableExists()
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $stmt = $pdo->query("
                SELECT COUNT(*) as table_count 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_NAME = 'NPC_Info'
            ");
            
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            $exists = $result->table_count > 0;
            
            error_log('Tabela NPC_Info existe: ' . ($exists ? 'SIM' : 'NÃO'));
            return $exists;
            
        } catch (\Exception $e) {
            error_log('Erro ao verificar tabela: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Contar total de NPCs na tabela
     * @return int
     */
    public static function countNpcs()
    {
        try {
            $count = static::count();
            error_log('Total NPCs (Eloquent): ' . $count);
            return $count;
        } catch (\Exception $e) {
            error_log('Erro ao contar NPCs via Eloquent: ' . $e->getMessage());
            
            // Fallback via PDO
            try {
                $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
                $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]);

                $stmt = $pdo->query("SELECT COUNT(*) as total FROM NPC_Info");
                $result = $stmt->fetch(\PDO::FETCH_OBJ);
                $count = $result->total;
                
                error_log('Total NPCs (PDO): ' . $count);
                return $count;
            } catch (\Exception $e2) {
                error_log('Erro ao contar NPCs via PDO: ' . $e2->getMessage());
                return 0;
            }
        }
    }

    /**
     * Buscar NPCs por level
     * @param int $minLevel
     * @param int $maxLevel
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public static function getNpcsByLevel($minLevel, $maxLevel = null)
    {
        try {
            $query = static::where('Level', '>=', $minLevel);
            
            if ($maxLevel !== null) {
                $query->where('Level', '<=', $maxLevel);
            }
            
            return $query->orderBy('Level', 'asc')->get();
        } catch (\Exception $e) {
            error_log('Erro ao buscar NPCs por level: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Buscar NPCs por nome (busca parcial)
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public static function searchByName($name)
    {
        try {
            return static::where('Name', 'LIKE', "%{$name}%")
                         ->orderBy('Name', 'asc')
                         ->get();
        } catch (\Exception $e) {
            error_log('Erro ao buscar NPCs por nome: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Gerar ID aleatório disponível
     * @return int
     */
    public static function generateAvailableId()
    {
        try {
            $dsn = "sqlsrv:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};Database=db_tank";
            $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            // Tentar IDs aleatórios até encontrar um disponível
            $maxAttempts = 100;
            for ($i = 0; $i < $maxAttempts; $i++) {
                $randomId = rand(10000, 99999);
                
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM NPC_Info WHERE ID = ?");
                $stmt->execute([$randomId]);
                $count = $stmt->fetchColumn();
                
                if ($count == 0) {
                    return $randomId;
                }
            }
            
            // Se não encontrou, usar o próximo ID sequencial
            $stmt = $pdo->query("SELECT MAX(ID) + 1 as next_id FROM NPC_Info");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->next_id ?? 10001;
            
        } catch (\Exception $e) {
            error_log('Erro ao gerar ID: ' . $e->getMessage());
            return rand(10000, 99999);
        }
    }

    /**
     * Verificar se ID está disponível
     * @param int $id
     * @return bool
     */
    public static function isIdAvailable($id)
    {
        try {
            return static::where('ID', $id)->count() === 0;
        } catch (\Exception $e) {
            error_log('Erro ao verificar ID: ' . $e->getMessage());
            return false;
        }
    }
}