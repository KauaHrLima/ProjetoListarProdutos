<?php
require_once 'config/conection.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM produtos ORDER BY criado_em DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($produtos);

$feedback = '';
if (isset($_GET['msg'])) {
    $msgs = [
        'criado'     => ['texto' => 'Produto cadastrado com sucesso!', 'tipo' => ''],
        'atualizado' => ['texto' => 'Produto atualizado com sucesso!', 'tipo' => ''],
        'deletado'   => ['texto' => 'Produto removido com sucesso.',   'tipo' => ''],
    ];
    if (isset($msgs[$_GET['msg']])) {
        $m = $msgs[$_GET['msg']];
        $feedback = "<div class=\"alerta {$m['tipo']}\"><i class=\"bi bi-check-circle-fill\"></i> {$m['texto']}</div>";
    }
}
?>
<?php echo $feedback; ?>
<div class="topo-lista">
    <div class="topo-lista-info">
        <h2>Produtos Cadastrados</h2>
        <p><?php echo $total; ?> produto<?php echo $total !== 1 ? 's' : ''; ?> no catálogo</p>
    </div>
    <a href="form.php" class="btn">
        <i class="bi bi-plus-lg"></i>
        Novo Produto
    </a>
</div>
<?php if (empty($produtos)): ?>
    <div class="estado-vazio">
        <i class="bi bi-box-seam" style="font-size: 64px; color: var(--text-muted); margin-bottom: 16px;"></i>
        <h3>Nenhum produto cadastrado</h3>
        <p>Comece adicionando seu primeiro produto ao catálogo.</p>
        <a href="form.php" class="btn">
            <i class="bi bi-plus-lg"></i>
            Adicionar Produto
        </a>
    </div>
<?php else: ?>
    <div class="cards">
        <?php foreach ($produtos as $p): ?>
            <?php $iconeCard = !empty($p['icone']) ? $p['icone'] : 'bi-box-seam'; ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title-group">
                        <div class="card-icon">
                            <i class="bi <?php echo htmlspecialchars($iconeCard); ?>"></i>
                        </div>
                        <div>
                            <h3><?php echo htmlspecialchars($p['nome']); ?></h3>
                            <span class="badge-estoque <?php echo $p['estoque'] == 0 ? 'zero' : ''; ?>">
                                <?php echo $p['estoque'] == 0 ? 'Sem estoque' : 'Estoque: ' . $p['estoque']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <p class="descricao"><?php echo htmlspecialchars($p['descricao'] ?: 'Sem descrição.'); ?></p>
                <div class="card-divider"></div>
                <div class="preco">
                    <small>R$</small> <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                </div>
                <div class="acoes">
                    <a href="form.php?id=<?php echo $p['id']; ?>" class="btn-editar">
                        <i class="bi bi-pencil-square"></i>
                        Editar
                    </a>
                    <a href="actions/delete.php?id=<?php echo $p['id']; ?>"
                       class="btn-deletar"
                       onclick="return confirm('Tem certeza que deseja remover o produto «<?php echo htmlspecialchars(addslashes($p['nome'])); ?>»?')">
                        <i class="bi bi-trash3"></i>
                        Deletar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>