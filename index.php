<?php
require_once 'functions.php';

// ② Asegura que exista el array de carrito
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ③ Procesa acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
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

// ④ Sorting y carga de items
$sort = $_GET['sort'] ?? '';
$orderSql = match($sort) {
    'price_asc'  => 'ORDER BY precio ASC',
    'price_desc' => 'ORDER BY precio DESC',
    'name_asc'   => 'ORDER BY nombre ASC',
    'name_desc'  => 'ORDER BY nombre DESC',
    default      => ''
};
$items = $pdo->query("SELECT * FROM menu $orderSql")->fetchAll();

// ⑤ Obtén favoritos y carrito actuales
$favorites = isLoggedIn()
    ? getFavorites($pdo, $_SESSION['user']['id'])
    : [];
$cart = getCart();
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
         <li class="nav-item">
           <a class="nav-link" href="favoritos.php">Favoritos</a>
         </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Salir</a>
          </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Registro</a></li>
          <?php endif; ?>
        </ul>
        <form method="get" class="d-flex ms-3">
          <select name="sort" class="form-select me-2">
            <option value="">Ordenar por</option>
            <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected':'' ?>>
              Precio ↑
            </option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected':'' ?>>
              Precio ↓
            </option>
            <option value="name_asc"   <?= $sort === 'name_asc'   ? 'selected':'' ?>>
              Nombre A→Z
            </option>
            <option value="name_desc"  <?= $sort === 'name_desc'  ? 'selected':'' ?>>
              Nombre Z→A
            </option>
          </select>
          <button class="btn btn-outline-light">Aplicar</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main class="container my-5">
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

              <!-- Botones de favorito y carrito -->
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
