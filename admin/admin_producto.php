<?php
// Conectar a la base de datos
include('../config/database.php');

// Obtener todas las marcas para el campo de selección
$brands = [];
$stmt = $conn->prepare("SELECT id, name FROM marcas");
$stmt->execute();
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las categorías para el campo de selección
$categories = [];
$stmt = $conn->prepare("SELECT id, name FROM categorias");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se está editando un producto existente, recuperar los datos del producto
$product = null;
$imagen = ''; // Inicializar la variable de imagen
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Asignar la imagen existente
    if ($product) {
        $imagen = $product['imagen'];
    }
}

// Guardar o actualizar el producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $brand_id = $_POST['brand']; // Obtener la marca seleccionada
    $category_id = $_POST['category']; // Obtener la categoría seleccionada
    $datasheet = $_POST['datasheet'];

    // Convertir características a formato JSON
    $characteristics = $_POST['characteristics'] ?? [];
    $characteristics_json = json_encode($characteristics);

    // Manejar la imagen subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen']['name'];
        $tmpName = $_FILES['imagen']['tmp_name'];
        $destination = '../assets/images/' . basename($imagen);

        if (move_uploaded_file($tmpName, $destination)) {
            echo "Imagen subida correctamente.";
        } else {
            echo "Error al mover la imagen al destino.";
        }
    } elseif ($product && !$imagen) {
        // Mantener la imagen existente si no se sube una nueva
        $imagen = $product['imagen'];
    }

    // Construir la consulta SQL según si se subió una imagen nueva o no
    if ($id) {
        if ($imagen) {
            // Actualizar producto existente incluyendo la imagen
            $stmt = $conn->prepare("UPDATE productos SET name = :name, description = :description, characteristics = :characteristics, price = :price, stock = :stock, imagen = :imagen, datasheet = :datasheet, brand_id = :brand_id, category_id = :category_id WHERE id = :id");
        } else {
            // Actualizar producto existente sin modificar la imagen
            $stmt = $conn->prepare("UPDATE productos SET name = :name, description = :description, characteristics = :characteristics, price = :price, stock = :stock, datasheet = :datasheet, brand_id = :brand_id, category_id = :category_id WHERE id = :id");
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Insertar nuevo producto incluyendo la imagen
        $stmt = $conn->prepare("INSERT INTO productos (name, description, characteristics, price, stock, imagen, datasheet, brand_id, category_id) VALUES (:name, :description, :characteristics, :price, :stock, :imagen, :datasheet, :brand_id, :category_id)");
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':characteristics', $characteristics_json);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    if ($imagen) {
        $stmt->bindParam(':imagen', $imagen); // Solo enlaza la imagen si está presente
    }
    $stmt->bindParam(':datasheet', $datasheet);
    $stmt->bindParam(':brand_id', $brand_id); // Agregar marca
    $stmt->bindParam(':category_id', $category_id); // Agregar categoría

    if ($stmt->execute()) {
        echo "Producto guardado correctamente.";
    } else {
        echo "Error al guardar el producto.";
    }

    header('Location: tabla_productos.php');
    exit;
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
                <label for="category">Categoría:</label>
                <select class="form-control" name="category" id="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo (isset($product['category_id']) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
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

    <!-- Incluir Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function addRow() {
            var table = document.getElementById('characteristics-table').getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            row.innerHTML = `
                <td><input type="text" class="form-control" name="characteristics[${rowCount}][name]"></td>
                <td><input type="text" class="form-control" name="characteristics[${rowCount}][value]"></td>
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
