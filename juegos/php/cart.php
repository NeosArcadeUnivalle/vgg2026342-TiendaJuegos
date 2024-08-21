<?php
include 'config.php';
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "El carrito está vacío.";
    exit();
}

// Obtener los juegos en el carrito
$cart_items = [];
$total = 0;
foreach ($_SESSION['cart'] as $game_id => $quantity) {
    $sql = "SELECT * FROM juegos WHERE id = $game_id";
    $result = $conn->query($sql);
    if ($result) {
        $game = $result->fetch_assoc();
        $game['quantity'] = $quantity;
        $cart_items[] = $game;
        $total += $game['price'] * $quantity;
    }
}

// Confirmar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_purchase'])) {
    $payment = $_POST['payment'];

    if ($payment < $total) {
        echo "El monto ingresado es menor que el total. Por favor, ingrese una cantidad suficiente.";
    } else {
        // Guardar los datos del carrito en la sesión para la factura
        $_SESSION['invoice_items'] = $cart_items;
        $_SESSION['invoice_total'] = $total;
        $_SESSION['payment'] = $payment;

        // Vaciar el carrito
        unset($_SESSION['cart']);

        // Redirigir a la página de facturación
        header("Location: facturas.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Inicio</a>
            <a href="shop.php">Modulo de Compras</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container">
        <h2>Carrito de Compras</h2>
        <div id="cart-items">
            <?php foreach ($cart_items as $item): ?>
                <div class="game-item">
                    <h4><?php echo $item['name']; ?></h4>
                    <p><?php echo $item['description']; ?></p>
                    <p>Precio: $<?php echo $item['price']; ?></p>
                    <p>Cantidad: <?php echo $item['quantity']; ?></p>
                    <p>Subtotal: $<?php echo $item['price'] * $item['quantity']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <h3>Total: $<?php echo $total; ?></h3>
        <form action="cart.php" method="post">
            <label for="payment">Monto para pagar: $</label>
            <input type="number" id="payment" name="payment" step="0.01" required>
            <button type="submit" name="confirm_purchase">Comprar</button>
        </form>
    </div>
</body>
</html>
