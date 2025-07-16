<?php
try {
    $pdo = new PDO("sqlsrv:Server=WIN-S1P3PUQKLNE\\DDTANK55;Database=app_black", "sa", "@DDTankPrisma123@Pintudus#2025");
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
