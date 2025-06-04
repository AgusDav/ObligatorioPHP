<?php
require_once 'functions.php';

// Verificar que el usuario sea cliente
redirectIfNotClient();

$user_id = $_SESSION['user']['id'];

// Procesar acciones de favoritos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'];
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO favoritos (user_id, menu_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $menu_id]);
    } elseif (isset($_POST['remove'])) {
        $stmt = $pdo->prepare("DELETE FROM favoritos WHERE user_id = ? AND menu_id = ?");
        $stmt->execute([$user_id, $menu_id]);
    }
    header('Location: favoritos.php');
    exit;
}

// Obtener favoritos del usuario
$stmt = $pdo->prepare("SELECT m.* FROM favoritos f JOIN menu m ON f.menu_id = m.id WHERE f.user_id = ? ORDER BY m.nombre");
$stmt->execute([$user_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener carrito actual para mostrar estado
$cart = getCart();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mis Favoritos – Restaurante</title>
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
          <li class="nav-item">
            <span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
          <?php if (isAdmin()): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin/menu_list.php">Panel Admin</a>
            </li>
          <?php endif; ?>
          <!-- Solo mostrar enlaces para clientes -->
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
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Mis Favoritos</h2>
      <a href="index.php" class="btn btn-outline-primary">← Volver al Menú</a>
    </div>

    <?php if (empty($favoritos)): ?>
      <!-- Sin favoritos -->
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body text-center py-5">
              <div class="mb-4">
                <svg width="64" height="64" fill="currentColor" class="bi bi-heart text-muted" viewBox="0 0 16 16">
                  <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                </svg>
              </div>
              <h4 class="text-muted">No tienes favoritos aún</h4>
              <p class="text-muted">Explora nuestro menú y marca tus platos favoritos</p>
              <a href="index.php" class="btn btn-primary">Explorar Menú</a>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <!-- Grid de favoritos -->
      <div class="row g-4">
        <?php foreach ($favoritos as $item): ?>
          <div class="col-sm-6 col-md-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($item['nombre']) ?></h5>
                <p class="card-text flex-grow-1">
                  <?= htmlspecialchars($item['descripcion']) ?>
                </p>
              </div>
              <div class="card-footer bg-transparent d-flex justify-content-between">
                
                <!-- Botones de acción -->
                <div class="btn-group" role="group">
                  <!-- Quitar de favoritos -->
                  <form method="post" class="d-inline">
                    <input type="hidden" name="menu_id" value="<?= $item['id'] ?>">
                    <button type="submit" name="remove" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('¿Quitar de favoritos?')">
                      ♥
                    </button>
                  </form>

                  <!-- Agregar/Quitar del carrito -->
                  <form method="post" action="index.php" class="d-inline">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="action"
                           value="<?= in_array($item['id'], $cart) ? 'remove_cart' : 'add_cart' ?>">
                    <button type="submit"
                            class="btn btn-sm <?= in_array($item['id'], $cart) ? 'btn-warning' : 'btn-success' ?>">
                      <?= in_array($item['id'], $cart) ? 'Eliminar del carrito' : 'Añadir al carrito' ?>
                    </button>
                  </form>
                </div>

                <!-- Precio -->
                <span class="fw-bold">$<?= number_format($item['precio'], 2) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>