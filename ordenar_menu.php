<?php
session_start();
include 'includes/db.php';
$order = $_GET['order'] ?? 'nombre';
$menuItems = $conn->query("SELECT * FROM menu ORDER BY $order")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Menú Ordenado por <?= htmlspecialchars($order) ?></h2>
<ul>
<?php foreach ($menuItems as $item): ?>
    <li><?= $item['nombre'] ?> - $<?= $item['precio'] ?></li>
<?php endforeach; ?>
</ul>
<a href="index.php">Volver</a>
