<?php
$serverName = "RAFAEL\\RAFAELPC"; // Nome da instância do SQL Server
$connectionOptions = [
    "Database" => "app", // ← Use aqui o nome correto do seu banco
    "Uid" => "sa",
    "PWD" => "ddtank123",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn) {
    echo "Conectado com sucesso!";
} else {
    echo "Erro na conexão:<br>";
    print_r(sqlsrv_errors());
}
