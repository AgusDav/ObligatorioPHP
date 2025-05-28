<?php
require_once '../functions.php';
redirectIfNotAdmin();
$items = $pdo->query('SELECT * FROM menu')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin – Menú</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <a href="../index.php" class="btn btn-link">← Volver</a>
    <h2 class="mb-4">Panel de Administración - Menú</h2>
    <a href="add_menu.php" class="btn btn-success mb-3">Agregar Item</a>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php foreach ($items as $i): ?>
        <tr>
          <td><?= $i['id'] ?></td>
          <td><?= htmlspecialchars($i['nombre']) ?></td>
          <td>$<?= number_format($i['precio'],2) ?></td>
          <td>
            <a href="edit_menu.php?id=<?= $i['id']?>" class="btn btn-sm btn-primary">Editar</a>
            <a href="delete_menu.php?id=<?= $i['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar item?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
