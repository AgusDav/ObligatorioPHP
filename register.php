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
<html>
<head><title>Registro</title></head>
<body>
<h2>Registro</h2>
<form method="post">
    Usuario: <input type="text" name="username"><br>
    Contraseña: <input type="password" name="password"><br>
    Rol:
    <select name="role">
        <option value="cliente">Cliente</option>
        <option value="admin">Admin</option>
    </select><br>
    <button type="submit">Registrar</button>
</form>
</body>
</html>
