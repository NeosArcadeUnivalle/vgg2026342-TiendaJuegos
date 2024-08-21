<?php
include 'config.php';
session_start();

// Verificar si hay datos en la sesión para la factura
if (!isset($_SESSION['invoice_items']) || empty($_SESSION['invoice_items']) || !isset($_SESSION['invoice_total']) || !isset($_SESSION['payment'])) {
    echo "No hay datos de factura disponibles.";
    exit();
}

// Obtener los artículos de la factura y el monto pagado
$invoice_items = $_SESSION['invoice_items'];
$total = $_SESSION['invoice_total'];
$payment = $_SESSION['payment'];
$change = $payment - $total;

// Limpiar los datos de la factura de la sesión después de mostrar
unset($_SESSION['invoice_items']);
unset($_SESSION['invoice_total']);
unset($_SESSION['payment']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link rel="stylesheet" href="../css/facturas.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">NeosGameRoom</a>
            <a href="shop.php">Modulo de Compras</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container">
        <h2>Factura</h2>
        <div id="invoice-items">
            <?php foreach ($invoice_items as $item): ?>
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
        <h3>Monto Pagado: $<?php echo $payment; ?></h3>
        <h3>Cambio: $<?php echo $change >= 0 ? $change : 0; ?></h3>
    </div>
</body>
</html>
