<?php include 'includes/header.php';
$tot = fn($t) => $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn(); ?>
<h2 class="mb-4">Painel</h2>
<div class="row g-3">
  <?php foreach (['produtos'=>'Produtos','enderecos'=>'Endereços','transportadores'=>'Transportadores','fretes'=>'Fretes'] as $t=>$n): ?>
    <div class="col-md-3"><a href="<?= $t ?>.php" class="text-decoration-none">
      <div class="card text-center shadow-sm"><div class="card-body">
        <h1><?= $tot($t) ?></h1><p class="mb-0 text-muted"><?= $n ?></p>
      </div></div></a></div>
  <?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>
