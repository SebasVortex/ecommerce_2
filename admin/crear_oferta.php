<?php
// Conectar a la base de datos
include('../gestionadmin/herramientas/check.php');
include('../gestionadmin/herramientas/dbconex.php');
date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta esto a tu zona horaria
// Verificar el rol del usuario
$username = $_SESSION['username'];
$sql = "SELECT role FROM usuarios WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
} else {
    echo "Usuario no encontrado.";
    exit();
}

if ($role != 'admine') {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}

?>
<?php
// Conectar a la base de datos
include('../config/database.php');

// Comprobar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $descripcion = $_POST['descripcion'];

    // Validar y formatear las fechas
    $fechaInicio = date('Y-m-d H:i:s', strtotime($fechaInicio));
    $fechaFin = date('Y-m-d H:i:s', strtotime($fechaFin));

    // Consulta para insertar o actualizar la oferta
    $query = "INSERT INTO ofertas (fecha_inicio, fecha_fin, descripcion) 
              VALUES (:fecha_inicio, :fecha_fin, :descripcion)
              ON DUPLICATE KEY UPDATE 
              fecha_inicio = VALUES(fecha_inicio), 
              fecha_fin = VALUES(fecha_fin), 
              descripcion = VALUES(descripcion)";
    $stmt = $conn->prepare($query);

    // Ejecutar la consulta
    if ($stmt->execute([
        ':fecha_inicio' => $fechaInicio,
        ':fecha_fin' => $fechaFin,
        ':descripcion' => $descripcion
    ])) {
        header("Location: admin_ofertas.php");
        echo "<div class='alert alert-success'>Oferta guardada exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al guardar la oferta.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
</head>
<body class="bg-light">
<?php include('assets/menu.php') ; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Gestionar Oferta</h3>
                    </div>
                    <div class="card-body">
                        <form action="crear_oferta.php" method="POST">
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
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Guardar Oferta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
