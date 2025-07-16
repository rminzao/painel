<?php

namespace Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /** @var Capsule */
    private static $instance;

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
    }

    /**
     * Adiciona uma nova conexão nomeada (ex: Db_Tank)
     */
    public static function addConnection(string $name, array $config)
    {
        if (!self::$instance->getConnection($name, false)) {
            self::$instance->addConnection($config, $name);
        }
    }

    /**
     * Retorna a instância do Capsule
     */
    public static function getInstance()
    {
        return self::$instance;
    }
}
