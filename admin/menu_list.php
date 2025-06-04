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
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="../index.php">Restaurante</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Ver Menú</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="menu_list.php">Panel Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../logout.php">Salir</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <main class="container mt-4">
    <h2 class="mb-4">Panel de Administración - Menú</h2>
    <a href="add_menu.php" class="btn btn-success mb-3">Agregar Item</a>
    <table class="table table-striped">
      <thead><tr><th>Nombre</th><th>Precio</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php foreach ($items as $i): ?>
        <tr>
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
  </main>
</body>
</html>
