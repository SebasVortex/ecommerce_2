<?php
// Conectar a la base de datos
include('../config/database.php');

// Obtener todas las marcas para el campo de selección
$brands = [];
$stmt = $conn->prepare("SELECT id, name FROM marcas");
$stmt->execute();
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se está editando un producto existente, recuperar los datos del producto
$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Guardar o actualizar el producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $brand_id = $_POST['brand']; // Obtener la marca seleccionada
    $datasheet = $_POST['datasheet'];

    // Convertir características a formato JSON
    $characteristics = $_POST['characteristics'] ?? [];
    $characteristics_json = json_encode($characteristics);

    // Manejar la imagen
    $imagen = $_FILES['imagen']['name'];
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $destination = '../assets/images/' . basename($imagen);
        move_uploaded_file($tmpName, $destination);
    } else {
        // Si no se subió una imagen nueva, mantener la imagen existente
        $imagen = $product['imagen'] ?? '';
    }

    // Insertar o actualizar el producto en la base de datos
    if ($id) {
        // Actualizar producto existente
        $stmt = $conn->prepare("UPDATE productos SET name = :name, description = :description, characteristics = :characteristics, price = :price, stock = :stock, imagen = :imagen, datasheet = :datasheet, brand_id = :brand_id WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Insertar nuevo producto
        $stmt = $conn->prepare("INSERT INTO productos (name, description, characteristics, price, stock, imagen, datasheet, brand_id) VALUES (:name, :description, :characteristics, :price, :stock, :imagen, :datasheet, :brand_id)");
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':characteristics', $characteristics_json);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':imagen', $imagen);
    $stmt->bindParam(':datasheet', $datasheet);
    $stmt->bindParam(':brand_id', $brand_id); // Agregar marca
    $stmt->execute();

    echo "Producto guardado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Producto</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Administrar Producto</h1>
        <form action="admin_producto.php" method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
            <input type="hidden" name="id" value="<?php echo $product['id'] ?? ''; ?>">

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" name="description" id="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" class="form-control" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" name="stock" id="stock" value="<?php echo htmlspecialchars($product['stock'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="brand">Marca:</label>
                <select class="form-control" name="brand" id="brand" required>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['id']; ?>" <?php echo (isset($product['brand_id']) && $product['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" name="imagen" id="imagen">
                <?php if (isset($product['imagen']) && $product['imagen']): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen del Producto" class="img-fluid mt-2" style="max-width: 200px;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="datasheet">Datasheet:</label>
                <input type="text" class="form-control" name="datasheet" id="datasheet" value="<?php echo htmlspecialchars($product['datasheet'] ?? ''); ?>">
            </div>

            <label>Características:</label>
            <table class="table table-bordered" id="characteristics-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($product['characteristics'])): ?>
                        <?php $characteristics = json_decode($product['characteristics'], true); ?>
                        <?php foreach ($characteristics as $index => $characteristic): ?>
                            <tr>
                                <td><input type="text" class="form-control" name="characteristics[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($characteristic['name']); ?>"></td>
                                <td><input type="text" class="form-control" name="characteristics[<?php echo $index; ?>][value]" value="<?php echo htmlspecialchars($characteristic['value']); ?>"></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-info btn-sm" onclick="addRow()">Agregar Fila</button><br><br>

            <button type="submit" class="btn btn-success">Guardar Producto</button>
        </form>
    </div>

    <!-- Incluir Bootstrap JS y dependencias de Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function addRow() {
            var table = document.getElementById('characteristics-table').getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var newRow = table.insertRow();
            newRow.innerHTML = `
                <td><input type="text" class="form-control" name="characteristics[${rowCount}][name]" placeholder="Nombre"></td>
                <td><input type="text" class="form-control" name="characteristics[${rowCount}][value]" placeholder="Valor"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button></td>
            `;
        }

        function removeRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</body>
</html>
