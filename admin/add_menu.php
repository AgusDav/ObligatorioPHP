<?php
require_once '../functions.php';
redirectIfNotAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stmt = $pdo->prepare('INSERT INTO menu (nombre, descripcion, precio) VALUES (?, ?, ?)');
    $stmt->execute([$nombre, $descripcion, $precio]);
    header('Location: menu_list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agregar Item – Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <a href="menu_list.php" class="btn btn-link mb-3">← Volver</a>
    <h2 class="mb-4">Agregar Item al Menú</h2>
    <form method="post" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Precio</label>
        <input type="number" step="0.01" name="precio" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Guardar</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
