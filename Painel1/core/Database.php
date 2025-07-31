<?php

namespace Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /** @var Capsule */
    private static $instance;

    /** @var array */
    private static $connections = [];

    /**
     * Inicializa o Eloquent
     */
    public static function init()
    {
        self::$instance = new Capsule();

        // Conexão principal (do painel)
        self::$instance->addConnection([
            'driver' => $_ENV['DB_CONNECTION'],
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        self::$instance->bootEloquent();

        // Conexão do jogo (db_tank) - ADICIONADO PARA WAR PASS
        if (isset($_ENV['DB_TANK_HOST'])) {
            self::addConnection('db_tank', [
                'driver' => 'sqlsrv',
                'host' => $_ENV['DB_TANK_HOST'],
                'port' => $_ENV['DB_TANK_PORT'] ?? '1433',
                'database' => $_ENV['DB_TANK_DATABASE'],
                'username' => $_ENV['DB_TANK_USERNAME'],
                'password' => $_ENV['DB_TANK_PASSWORD'],
                'charset' => 'utf8',
                'prefix' => '',
            ]);
        }
    }

    /**
     * Adiciona uma nova conexão nomeada (ex: Db_Tank)
     */
    public static function addConnection(string $name, array $config)
    {
        // Verifica se a conexão já foi registrada em nosso array de controle
        if (!isset(self::$connections[$name])) {
            self::$instance->addConnection($config, $name);
            self::$connections[$name] = true; // Marca como registrada
        }
    }

    /**
     * Retorna a instância do Capsule
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Retorna uma conexão específica
     */
    public static function getConnection(string $name = null)
    {
        if ($name === null) {
            return self::$instance->getConnection();
        }
        
        return self::$instance->getConnection($name);
    }
}