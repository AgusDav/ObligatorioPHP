<?php
include 'db.php';
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = login($conn, $username, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales inválidas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login – Restaurante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Restaurante</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <?php if(isLoggedIn()): ?>
            <li class="nav-item"><a class="nav-link" href="#">Hola, <?=htmlspecialchars($_SESSION['user']['username'])?></a></li>
            <?php if(isAdmin()): ?><li class="nav-item"><a class="nav-link" href="admin/menu_list.php">Panel Admin</a></li><?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Salir</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Registro</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="card shadow-sm w-100" style="max-width:400px;">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
        <?php if(isset($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <p class="text-center mt-3">¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
