<?php
// Conectar a la base de datos
include('../config/database.php');

// Comprobar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $descripcion = $_POST['descripcion'];

    // Validar y formatear las fechas (opcional)
    $fechaInicio = date('Y-m-d H:i:s', strtotime($fechaInicio));
    $fechaFin = date('Y-m-d H:i:s', strtotime($fechaFin));

    // Consulta para insertar o actualizar la oferta
    $query = "INSERT INTO ofertas (fecha_inicio, fecha_fin, descripcion) VALUES (:fecha_inicio, :fecha_fin, :descripcion)
              ON DUPLICATE KEY UPDATE fecha_inicio = VALUES(fecha_inicio), fecha_fin = VALUES(fecha_fin), descripcion = VALUES(descripcion)";
    $stmt = $conn->prepare($query);

    // Ejecutar la consulta
    if ($stmt->execute([
        ':fecha_inicio' => $fechaInicio,
        ':fecha_fin' => $fechaFin,
        ':descripcion' => $descripcion
    ])) {
        echo "Oferta guardada exitosamente.";
    } else {
        echo "Error al guardar la oferta.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas - admin</title>
</head>
<body>
<form action="ofertas.php" method="POST">
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="datetime-local" id="fecha_fin" name="fecha_fin" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Oferta</button>
</form>

</body>
</html>