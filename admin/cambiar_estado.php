<?php
include '../config/database.php'; // Incluye tu archivo de configuración con PDO
include '../config/checksession.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedido_id = isset($_POST['pedido_id']) ? (int)$_POST['pedido_id'] : 0;
    $nuevo_estado = isset($_POST['estado']) ? $_POST['estado'] : '';

    if ($pedido_id && in_array($nuevo_estado, ['pendiente', 'procesado', 'enviado', 'cancelado'])) {
        try {
            $sql = 'UPDATE pedidos SET status = :estado WHERE id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':estado', $nuevo_estado, PDO::PARAM_STR);
            $stmt->bindValue(':id', $pedido_id, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: admin_pedidos.php?' . http_build_query(['status' => $_GET['status'] ?? '', 'page' => $_GET['page'] ?? 1]));
            exit;
        } catch (Exception $e) {
            die('Error al actualizar el estado: ' . $e->getMessage());
        }
    } else {
        die('Datos inválidos.');
    }
}
?>
