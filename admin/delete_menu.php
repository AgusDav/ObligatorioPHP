<?php
require_once '../functions.php';
redirectIfNotAdmin();

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        // Iniciar transacción para asegurar consistencia
        $pdo->beginTransaction();
        
        // 1. Eliminar de favoritos
        $stmt = $pdo->prepare('DELETE FROM favoritos WHERE menu_id = ?');
        $stmt->execute([$id]);
        
        // 2. Eliminar de carritos de sesión (esto requiere limpiar las sesiones activas)
        // Como los carritos están en sesión, eliminamos de la base si tuvieras tabla de carritos
        // Por ahora, los carritos en sesión se limpiarán automáticamente cuando se refresque
        
        // 3. Eliminar de la tabla menu
        $stmt = $pdo->prepare('DELETE FROM menu WHERE id = ?');
        $stmt->execute([$id]);
        
        // Confirmar transacción
        $pdo->commit();
        
        // Mensaje de éxito (opcional)
        $_SESSION['message'] = 'Item eliminado correctamente';
        
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $pdo->rollBack();
        
        // Mensaje de error (opcional)
        $_SESSION['error'] = 'Error al eliminar el item: ' . $e->getMessage();
    }
}

header('Location: menu_list.php');
exit;
