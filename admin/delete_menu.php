<?php
require_once '../functions.php';
redirectIfNotAdmin();
$id = intval($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM menu_items WHERE id = ?')->execute([$id]);
header('Location: menu_list.php');
exit;
