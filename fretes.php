<?php include 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->beginTransaction();
  $pdo->prepare("INSERT INTO fretes (data, origem_id, destino_id, transportador_id) VALUES (?,?,?,?)")
      ->execute([$_POST['data'], $_POST['origem_id'], $_POST['destino_id'], $_POST['transportador_id']]);
  $freteId = $pdo->lastInsertId();
  $ins = $pdo->prepare("INSERT INTO frete_itens (frete_id, produto_id, quantidade) VALUES (?,?,?)");
  foreach ($_POST['produto_id'] as $i => $pid) {
    if ($pid) $ins->execute([$freteId, $pid, max(1, (int)$_POST['quantidade'][$i])]);
  }
  $pdo->commit();
  header('Location: fretes.php'); exit;
}
if (isset($_GET['excluir'])) $pdo->prepare("DELETE FROM fretes WHERE id=?")->execute([$_GET['excluir']]);

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();
$ends = $pdo->query("SELECT * FROM enderecos ORDER BY rotulo")->fetchAll();
$trans = $pdo->query("SELECT * FROM transportadores ORDER BY nome")->fetchAll();
$fretes = $pdo->query("
  SELECT f.id, f.data, o.rotulo AS origem, d.rotulo AS destino, t.nome AS transportador,
         GROUP_CONCAT(p.nome || ' (x' || i.quantidade || ')', ', ') AS itens
  FROM fretes f
  JOIN enderecos o ON o.id = f.origem_id
  JOIN enderecos d ON d.id = f.destino_id
  JOIN transportadores t ON t.id = f.transportador_id
  LEFT JOIN frete_itens i ON i.frete_id = f.id
  LEFT JOIN produtos p ON p.id = i.produto_id
  GROUP BY f.id ORDER BY f.id DESC")->fetchAll();

function opcoes($lista, $campo) { foreach ($lista as $x) echo "<option value='{$x['id']}'>" . e($x[$campo]) . "</option>"; }
?>
<h2>Fretes</h2>
<?php if (!$produtos || !$ends || !$trans): ?>
  <div class="alert alert-warning">Cadastre ao menos um produto, um endereço e um transportador antes de gerar um frete.</div>
<?php else: ?>
<form method="post" class="card card-body shadow-sm mb-4">
  <div class="row g-3">
    <div class="col-md-3"><label class="form-label">Data</label><input type="date" name="data" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
    <div class="col-md-3"><label class="form-label">Origem</label><select name="origem_id" class="form-select"><?php opcoes($ends, 'rotulo') ?></select></div>
    <div class="col-md-3"><label class="form-label">Destino</label><select name="destino_id" class="form-select"><?php opcoes($ends, 'rotulo') ?></select></div>
    <div class="col-md-3"><label class="form-label">Transportador</label><select name="transportador_id" class="form-select"><?php opcoes($trans, 'nome') ?></select></div>
  </div>
  <hr><h5>Produtos do frete</h5>
  <div id="itens"></div>
  <div class="mt-2"><button type="button" class="btn btn-outline-secondary" onclick="addItem()">+ Adicionar produto</button>
  <button class="btn btn-success">Gerar frete</button></div>
</form>

<template id="modelo">
  <div class="row g-2 mb-2 item">
    <div class="col-md-8"><select name="produto_id[]" class="form-select"><?php opcoes($produtos, 'nome') ?></select></div>
    <div class="col-md-3"><input type="number" name="quantidade[]" min="1" value="1" class="form-control"></div>
    <div class="col-md-1"><button type="button" class="btn btn-danger w-100" onclick="this.closest('.item').remove()">×</button></div>
  </div>
</template>
<script>
  // Adiciona dinamicamente uma nova linha de produto
  function addItem() {
    document.getElementById('itens').appendChild(document.getElementById('modelo').content.cloneNode(true));
  }
  addItem();
</script>
<?php endif; ?>

<table class="table table-striped bg-white shadow-sm">
  <tr><th>#</th><th>Data</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Produtos</th><th></th></tr>
  <?php foreach ($fretes as $f): ?>
  <tr><td><?= $f['id'] ?></td><td><?= date('d/m/Y', strtotime($f['data'])) ?></td><td><?= e($f['origem']) ?></td><td><?= e($f['destino']) ?></td>
    <td><?= e($f['transportador']) ?></td><td><?= e($f['itens']) ?></td>
    <td><a class="btn btn-sm btn-danger" href="?excluir=<?= $f['id'] ?>" onclick="return confirm('Excluir frete?')">Excluir</a></td></tr>
  <?php endforeach; ?>
</table>
<?php include 'includes/footer.php'; ?>
