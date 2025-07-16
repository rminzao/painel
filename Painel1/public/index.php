<?php

ob_start();

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('America/Sao_Paulo');

define('SCRIPT_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

if (isset($_GET['isClient'])) {
    if (!session_id()) {
        session_save_path(__DIR__ . '/../storage/framework/sessions');
        session_start();
    }

    $_SESSION['clientUser'] = true;
}

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app
  ->run()
  ->sendResponse();

ob_end_flush();
