<?php
// Conexão SQLite (arquivo criado automaticamente em /data)
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/logistica.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->exec("PRAGMA foreign_keys = ON");

$pdo->exec("
CREATE TABLE IF NOT EXISTS produtos (
  id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT NOT NULL, peso REAL, valor REAL);
CREATE TABLE IF NOT EXISTS enderecos (
  id INTEGER PRIMARY KEY AUTOINCREMENT, rotulo TEXT NOT NULL, logradouro TEXT, numero TEXT,
  cidade TEXT, uf TEXT, cep TEXT);
CREATE TABLE IF NOT EXISTS transportadores (
  id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT NOT NULL, documento TEXT, telefone TEXT);
CREATE TABLE IF NOT EXISTS fretes (
  id INTEGER PRIMARY KEY AUTOINCREMENT, data TEXT NOT NULL,
  origem_id INTEGER NOT NULL REFERENCES enderecos(id),
  destino_id INTEGER NOT NULL REFERENCES enderecos(id),
  transportador_id INTEGER NOT NULL REFERENCES transportadores(id));
CREATE TABLE IF NOT EXISTS frete_itens (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  frete_id INTEGER NOT NULL REFERENCES fretes(id) ON DELETE CASCADE,
  produto_id INTEGER NOT NULL REFERENCES produtos(id),
  quantidade INTEGER NOT NULL DEFAULT 1);
");

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
