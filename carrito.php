<?php
require_once 'functions.php';

// Verificar que el usuario sea cliente
redirectIfNotClient();

// Procesar eliminación de items del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $itemId = intval($_POST['item_id'] ?? 0);
    
    if ($action === 'remove_cart') {
        removeCart($itemId);
        header('Location: carrito.php');
        exit;
    }
}

// Obtener items del carrito
$cart = getCart();
$cartItems = [];
$total = 0;

if (!empty($cart)) {
    $placeholders = str_repeat('?,', count($cart) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id IN ($placeholders)");
    $stmt->execute($cart);
    $cartItems = $stmt->fetchAll();
    
    // Calcular total
    foreach ($cartItems as $item) {
        $total += $item['precio'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrito de Compras – Restaurante</title>
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
              <a class="nav-link" href="carrito.php">Carrito</a>
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
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Mi Carrito de Compras</h2>
          <a href="index.php" class="btn btn-outline-primary">← Seguir comprando</a>
        </div>
        
        <?php if (empty($cartItems)): ?>
          <!-- Carrito vacío -->
          <div class="card">
            <div class="card-body text-center py-5">
              <div class="mb-4">
                <svg width="64" height="64" fill="currentColor" class="bi bi-cart text-muted" viewBox="0 0 16 16">
                  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
              </div>
              <h4 class="text-muted">Tu carrito está vacío</h4>
              <p class="text-muted">Agrega algunos deliciosos platos desde nuestro menú</p>
              <a href="index.php" class="btn btn-primary">Ver Menú</a>
            </div>
          </div>
        <?php else: ?>
          <!-- Items del carrito -->
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Items en tu carrito (<?= count($cartItems) ?>)</h5>
            </div>
            <div class="card-body p-0">
              <?php foreach ($cartItems as $item): ?>
                <div class="d-flex align-items-center p-3 border-bottom">
                  <div class="flex-grow-1">
                    <h6 class="mb-1"><?= htmlspecialchars($item['nombre']) ?></h6>
                    <p class="text-muted mb-0 small"><?= htmlspecialchars($item['descripcion']) ?></p>
                  </div>
                  <div class="text-end me-3">
                    <span class="fw-bold">$<?= number_format($item['precio'], 2) ?></span>
                  </div>
                  <div>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                      <input type="hidden" name="action" value="remove_cart">
                      <button type="submit" class="btn btn-outline-danger btn-sm"
                              onclick="return confirm('¿Eliminar este item del carrito?')">
                        <svg width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            
            <!-- Resumen del carrito -->
            <div class="card-footer bg-light">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold text-primary fs-5">$<?= number_format($total, 2) ?></span>
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <!-- Botón deshabilitado para finalizar compra -->
                  <button class="btn btn-success btn-lg" disabled>
                    <svg width="16" height="16" fill="currentColor" class="bi bi-credit-card me-2" viewBox="0 0 16 16">
                      <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                      <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                    </svg>
                    Finalizar Compra (Próximamente)
                  </button>
                  <div class="small text-muted mt-1">
                    La funcionalidad de compra estará disponible pronto
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Acciones adicionales -->
          <div class="row mt-4">
            <div class="col-md-6">
              <a href="index.php" class="btn btn-outline-primary w-100">
                <svg width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Seguir Comprando
              </a>
            </div>
            <div class="col-md-6">
              <form method="post" onsubmit="return confirm('¿Vaciar todo el carrito?')">
                <input type="hidden" name="action" value="clear_cart">
                <button type="button" class="btn btn-outline-danger w-100"
                        onclick="if(confirm('¿Vaciar todo el carrito?')) { window.location.href='index.php?clear_cart=1'; }">
                  <svg width="16" height="16" fill="currentColor" class="bi bi-trash me-2" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                  </svg>
                  Vaciar Carrito
                </button>
              </form>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
