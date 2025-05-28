<?php
require_once 'functions.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
$user_id = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'];
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO favoritos (user_id, menu_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $menu_id]);
    } elseif (isset($_POST['remove'])) {
        $stmt = $pdo->prepare("DELETE FROM favoritos WHERE user_id = ? AND menu_id = ?");
        $stmt->execute([$user_id, $menu_id]);
    }
}
$stmt = $pdo->prepare("SELECT m.* FROM favoritos f JOIN menu m ON f.menu_id = m.id WHERE f.user_id = ?");
$stmt->execute([$user_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Mis Favoritos</h2>
<ul>
<?php foreach ($favoritos as $item): ?>
    <li><?= $item['nombre'] ?> - $<?= $item['precio'] ?>
        <form method="post">
            <input type="hidden" name="menu_id" value="<?= $item['id'] ?>">
            <button name="remove">Quitar</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>
<a href="index.php">Volver</a>
