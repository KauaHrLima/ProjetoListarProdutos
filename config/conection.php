<?php
$host = 'localhost';
$db = 'produtos';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS produtos (
            id        INT AUTO_INCREMENT PRIMARY KEY,
            nome      VARCHAR(150)  NOT NULL,
            descricao TEXT,
            preco     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            estoque   INT           NOT NULL DEFAULT 0,
            icone     VARCHAR(50)   NOT NULL DEFAULT 'bi-box-seam',
            criado_em DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $colunas = $pdo->query("SHOW COLUMNS FROM produtos LIKE 'icone'")->fetchAll();
    if (empty($colunas)) {
        $pdo->exec("ALTER TABLE produtos ADD COLUMN icone VARCHAR(50) NOT NULL DEFAULT 'bi-box-seam'");
    }
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;color:#991b1b;background:#fee2e2;border-radius:8px;margin:40px auto;max-width:600px;">
        <strong>Erro de conexão com o banco de dados:</strong><br><br>'
        . htmlspecialchars($e->getMessage()) .
        '<br><br>Verifique se o MySQL (XAMPP) está rodando.
    </div>');
}