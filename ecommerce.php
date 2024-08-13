<?php
include('producto.php'); // Incluye el archivo que recupera los datos de los productos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi eCommerce</title>
    <!-- Incluye Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header class="bg-dark text-white text-center py-3">
        <h1>eCommerce</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="#">Mi eCommerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Productos Destacados</h2>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>">
                            <img class="card-img-top" src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>">
                                    <?php echo htmlspecialchars($producto['brand_name']); ?>
                                    <span class="brand"><?php echo htmlspecialchars($producto['name']); ?></span>
                                </a>
                            </h5>
                            <p class="card-text price text-warning">Precio: $<?php echo number_format($producto['price'], 2); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2024 Mi eCommerce. Todos los derechos reservados.</p>
    </footer>

    <!-- Incluye Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
