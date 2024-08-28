<?php
// Conectar a la base de datos
include('../config/database.php');

// Consultar todas las ofertas
$query = "SELECT * FROM ofertas ORDER BY index_id DESC";
$stmt = $conn->query($query);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado una solicitud para cambiar la oferta en el índice
if (isset($_POST['set_index_offer_id'])) {
    $newIndexOfferId = intval($_POST['set_index_offer_id']);
    
    // Cambiar el índice de la oferta actual
    $query = "UPDATE ofertas SET index_id = 0 WHERE index_id = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Establecer la nueva oferta como índice
    $query = "UPDATE ofertas SET index_id = 1 WHERE id = :new_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['new_id' => $newIndexOfferId]);

    echo "<div class='alert alert-success'>Oferta del índice actualizada exitosamente.</div>";
    header('Location: admin_ofertas.php');
}

// Verificar si se ha enviado una solicitud para eliminar una oferta
if (isset($_POST['delete_offer_id'])) {
    $deleteOfferId = intval($_POST['delete_offer_id']);
    
    // Eliminar la oferta
    $query = "DELETE FROM ofertas WHERE id = :offer_id";
    $stmt = $conn->prepare($query);
    if ($stmt->execute(['offer_id' => $deleteOfferId])) {
        echo "<div class='alert alert-success'>Oferta eliminada exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la oferta.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ofertas - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include('assets/menu.php'); ?>
    <div class="container mt-5">
    <h1 class="text-left mb-4">Gestionar ofertas</h1>
        <div class="text-right mb-3">
            <button onclick="location.href='crear_oferta.php'" class="btn btn-success">Agregar Nueva Oferta</button>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ofertas as $oferta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($oferta['index_id']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['fecha_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['fecha_fin']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['descripcion']); ?></td>
                            <td>
                                <?php if ($oferta['index_id'] != 1): ?>
                                    <!-- Formulario para establecer la oferta en el índice -->
                                    <form action="admin_ofertas.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="set_index_offer_id" value="<?php echo htmlspecialchars($oferta['id']); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Establecer como Índice</button>
                                    </form>
                                    <!-- Formulario para eliminar la oferta -->
                                    <form action="admin_ofertas.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_offer_id" value="<?php echo htmlspecialchars($oferta['id']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta oferta?')">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
