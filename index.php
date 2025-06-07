<?php
require_once 'functions.php';

// ② Asegura que exista el array de carrito solo para clientes
if (isClient() && !isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ③ Procesa acciones POST solo para clientes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isClient()) {
    $action = $_POST['action']   ?? '';
    $itemId = intval($_POST['item_id'] ?? 0);
    $userId = $_SESSION['user']['id'];

    switch ($action) {
        case 'add_fav':
            addFavorite($pdo, $userId, $itemId);
            break;
        case 'remove_fav':
            removeFavorite($pdo, $userId, $itemId);
            break;
        case 'add_cart':
            addCart($itemId);
            break;
        case 'remove_cart':
            removeCart($itemId);
            break;
    }

    header('Location: index.php');
    exit;
}

// También manejar vaciado de carrito via GET solo para clientes
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] === '1' && isClient()) {
    clearCart();
    header('Location: index.php');
    exit;
}

// Sorting y carga de items
$sort = $_GET['sort'] ?? '';
$orderSql = match($sort) {
    'price_asc'  => 'ORDER BY precio ASC',
    'price_desc' => 'ORDER BY precio DESC',
    'name_asc'   => 'ORDER BY nombre ASC',
    'name_desc'  => 'ORDER BY nombre DESC',
    default      => ''
};
$items = $pdo->query("SELECT * FROM menu $orderSql")->fetchAll();

// Obtén favoritos y carrito actuales solo para clientes
$favorites = isClient() ? getFavorites($pdo, $_SESSION['user']['id']) : [];
$cart = isClient() ? getCart() : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menú – Restaurante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Restaurante</a>
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <?php if (isLoggedIn()): ?>
          <li class="nav-item">
            <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
          <?php if (isAdmin()): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin/menu_list.php">Panel Admin</a>
            </li>
          <?php endif; ?>
          
          <!-- Solo mostrar Favoritos y Carrito para clientes -->
          <?php if (isClient()): ?>
            <li class="nav-item">
              <a class="nav-link" href="favoritos.php">Favoritos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="carrito.php">
                Carrito
                <?php if (!empty($cart)): ?>
                  <span class="badge bg-warning text-dark"><?= count($cart) ?></span>
                <?php endif; ?>
              </a>
            </li>
          <?php endif; ?>
          
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Salir</a>
          </li>
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
    
    <!-- Sección de ordenamiento -->
    <div class="row mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">Nuestro Menú</h2>
        <p class="text-muted">Descubre nuestros deliciosos platos</p>
      </div>
      <div class="col-md-6">
        <form method="get" class="d-flex justify-content-md-end">
          <div class="d-flex align-items-center">
            <label class="form-label me-2 mb-0">Ordenar por:</label>
            <select name="sort" class="form-select me-2" style="width: auto;">
              <option value="">Seleccionar</option>
              <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected':'' ?>>
                Precio: Menor a Mayor
              </option>
              <option value="price_desc" <?= $sort === 'price_desc' ? 'selected':'' ?>>
                Precio: Mayor a Menor
              </option>
              <option value="name_asc"   <?= $sort === 'name_asc'   ? 'selected':'' ?>>
                Nombre: A - Z
              </option>
              <option value="name_desc"  <?= $sort === 'name_desc'  ? 'selected':'' ?>>
                Nombre: Z - A
              </option>
            </select>
            <button type="submit" class="btn btn-primary">Aplicar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Grid del menú -->
    <div class="row g-4">
      <?php foreach ($items as $i): ?>
        <div class="col-sm-6 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($i['nombre']) ?></h5>
              <p class="card-text flex-grow-1">
                <?= htmlspecialchars($i['descripcion']) ?>
              </p>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between">

              <!-- Botones de favorito y carrito - Solo para clientes -->
              <?php if (isClient()): ?>
                <div class="btn-group" role="group">
                  <!-- Favorito -->
                  <form method="post" class="d-inline">
                    <input type="hidden" name="item_id" value="<?= $i['id'] ?>">
                    <input type="hidden" name="action"
                      value="<?= in_array($i['id'], $favorites) ? 'remove_fav' : 'add_fav' ?>">
                    <button type="submit"
                      class="btn btn-sm <?= in_array($i['id'], $favorites)
                        ? 'btn-outline-danger' : 'btn-outline-secondary' ?>">
                      <?= in_array($i['id'], $favorites) ? '♥' : '♡' ?>
                    </button>
                  </form>

                  <!-- Carrito -->
                  <form method="post" class="d-inline">
                    <input type="hidden" name="item_id" value="<?= $i['id'] ?>">
                    <input type="hidden" name="action"
                      value="<?= in_array($i['id'], $cart) ? 'remove_cart' : 'add_cart' ?>">
                    <button type="submit"
                      class="btn btn-sm <?= in_array($i['id'], $cart)
                        ? 'btn-warning' : 'btn-success' ?>">
                      <?= in_array($i['id'], $cart)
                        ? 'Eliminar del carrito'
                        : 'Añadir al carrito' ?>
                    </button>
                  </form>
                </div>
              <?php else: ?>
                <!-- Para admins o usuarios no logueados, solo mostrar un espacio -->
                <div class="btn-group" role="group">
                  <?php if (isLoggedIn()): ?>
                    <small class="text-muted">Panel de administrador</small>
                  <?php else: ?>
                    <small class="text-muted">
                      <a href="login.php" class="text-decoration-none">Inicia sesión</a> para interactuar
                    </small>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <!-- Precio -->
              <span class="fw-bold">$<?= number_format($i['precio'], 2) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
  </script>
</body>
</html>
