<?php
include('config/database.php');
include('config/checksession.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    $cartContent = '<div class="cart-list">';
    $cartContent .= '<p>Tu carrito está vacío</p>';
    $cartContent .= '</div>
    <div class="cart-summary">
        <small>0 Item(s)</small>
        <h5>SUBTOTAL: $0.00</h5>
    </div>
    <div class="cart-btns">
        <a href="login.php">Inicia sesión para agregar productos</a>
    </div>';

    // Devolver el contenido del carrito vacío en formato JSON con la URL de redirección
    echo json_encode([
        'total_items' => 0,
        'cart_content' => $cartContent,
        'total' => '0.00',
        'redirect' => 'login.php' // URL de redirección
    ]);
    exit;
}

$userId = $_SESSION['user_id'];

// Verifica si se envió un ID de producto y una cantidad para agregar al carrito
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Asegúrate de que la cantidad sea un entero positivo

    if ($quantity <= 0) {
        $quantity = 1; // Asegura que la cantidad sea al menos 1
    }

    // Lógica para verificar si el producto ya está en el carrito
    $query = "SELECT quantity FROM carrito WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // Si el producto ya está en el carrito, se actualiza la cantidad
        $query = "UPDATE carrito SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id";
    } else {
        // Si el producto no está en el carrito, se inserta una nueva entrada
        $query = "INSERT INTO carrito (user_id, product_id, quantity, created_at) 
                  VALUES (:user_id, :product_id, :quantity, NOW())";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
}

// Verifica si se envió un ID de producto para eliminar del carrito
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Lógica para eliminar el producto del carrito
    $query = "DELETE FROM carrito WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $deleteId, PDO::PARAM_INT);
    $stmt->execute();
}

// Obtener los productos en el carrito del usuario
$query = "SELECT carrito.quantity, productos.id, productos.name, productos.price, productos.imagen 
          FROM carrito 
          INNER JOIN productos ON carrito.product_id = productos.id 
          WHERE carrito.user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total de artículos y el subtotal
$total_items = 0;
$total = 0.00;

foreach ($cartItems as $item) {
    $total_items += $item['quantity'];
    $total += $item['quantity'] * $item['price'];
}

// Generar el contenido del carrito
$cartContent = '<div class="cart-list">';
if (!empty($cartItems)) {
    foreach ($cartItems as $item) {
        $cartContent .= '<div class="product-widget">
                            <div class="product-img">
                                <img src="assets/images/' . htmlspecialchars($item['imagen']) . '" alt="">
                            </div>
                            <div class="product-body">
                                <h3 class="product-name">
                                    <a href="product_detalle.php?id=' . htmlspecialchars($item['id']) . '">
                                        ' . htmlspecialchars($item['name']) . '
                                    </a>
                                </h3>
                                <h4 class="product-price">
                                    <span class="qty">' . htmlspecialchars($item['quantity']) . 'x</span>
                                    $' . number_format($item['price'], 2) . '
                                </h4>
                            </div>
                            <form method="POST" action="">
                                <input type="hidden" name="delete_id" value="' . htmlspecialchars($item['id']) . '">
                                <button class="delete" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>';
    }
} else {
    $cartContent .= '<p>Tu carrito está vacío</p>';
}
$cartContent .= '</div>
<div class="cart-summary">
    <small>' . $total_items . ' Item(s)</small>
    <h5>SUBTOTAL: $' . number_format($total, 2) . '</h5>
</div>
<div class="cart-btns">';
if (isset($_SESSION['user_id'])) {
    $cartContent .= '<a href="carrito.php">Ver carrito</a>
                     <a href="checkout.php">Finalizar <i class="fa fa-arrow-circle-right"></i></a>';
} else {
    $cartContent .= '<a href="login.php">Inicia sesión para finalizar compra</a>';
}
$cartContent .= '</div>';

// Devolver los datos en formato JSON
echo json_encode([
    'total_items' => $total_items,
    'cart_content' => $cartContent,
    'total' => number_format($total, 2)
]);
?>