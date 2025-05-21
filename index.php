<?php
require_once 'functions.php';

// Procesar acciones de favoritos y carrito (si se implementan en funciones extras)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $action = $_POST['action'] ?? '';
    $itemId = intval($_POST['item_id'] ?? 0);
    switch ($action) {
        case 'add_fav':
            // lógica para agregar a favoritos
            break;
        case 'remove_fav':
            // lógica para quitar favorito
            break;
        case 'add_cart':
            // lógica para agregar al carrito
            break;
        case 'remove_cart':
            // lógica para quitar del carrito
            break;
    }
    header('Location: index.php');
    exit;
}

// Ordenamiento
$sort = $_GET['sort'] ?? '';
$orderSql = '';
switch ($sort) {
    case 'price_asc':    $orderSql = 'ORDER BY precio ASC'; break;
    case 'price_desc':   $orderSql = 'ORDER BY precio DESC'; break;
    case 'name_asc':     $orderSql = 'ORDER BY nombre ASC'; break;
    case 'name_desc':    $orderSql = 'ORDER BY nombre DESC'; break;
    default:             $orderSql = '';
}

// Consulta de items
$query = "SELECT * FROM menu_items $orderSql";
$items = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menú – Restaurante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Restaurante</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <?php if (isLoggedIn()): ?>
            <li class="nav-item"><span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['user']['username']) ?></span></li>
            <?php if (isAdmin()): ?>
              <li class="nav-item"><a class="nav-link" href="admin/menu_list.php">Panel Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Salir</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Registro</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="mb-0">Nuestro Menú</h1>
      <!-- Filtro de ordenamiento -->
      <form method="get" class="d-flex">
        <select name="sort" class="form-select me-2">
          <option value="">Ordenar por</option>
          <option value="price_asc"<?= $sort=='price_asc'?' selected':''?>>Precio ↑</option>
          <option value="price_desc"<?= $sort=='price_desc'?' selected':''?>>Precio ↓</option>
          <option value="name_asc"<?= $sort=='name_asc'?' selected':''?>>Nombre A→Z</option>
          <option value="name_desc"<?= $sort=='name_desc'?' selected':''?>>Nombre Z→A</option>
        </select>
        <button class="btn btn-outline-secondary">Aplicar</button>
      </form>
    </div>

    <div class="row g-4">
      <?php foreach ($items as $i): ?>
        <div class="col-sm-6 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($i['nombre']) ?></h5>
              <p class="card-text flex-grow-1"><?= htmlspecialchars($i['descripcion']) ?></p>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
              <span class="fw-bold">$<?= number_format($i['precio'], 2) ?></span>
              <?php if (isLoggedIn()): ?>
                <form method="post" class="d-inline">
                  <input type="hidden" name="item_id" value="<?= $i['id'] ?>">
                  <input type="hidden" name="action" value="add_cart">
                  <button class="btn btn-primary btn-sm">Añadir al carrito</button>
                </form>
              <?php else: ?>
                <a href="login.php" class="btn btn-secondary btn-sm">Login para comprar</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
