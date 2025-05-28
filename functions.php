<?php
require_once 'config.php';

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function getMenuItems($conn) {
    $stmt = $conn->query("SELECT * FROM menu");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function login($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function register($conn, $username, $password, $role) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed, $role]);
}

function addFavorite(PDO $pdo, int $userId, int $itemId): void {
    $stmt = $pdo->prepare("INSERT IGNORE INTO favoritos (user_id, menu_id) VALUES (?,?)");
    $stmt->execute([$userId, $itemId]);
}

function removeFavorite(PDO $pdo, int $userId, int $itemId): void {
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
}

function getFavorites(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare("SELECT menu_id FROM favoritos WHERE user_id = ?");
    $stmt->execute([$userId]);
    return array_column($stmt->fetchAll(), 'item_id');
}

function isFavorite(PDO $pdo, int $userId, int $itemId): bool {
    $stmt = $pdo->prepare("SELECT 1 FROM favoritos WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
    return (bool)$stmt->fetchColumn();
}

// — Carrito en sesión —
function addCart(int $itemId): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!in_array($itemId, $_SESSION['cart'], true)) {
        $_SESSION['cart'][] = $itemId;
    }
}

function removeCart(int $itemId): void {
    if (!empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array_values(
            array_diff($_SESSION['cart'], [$itemId])
        );
    }
}

function getCart(): array {
    return $_SESSION['cart'] ?? [];
}
