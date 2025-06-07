<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO menu (nombre, descripcion, precio) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nombre'], $_POST['descripcion'], $_POST['precio']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
}
$items = getMenuItems($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head><title>Admin Menú</title></head>
<body>
<h2>Administrar Menú</h2>
<form method="post">
    Nombre: <input type="text" name="nombre"><br>
    Descripción: <input type="text" name="descripcion"><br>
    Precio: <input type="number" name="precio" step="0.01"><br>
    <button type="submit" name="add">Agregar</button>
</form>

<h3>Items Actuales</h3>
<ul>
<?php foreach ($items as $item): ?>
    <li><?= $item['nombre'] ?> - $<?= $item['precio'] ?>
        <form method="post" style="display:inline;">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <button type="submit" name="delete">Eliminar</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>
<p><a href="../index.php">Volver al inicio</a></p>
</body>
</html>
