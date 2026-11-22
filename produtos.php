<?php include 'includes/header.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = [$_POST['nome'], $_POST['peso'], $_POST['valor']];
  if (!empty($_POST['id'])) $pdo->prepare("UPDATE produtos SET nome=?, peso=?, valor=? WHERE id=?")->execute([...$d, $_POST['id']]);
  else $pdo->prepare("INSERT INTO produtos (nome, peso, valor) VALUES (?,?,?)")->execute($d);
  header('Location: produtos.php'); exit;
}
if (isset($_GET['excluir'])) {
  try { $pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$_GET['excluir']]); }
  catch (Exception $ex) { $msg = 'Não é possível excluir: produto usado em um frete.'; }
}
$ed = ['id'=>'','nome'=>'','peso'=>'','valor'=>''];
if (isset($_GET['editar'])) { $s = $pdo->prepare("SELECT * FROM produtos WHERE id=?"); $s->execute([$_GET['editar']]); $ed = $s->fetch() ?: $ed; }
$lista = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
?>
<h2>Produtos</h2>
<?php if ($msg): ?><div class="alert alert-danger"><?= $msg ?></div><?php endif; ?>
<form method="post" class="card card-body shadow-sm mb-4 row g-3 flex-row">
  <input type="hidden" name="id" value="<?= e($ed['id']) ?>">
  <div class="col-md-5"><label class="form-label">Nome</label><input name="nome" class="form-control" required value="<?= e($ed['nome']) ?>"></div>
  <div class="col-md-3"><label class="form-label">Peso (kg)</label><input name="peso" type="number" step="0.01" class="form-control" value="<?= e($ed['peso']) ?>"></div>
  <div class="col-md-3"><label class="form-label">Valor (R$)</label><input name="valor" type="number" step="0.01" class="form-control" value="<?= e($ed['valor']) ?>"></div>
  <div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary w-100">Salvar</button></div>
</form>
<table class="table table-striped bg-white shadow-sm">
  <tr><th>ID</th><th>Nome</th><th>Peso</th><th>Valor</th><th></th></tr>
  <?php foreach ($lista as $p): ?>
  <tr><td><?= $p['id'] ?></td><td><?= e($p['nome']) ?></td><td><?= e($p['peso']) ?> kg</td><td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
    <td class="text-end"><a class="btn btn-sm btn-warning" href="?editar=<?= $p['id'] ?>">Editar</a>
    <a class="btn btn-sm btn-danger" href="?excluir=<?= $p['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a></td></tr>
  <?php endforeach; ?>
</table>
<?php include 'includes/footer.php'; ?>
