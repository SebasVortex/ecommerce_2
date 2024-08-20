<?php
include 'database.php'; // Incluye tu archivo de configuración con PDO
include 'checksession.php'; // Incluye el archivo de verificación de sesión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die('Usuario no autenticado.');
    }

    $userId = $_SESSION['user_id'];
    $pedidoId = $_POST['pedido_id'];

    try {
        // Verificar si el pedido pertenece al usuario
        $stmt = $conn->prepare('SELECT status FROM pedidos WHERE id = :pedido_id AND user_id = :user_id');
        $stmt->execute(['pedido_id' => $pedidoId, 'user_id' => $userId]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            die('Pedido no encontrado o no pertenece al usuario.');
        }

        if ($pedido['status'] != 'pendiente') {
            die('El pedido no puede ser cancelado.');
        }

        // Iniciar una transacción
        $conn->beginTransaction();

        // Actualizar el estado del pedido a 'cancelado'
        $stmt = $conn->prepare('UPDATE pedidos SET status = :status WHERE id = :pedido_id');
        $stmt->execute(['status' => 'Cancelado', 'pedido_id' => $pedidoId]);

        // Insertar en el historial de pedidos
        $stmt = $conn->prepare('INSERT INTO pedidos_historial (order_id, status) VALUES (:order_id, :status)');
        $stmt->execute(['order_id' => $pedidoId, 'status' => 'cancelled']);

        // Eliminar los elementos del carrito del usuario
        $stmt = $conn->prepare('DELETE FROM carrito WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);

        // Confirmar la transacción
        $conn->commit();

        echo '<div class="alert alert-success" role="alert">Pedido cancelado con éxito.</div>';
        header('Location: ../pedidos.php'); // Redirige de vuelta a la página de pedidos

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conn->rollBack();
        echo '<div class="alert alert-danger" role="alert">Error al cancelar el pedido: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">Solicitud inválida.</div>';
}
?>
