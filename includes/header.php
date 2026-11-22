<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gerenciador Logístico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">🚚 Logística</a>
    <div class="navbar-nav">
      <a class="nav-link" href="produtos.php">Produtos</a>
      <a class="nav-link" href="enderecos.php">Endereços</a>
      <a class="nav-link" href="transportadores.php">Transportadores</a>
      <a class="nav-link" href="fretes.php">Fretes</a>
      <a class="nav-link" href="relatorio.php">Relatório</a>
    </div>
  </div>
</nav>
<div class="container">
