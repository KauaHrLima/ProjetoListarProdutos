# EstoquePro — Gerenciador de Produtos

Sistema web para cadastro, listagem, edição e exclusão (CRUD) de produtos, desenvolvido em PHP moderno com PDO, MySQL e design responsivo com biblioteca local de ícones.

---

## 📋 Sumário
- [Funcionalidades](#-funcionalidades)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Explicação Detalhada do Código](#-explicação-detalhada-do-código)
  - [1. Configuração e Banco de Dados (`config/`)](#1-configuração-e-banco-de-dados-config)
  - [2. Camada de Ações (`actions/`)](#2-camada-de-ações-actions)
  - [3. Interface e Visualização](#3-interface-e-visualização)
  - [4. Componentes Compartilhados (`includes/`)](#4-componentes-compartilhados-includes)
  - [5. Estilização e Ícones (`assets/`)](#5-estilização-e-ícones-assets)
- [Como Executar o Projeto](#-como-executar-o-projeto)

---

## 🚀 Funcionalidades

- **Listagem de Produtos em Cards**: Visualização em grade responsiva com ícone temático, nome, descrição, preço formatado em Real (R$) e indicador visual de estoque.
- **Cadastro de Produtos**: Formulário intuitivo com nome, seletor visual de ícones por categoria, descrição, preço e quantidade em estoque.
- **Edição Dinâmica**: Reutilização inteligente do formulário para atualização de registros existentes.
- **Exclusão com Confirmação**: Remoção segura de itens com alerta de confirmação em JavaScript para evitar exclusões acidentais.
- **Auto-instalação do Banco**: O sistema verifica e cria automaticamente o banco de dados e a tabela se ainda não existirem na primeira execução.
- **Feedback Visual**: Alertas animados de sucesso e validação de erro após cada operação.
- **Ícones 100% Locais**: Utiliza Bootstrap Icons hospedado internamente na pasta do projeto, funcionando sem dependência de internet.

---

## 📂 Estrutura do Projeto

```text
ProjetoListarProdutos/
│
├── actions/
│   ├── create.php          # Processa o cadastro de novo produto
│   ├── update.php          # Processa a atualização de um produto existente
│   └── delete.php          # Processa a exclusão de um produto
│
├── assets/
│   ├── icons/
│   │   ├── fonts/          # Fontes woff e woff2 dos ícones
│   │   └── bootstrap-icons.css # Folha de estilo dos ícones locais
│   └── style.css           # Folha de estilo principal (design system)
│
├── config/
│   ├── conection.php       # Conexão PDO e auto-setup do banco de dados
│   └── database.sql        # Script SQL de referência
│
├── includes/
│   ├── header.php          # Cabeçalho HTML, importação de estilos e navegação
│   └── footer.php          # Rodapé com ano dinâmico e fechamento de tags
│
├── form.php                # Formulário de cadastro e edição de produtos
├── index.php               # Página inicial com o catálogo de produtos
└── README.md               # Documentação técnica do projeto
```

---

## 🛠 Tecnologias Utilizadas

- **PHP 8+**: Lógica de backend e renderização de páginas.
- **PDO (PHP Data Objects)**: Acesso seguro ao banco de dados com Prepared Statements contra injeção de SQL.
- **MySQL / MariaDB**: Armazenamento relacional dos dados.
- **Bootstrap Icons**: Mais de 2.000 ícones vetoriais embutidos localmente no projeto.
- **CSS3 Moderno**: Variáveis CSS (Custom Properties), CSS Grid, Flexbox e animações fluidas.
- **Google Fonts (Inter)**: Tipografia limpa e profissional.

---

## 🔍 Explicação Detalhada do Código

### 1. Configuração e Banco de Dados (`config/`)

#### `config/conection.php`
Responsável por estabelecer a conexão com o servidor MySQL através da classe `PDO`.
- Conecta inicialmente sem especificar a base para verificar se o banco `produtos` já existe via `CREATE DATABASE IF NOT EXISTS`.
- Seleciona o banco e cria a tabela `produtos` caso ainda não exista.
- Executa verificação dinâmica da coluna `icone` via `SHOW COLUMNS`; caso a tabela venha de versões antigas sem a coluna, aplica o `ALTER TABLE` automaticamente.
- Em caso de falha de conexão (ex.: MySQL parado no painel do XAMPP), interrompe o fluxo com um card de erro amigável ao desenvolvedor.

#### `config/database.sql`
Arquivo de apoio com a definição estrutural da tabela:
- `id`: Chave primária auto-incrementada.
- `nome`: Texto de até 150 caracteres (obrigatório).
- `descricao`: Texto detalhado do item.
- `preco`: Numérico decimal com 2 casas decimais (`DECIMAL(10,2)`).
- `estoque`: Inteiro com contagem de unidades disponíveis.
- `icone`: Classe CSS do ícone associado (ex.: `bi-laptop`, `bi-box-seam`).
- `criado_em`: Registro de data e hora do cadastro.

---

### 2. Camada de Ações (`actions/`)

Todas as ações utilizam **Prepared Statements** (`prepare()` e `execute()`), garantindo proteção completa contra ataques de *SQL Injection*.

#### `actions/create.php`
- Recebe os dados enviados via `POST` pelo formulário.
- Limpa espaços em branco com `trim()` e valida se `nome` e `preco` foram devidamente preenchidos.
- Define o ícone padrão (`bi-box-seam`) caso nenhum tenha sido escolhido.
- Se os dados forem válidos, grava o produto no banco e redireciona para `index.php?msg=criado`.
- Se inválido, redireciona de volta para `form.php?erro=1`.

#### `actions/update.php`
- Recebe o identificador `id` e os campos editados via `POST`.
- Valida a presença do `id` e das informações obrigatórias.
- Executa a cláusula `UPDATE produtos SET ... WHERE id = :id`.
- Ao concluir, redireciona para `index.php?msg=atualizado`.

#### `actions/delete.php`
- Recebe o parâmetro `id` via `GET`.
- Converte o valor para inteiro (`(int)$_GET['id']`). Se inválido ou zero, cancela o fluxo.
- Executa `DELETE FROM produtos WHERE id = :id`.
- Redireciona o usuário para `index.php?msg=deletado`.

---

### 3. Interface e Visualização

#### `index.php`
Página principal do sistema:
- Executa uma consulta `SELECT * FROM produtos ORDER BY criado_em DESC` para listar os itens do mais recente ao mais antigo.
- **Tratamento de Mensagens**: Avalia o parâmetro `$_GET['msg']` para exibir avisos de confirmação de cadastro, atualização ou exclusão.
- **Estado Vazio**: Se a tabela não contiver itens, exibe um painel ilustrado convidando o usuário a cadastrar seu primeiro produto.
- **Cards dos Produtos**: Cada produto renderiza seu próprio ícone (`icone`), status de estoque (com classe especial caso esteja zerado), valor monetário formatado com `number_format()` e atalhos de ação (Editar e Deletar).

#### `form.php`
Formulário inteligente e reutilizável:
- **Modo Criação vs. Edição**: Verifica se há `$_GET['id']`. Se houver, busca o registro correspondente e preenche os campos automaticamente; se não houver, prepara o formulário limpo para um novo registro.
- **Seletor Visual de Ícones**: Exibe uma grade interativa de ícones categorizados (Tecnologia, Moda, Games, Alimentos, Ferramentas, etc.). A seleção atualiza um campo de rádio invisível com a classe do ícone.
- **Validação no Cliente e Servidor**: Utiliza atributos `required`, `min` e `step` do HTML5 complementados pela validação PHP.

---

### 4. Componentes Compartilhados (`includes/`)

#### `includes/header.php`
- Declara as meta tags fundamentais, pré-carrega a fonte Inter e importa as folhas de estilo:
  - `/ProjetoListarProdutos/assets/icons/bootstrap-icons.css`
  - `/ProjetoListarProdutos/assets/style.css`
- Renderiza a barra de navegação superior fixa com logotipo e atalhos.

#### `includes/footer.php`
- Fecha a tag principal `<main>`.
- Exibe o rodapé com direitos autorais e ano gerado automaticamente pelo PHP via `date('Y')`.

---

### 5. Estilização e Ícones (`assets/`)

#### `assets/style.css`
- **Design System com Variáveis**: Cores principais, sombras, raios de borda e transições unificadas através de `:root`.
- **Layout Responsivo**: Utiliza `minmax()` no CSS Grid para ajustar o número de colunas conforme o tamanho da tela do dispositivo (computador, tablet ou celular).
- **Componentes**: Estilização de botões, badges de estoque, alertas animados com `@keyframes slideDown`, e cartões do seletor de ícones com estados de `:hover` e `:checked`.

#### `assets/icons/`
- Arquivo `bootstrap-icons.css` e pasta `fonts/` contendo os arquivos binários `.woff` e `.woff2`.
- Permite uso de qualquer classe `bi bi-*` em qualquer elemento do projeto sem depender de conexões externas.

---

## 💻 Como Executar o Projeto

1. Certifique-se de que o **XAMPP** está instalado.
2. Mantenha os arquivos do projeto dentro do diretório:
   ```text
   C:\xampp\htdocs\ProjetoListarProdutos
   ```
3. Abra o **XAMPP Control Panel** e inicie os módulos:
   - **Apache**
   - **MySQL**
4. Abra o navegador e acesse:
   ```text
   http://localhost/ProjetoListarProdutos/
   ```
5. O banco de dados e a tabela serão inicializados de forma automática na primeira abertura. Pronto para uso!
