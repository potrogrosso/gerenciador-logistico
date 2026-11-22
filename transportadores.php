<?php include 'includes/header.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $d = [$_POST['nome'], $_POST['documento'], $_POST['telefone']];
  if (!empty($_POST['id'])) $pdo->prepare("UPDATE transportadores SET nome=?, documento=?, telefone=? WHERE id=?")->execute([...$d, $_POST['id']]);
  else $pdo->prepare("INSERT INTO transportadores (nome, documento, telefone) VALUES (?,?,?)")->execute($d);
  header('Location: transportadores.php'); exit;
}
if (isset($_GET['excluir'])) {
  try { $pdo->prepare("DELETE FROM transportadores WHERE id=?")->execute([$_GET['excluir']]); }
  catch (Exception $ex) { $msg = 'Não é possível excluir: transportador usado em um frete.'; }
}
$ed = ['id'=>'','nome'=>'','documento'=>'','telefone'=>''];
if (isset($_GET['editar'])) { $s = $pdo->prepare("SELECT * FROM transportadores WHERE id=?"); $s->execute([$_GET['editar']]); $ed = $s->fetch() ?: $ed; }
$lista = $pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll();
?>
<h2>Transportadores</h2>
<?php if ($msg): ?><div class="alert alert-danger"><?= $msg ?></div><?php endif; ?>
<form method="post" class="card card-body shadow-sm mb-4 row g-3 flex-row">
  <input type="hidden" name="id" value="<?= e($ed['id']) ?>">
  <div class="col-md-5"><label class="form-label">Nome</label><input name="nome" class="form-control" required value="<?= e($ed['nome']) ?>"></div>
  <div class="col-md-3"><label class="form-label">CPF/CNPJ</label><input name="documento" class="form-control" value="<?= e($ed['documento']) ?>"></div>
  <div class="col-md-3"><label class="form-label">Telefone</label><input name="telefone" class="form-control" value="<?= e($ed['telefone']) ?>"></div>
  <div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary w-100">Salvar</button></div>
</form>
<table class="table table-striped bg-white shadow-sm">
  <tr><th>ID</th><th>Nome</th><th>Documento</th><th>Telefone</th><th></th></tr>
  <?php foreach ($lista as $t): ?>
  <tr><td><?= $t['id'] ?></td><td><?= e($t['nome']) ?></td><td><?= e($t['documento']) ?></td><td><?= e($t['telefone']) ?></td>
    <td class="text-end"><a class="btn btn-sm btn-warning" href="?editar=<?= $t['id'] ?>">Editar</a>
    <a class="btn btn-sm btn-danger" href="?excluir=<?= $t['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a></td></tr>
  <?php endforeach; ?>
</table>
<?php include 'includes/footer.php'; ?>
