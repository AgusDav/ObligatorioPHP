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
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Usuario: <input type="text" name="username"><br>
    Contraseña: <input type="password" name="password"><br>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
