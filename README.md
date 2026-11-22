# Gerenciador Logístico

Sistema web simples para gerenciar fretes, desenvolvido em **PHP**, **HTML/CSS/JavaScript** e **Bootstrap 5**, com banco **SQLite**.

## Funcionalidades
- Cadastro, edição e exclusão de **produtos**, **endereços** (origem/destino) e **transportadores**
- Geração de **fretes** ligando origem, destino e transportador (1 transportador → vários produtos)
- Campos de produtos adicionados dinamicamente com JavaScript
- **Relatório de fretes por dia** com download em CSV
- Menu de navegação em todas as telas

## Estrutura
```
config/db.php        conexão e criação das tabelas
includes/            header (menu) e footer
data/                banco SQLite (criado automaticamente)
produtos.php, enderecos.php, transportadores.php, fretes.php, relatorio.php, index.php
```

## Como executar (XAMPP)
1. Copie a pasta para `C:\xampp\htdocs\gerenciador-logistico`
2. Inicie o **Apache** no painel do XAMPP
3. Acesse `http://localhost/gerenciador-logistico/`

> Requer a extensão `pdo_sqlite`, já habilitada por padrão no XAMPP.

## Modelo de dados
`produtos`, `enderecos`, `transportadores`, `fretes` (FKs para origem, destino e transportador) e `frete_itens` (frete × produto × quantidade).
