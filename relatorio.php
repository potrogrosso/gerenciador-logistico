<?php
require_once 'config/db.php';
$data = $_GET['data'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
  SELECT f.id, f.data, o.rotulo AS origem, d.rotulo AS destino, t.nome AS transportador,
         p.nome AS produto, i.quantidade, p.valor
  FROM fretes f
  JOIN enderecos o ON o.id = f.origem_id
  JOIN enderecos d ON d.id = f.destino_id
  JOIN transportadores t ON t.id = f.transportador_id
  LEFT JOIN frete_itens i ON i.frete_id = f.id
  LEFT JOIN produtos p ON p.id = i.produto_id
  WHERE f.data = ? ORDER BY f.id");
$stmt->execute([$data]);
$linhas = $stmt->fetchAll();

// Download em CSV (abre no Excel)
if (isset($_GET['download'])) {
  header('Content-Type: text/csv; charset=utf-8');
  header("Content-Disposition: attachment; filename=fretes_$data.csv");
  echo "\xEF\xBB\xBF";
  $out = fopen('php://output', 'w');
  fputcsv($out, ['Frete', 'Data', 'Origem', 'Destino', 'Transportador', 'Produto', 'Qtd', 'Valor unit.'], ';');
  foreach ($linhas as $l) fputcsv($out, [$l['id'], $l['data'], $l['origem'], $l['destino'], $l['transportador'], $l['produto'], $l['quantidade'], $l['valor']], ';');
  fclose($out); exit;
}
include 'includes/header.php';
?>
<h2>Relatório de fretes por dia</h2>
<form class="row g-2 mb-4">
  <div class="col-auto"><input type="date" name="data" value="<?= e($data) ?>" class="form-control"></div>
  <div class="col-auto"><button class="btn btn-primary">Filtrar</button></div>
  <div class="col-auto"><button name="download" value="1" class="btn btn-success">⬇ Baixar CSV</button></div>
</form>
<table class="table table-striped bg-white shadow-sm">
  <tr><th>Frete</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Produto</th><th>Qtd</th><th>Valor</th></tr>
  <?php foreach ($linhas as $l): ?>
  <tr><td><?= $l['id'] ?></td><td><?= e($l['origem']) ?></td><td><?= e($l['destino']) ?></td><td><?= e($l['transportador']) ?></td>
    <td><?= e($l['produto']) ?></td><td><?= $l['quantidade'] ?></td><td>R$ <?= number_format($l['valor'], 2, ',', '.') ?></td></tr>
  <?php endforeach; if (!$linhas): ?><tr><td colspan="7" class="text-center text-muted">Nenhum frete nesta data.</td></tr><?php endif; ?>
</table>
<?php include 'includes/footer.php'; ?>
