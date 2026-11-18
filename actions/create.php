<?php
require_once '../config/conection.php';

$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco     = $_POST['preco'] ?? '';
$estoque   = (int)($_POST['estoque'] ?? 0);
$icone     = trim($_POST['icone'] ?? 'bi-box-seam');

if (empty($icone)) {
    $icone = 'bi-box-seam';
}

if (empty($nome) || $preco === '' || $preco < 0) {
    header('Location: /ProjetoListarProdutos/form.php?erro=1');
    exit;
}

$sql  = "INSERT INTO produtos (nome, descricao, preco, estoque, icone) VALUES (:nome, :descricao, :preco, :estoque, :icone)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nome'      => $nome,
    ':descricao' => $descricao,
    ':preco'     => (float)$preco,
    ':estoque'   => $estoque,
    ':icone'     => $icone,
]);

header('Location: /ProjetoListarProdutos/index.php?msg=criado');
exit;