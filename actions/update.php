<?php
require_once '../config/conection.php';

$id        = (int)($_POST['id'] ?? 0);
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco     = $_POST['preco'] ?? '';
$estoque   = (int)($_POST['estoque'] ?? 0);
$icone     = trim($_POST['icone'] ?? 'bi-box-seam');

if (empty($icone)) {
    $icone = 'bi-box-seam';
}

if (!$id || empty($nome) || $preco === '' || $preco < 0) {
    header("Location: /ProjetoListarProdutos/form.php?id=$id&erro=1");
    exit;
}

$sql  = "UPDATE produtos SET nome=:nome, descricao=:descricao, preco=:preco, estoque=:estoque, icone=:icone WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nome'      => $nome,
    ':descricao' => $descricao,
    ':preco'     => (float)$preco,
    ':estoque'   => $estoque,
    ':icone'     => $icone,
    ':id'        => $id,
]);

header('Location: /ProjetoListarProdutos/index.php?msg=atualizado');
exit;
