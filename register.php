<?php
include 'db.php';
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    register($conn, $username, $password, $role);
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro – Restaurante</title>
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
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><span class="nav-link">Hola, <?= htmlspecialchars($_SESSION['user']['username']) ?></span></li>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
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

  <!-- Formulario de Registro -->
  <main class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-sm w-100" style="max-width: 400px;">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Crear Cuenta</h3>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="role" class="form-select" required>
              <option value="cliente" <?= (!isset($_POST['role']) || $_POST['role']==='cliente')?'selected':''?>>Cliente</option>
              <option value="admin"   <?= (isset($_POST['role']) && $_POST['role']==='admin')?'selected':''?>>Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Registrarse</button>
        </form>
        <p class="text-center mt-3">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
