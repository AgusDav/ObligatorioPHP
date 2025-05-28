<?php
require_once '../functions.php';
redirectIfNotAdmin();
$id = intval($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM menu WHERE id = ?')->execute([$id]);
header('Location: menu_list.php');
exit;
