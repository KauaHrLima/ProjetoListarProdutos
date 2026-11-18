# Gerenciador de Produtos

Projeto simples de CRUD (Create, Read, Update, Delete) de produtos feito em PHP puro com MySQL, usando PDO e Bootstrap Icons.

## Como rodar o projeto

1. Baixe ou copie a pasta do projeto para dentro de `htdocs` do seu XAMPP:
   `C:\xampp\htdocs\ProjetoListarProdutos`
2. Abra o XAMPP e inicie o **Apache** e o **MySQL**.
3. Acesse no navegador:
   `http://localhost/ProjetoListarProdutos/`

> O banco de dados (`produtos`) e a tabela são criados sozinhos no primeiro acesso, não precisa importar nada manualmente. Se preferir criar na mão, o script está em `config/database.sql`.

---

## O que o sistema faz

- Lista todos os produtos cadastrados em formato de cards.
- Cadastro e edição de produtos (nome, descrição, preço, estoque e ícone).
- Exclusão com confirmação antes de apagar.
- Avisos na tela quando um produto é adicionado, editado ou removido.

---

## Como o código está organizado

### `index.php`
É a página principal. Faz um `SELECT` no banco para pegar todos os produtos ordenados pelo mais recente e monta os cards na tela. Também pega o parâmetro `?msg=` da URL para exibir avisos de sucesso. Se não tiver nenhum produto cadastrado, mostra uma mensagem avisando.

### `form.php`
Serve tanto para cadastrar quanto para editar. Ele verifica se veio um `id` pela URL:
- Se veio um `id`, busca o produto no banco e preenche os campos para edição.
- Se não veio, deixa o formulário em branco para cadastro novo.
Também tem a grade para escolher o ícone que vai aparecer no card do produto.

### `config/conection.php`
Faz a conexão com o MySQL usando PDO. Antes de conectar no banco, ele checa se o banco `produtos` e a tabela já existem. Se não existirem, cria na hora. Se a tabela já existir mas não tiver o campo `icone`, ele roda um `ALTER TABLE` para adicionar.

### `config/database.sql`
Script SQL com o comando de criação do banco e da tabela, caso queira rodar direto no phpMyAdmin.

### `actions/` (ações do backend)
- **`create.php`**: Recebe os dados do formulário via POST, valida se nome e preço foram preenchidos, faz o `INSERT` no banco usando prepared statement e redireciona de volta para a lista.
- **`update.php`**: Recebe o ID e os novos dados via POST, valida os campos, roda o `UPDATE` e redireciona.
- **`delete.php`**: Recebe o ID via GET, roda o `DELETE` no banco e redireciona.

### `includes/`
- **`header.php`**: Cabeçalho do HTML com a barra de navegação, chamada das fontes e do CSS.
- **`footer.php`**: Rodapé simples com o fechamento das tags do layout.

### `assets/`
- **`style.css`**: Todo o visual do sistema (cards, formulário, botões, responsividade para celular).
- **`icons/`**: Arquivos locais do Bootstrap Icons (CSS e fontes), para não depender de internet para carregar os ícones.
