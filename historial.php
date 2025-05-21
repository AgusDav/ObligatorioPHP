<?php
session_start();
include 'includes/db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM compras WHERE user_id = ? ORDER BY fecha DESC");
$stmt->execute([$user_id]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Historial de Compras</h2>
<ul>
<?php foreach ($compras as $compra): ?>
    <li><?= $compra['fecha'] ?> - <?= $compra['detalle'] ?></li>
<?php endforeach; ?>
</ul>
<a href="index.php">Volver</a>
