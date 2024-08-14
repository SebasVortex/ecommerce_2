<?php
// Conectar a la base de datos
include('config/database.php');

// Recuperar los datos del producto junto con la marca
$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("
        SELECT p.*, m.name AS brand_name
        FROM productos p
        LEFT JOIN marcas m ON p.brand_id = m.id
        WHERE p.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Decodificar características de JSON
$characteristics = [];
if (!empty($product['characteristics'])) {
    $characteristics = json_decode($product['characteristics'], true);
}

// Obtener productos adicionales
$relatedProducts = [];
if ($product) {
    $stmt = $conn->prepare("
        SELECT p.*, m.name AS brand_name
        FROM productos p
        LEFT JOIN marcas m ON p.brand_id = m.id
        WHERE p.id != :id
        LIMIT 5
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include 'assets/includes/head.php';?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <style>
        .product-detail img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .swiper-container {
            width: 100%;
            padding: 20px 0;
            overflow: hidden;
            position: relative;
        }
        .swiper-slide {
            text-align: center;
            background: #fff;
            position: relative;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .swiper-slide img {
            max-width: 100%;
            border-radius: 5px;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #000;
            background-color: rgba(0, 0, 0, 0.5);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-button-next {
            right: 0;
        }
        .swiper-button-prev {
            left: 0;
        }
        .swiper-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-pagination {
            bottom: 0px;
            position: relative;
        }
        .btn-buy {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-buy:hover {
            background-color: #0056b3;
        }
        @media (max-width: 767px) {
            .swiper-button-next, .swiper-button-prev {
                width: 36px;
                height: 36px;
            }
        }
    </style>
</head>
	<body>
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- /HEADER -->

    <div class="container mt-4">
        <div class="row">
            <!-- Detalle del producto -->
            <div class="col-md-6">
                <img src="assets/images/<?php echo htmlspecialchars($product['imagen']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['brand_name']); ?> - <?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p><strong>Precio:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                <p><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                <p><strong>Datasheet:</strong> <?php echo htmlspecialchars($product['datasheet']); ?></p>
                <h2>Características:</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($characteristics as $characteristic): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($characteristic['name']); ?></td>
                                <td><?php echo htmlspecialchars($characteristic['value']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <!-- Botón de compra -->
            <a href="carrito.php?id=<?php echo $product['id']; ?>" class="btn-buy">Comprar</a>
            </div>
        </div>

        <!-- Productos relacionados -->
        <div class="mt-5">
            <h2>Productos Relacionados</h2>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="swiper-slide">
                            <a href="producto_detalle.php?id=<?php echo $relatedProduct['id']; ?>">
                                <img src="assets/images/<?php echo htmlspecialchars($relatedProduct['imagen']); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                                <p class="mt-2"><?php echo htmlspecialchars($relatedProduct['brand_name']); ?> - <?php echo htmlspecialchars($relatedProduct['name']); ?></p>
                                <p class="text-warning">Precio: $<?php echo number_format($relatedProduct['price'], 2); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add Navigation -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <!-- Add Pagination -->

            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                576: {
                    slidesPerView: 1,
                },
            }
        });
    </script>
</body>
</html>
