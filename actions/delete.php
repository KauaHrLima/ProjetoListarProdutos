<?php
require_once '../config/conection.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$id) {
    header('Location: /ProjetoListarProdutos/index.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: /ProjetoListarProdutos/index.php?msg=deletado');
exit;