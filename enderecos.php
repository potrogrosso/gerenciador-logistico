<?php include 'includes/header.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = [$_POST['rotulo'], $_POST['logradouro'], $_POST['numero'], $_POST['cidade'], $_POST['uf'], $_POST['cep']];
  if (!empty($_POST['id'])) $pdo->prepare("UPDATE enderecos SET rotulo=?, logradouro=?, numero=?, cidade=?, uf=?, cep=? WHERE id=?")->execute([...$d, $_POST['id']]);
  else $pdo->prepare("INSERT INTO enderecos (rotulo, logradouro, numero, cidade, uf, cep) VALUES (?,?,?,?,?,?)")->execute($d);
  header('Location: enderecos.php'); exit;
}
if (isset($_GET['excluir'])) {
  try { $pdo->prepare("DELETE FROM enderecos WHERE id=?")->execute([$_GET['excluir']]); }
  catch (Exception $ex) { $msg = 'Não é possível excluir: endereço usado em um frete.'; }
}
$ed = ['id'=>'','rotulo'=>'','logradouro'=>'','numero'=>'','cidade'=>'','uf'=>'','cep'=>''];
if (isset($_GET['editar'])) { $s = $pdo->prepare("SELECT * FROM enderecos WHERE id=?"); $s->execute([$_GET['editar']]); $ed = $s->fetch() ?: $ed; }
$lista = $pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll();
?>
<h2>Endereços (origem / destino)</h2>
<?php if ($msg): ?><div class="alert alert-danger"><?= $msg ?></div><?php endif; ?>
<form method="post" class="card card-body shadow-sm mb-4 row g-3 flex-row">
  <input type="hidden" name="id" value="<?= e($ed['id']) ?>">
  <div class="col-md-4"><label class="form-label">Identificação (ex: Depósito SP)</label><input name="rotulo" class="form-control" required value="<?= e($ed['rotulo']) ?>"></div>
  <div class="col-md-5"><label class="form-label">Logradouro</label><input name="logradouro" class="form-control" value="<?= e($ed['logradouro']) ?>"></div>
  <div class="col-md-3"><label class="form-label">Número</label><input name="numero" class="form-control" value="<?= e($ed['numero']) ?>"></div>
  <div class="col-md-5"><label class="form-label">Cidade</label><input name="cidade" class="form-control" value="<?= e($ed['cidade']) ?>"></div>
  <div class="col-md-2"><label class="form-label">UF</label><input name="uf" maxlength="2" class="form-control" value="<?= e($ed['uf']) ?>"></div>
  <div class="col-md-3"><label class="form-label">CEP</label><input name="cep" class="form-control" value="<?= e($ed['cep']) ?>"></div>
  <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100">Salvar</button></div>
</form>
<table class="table table-striped bg-white shadow-sm">
  <tr><th>ID</th><th>Identificação</th><th>Endereço</th><th>CEP</th><th></th></tr>
  <?php foreach ($lista as $x): ?>
  <tr><td><?= $x['id'] ?></td><td><?= e($x['rotulo']) ?></td>
    <td><?= e("{$x['logradouro']}, {$x['numero']} - {$x['cidade']}/{$x['uf']}") ?></td><td><?= e($x['cep']) ?></td>
    <td class="text-end"><a class="btn btn-sm btn-warning" href="?editar=<?= $x['id'] ?>">Editar</a>
    <a class="btn btn-sm btn-danger" href="?excluir=<?= $x['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a></td></tr>
  <?php endforeach; ?>
</table>
<?php include 'includes/footer.php'; ?>
