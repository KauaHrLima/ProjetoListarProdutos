<?php
require_once 'config/conection.php';
include 'includes/header.php';

$produto = null;
$editando = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute([':id' => (int) $_GET['id']]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        header('Location: /ProjetoListarProdutos/index.php');
        exit;
    }
}

$iconesDisponiveis = [
    'bi-box-seam' => 'Geral',
    'bi-tag' => 'Moda',
    'bi-laptop' => 'Notebook',
    'bi-phone' => 'Celular',
    'bi-tv' => 'TV / Vídeo',
    'bi-headphones' => 'Áudio',
    'bi-watch' => 'Relógio',
    'bi-controller' => 'Games',
    'bi-cup-hot' => 'Bebidas',
    'bi-basket' => 'Mercado',
    'bi-cart3' => 'Compras',
    'bi-bag' => 'Bolsa',
    'bi-book' => 'Livros',
    'bi-tools' => 'Ferramentas',
    'bi-house' => 'Casa',
    'bi-heart-pulse' => 'Saúde',
    'bi-car-front' => 'Auto',
    'bi-gem' => 'Joias'
];
$iconeSelecionado = $produto['icone'] ?? 'bi-box-seam';
?>
<div class="form-wrapper">
    <div class="form-header">
        <h2><?php echo $editando ? 'Editar Produto' : 'Novo Produto'; ?></h2>
        <p><?php echo $editando ? 'Altere os campos e salve as mudanças.' : 'Preencha os dados para cadastrar um novo produto.'; ?>
        </p>
    </div>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alerta erro">
            <i class="bi bi-exclamation-triangle"></i>
            Nome e Preço são obrigatórios. Verifique os campos e tente novamente.
        </div>
    <?php endif; ?>
    <div class="form-box">
        <form method="POST" action="<?php echo $editando ? 'actions/update.php' : 'actions/create.php'; ?>">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="nome" placeholder="Ex.: Camiseta Polo Azul"
                    value="<?php echo htmlspecialchars($produto['nome'] ?? ''); ?>" required autofocus>
            </div>
            <div class="form-group">
                <label>Ícone do Produto</label>
                <div class="icon-selector-grid">
                    <?php foreach ($iconesDisponiveis as $ico => $label): ?>
                        <label class="icon-option">
                            <input type="radio" name="icone" value="<?php echo $ico; ?>" <?php echo $iconeSelecionado === $ico ? 'checked' : ''; ?>>
                            <span class="icon-card">
                                <i class="bi <?php echo $ico; ?>"></i>
                                <small><?php echo $label; ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao"
                    placeholder="Descreva o produto brevemente..."><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="preco">Preço (R$)</label>
                    <input type="number" id="preco" step="0.01" min="0" name="preco" placeholder="0,00"
                        value="<?php echo $produto['preco'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="estoque">Estoque (un.)</label>
                    <input type="number" id="estoque" min="0" name="estoque" placeholder="0"
                        value="<?php echo $produto['estoque'] ?? 0; ?>">
                </div>
            </div>
            <div class="form-acoes">
                <button type="submit" class="btn">
                    <i class="bi bi-check2-circle"></i>
                    <?php echo $editando ? 'Salvar Alterações' : 'Cadastrar Produto'; ?>
                </button>
                <a href="index.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>