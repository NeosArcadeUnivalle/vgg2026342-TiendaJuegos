<?php
include 'config.php';
session_start();

// Inicializar el carrito en la sesión si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Agregar un juego al carrito
if (isset($_GET['add_to_cart'])) {
    $game_id = $_GET['add_to_cart'];

    // Verificar si el juego ya está en el carrito
    if (!isset($_SESSION['cart'][$game_id])) {
        $_SESSION['cart'][$game_id] = 1;
    } else {
        $_SESSION['cart'][$game_id]++;
    }
    header("Location: shop.php");
    exit();
}

// Obtener todos los juegos
$games = $conn->query("SELECT * FROM juegos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Juegos</title>
    <link rel="stylesheet" href="../css/shop.css">
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
    <h2>Comprar Juegos</h2>
    <a href="cart.php" class="view-cart-button">Ver Carrito</a>
    <div id="game-list">
        <?php while($game = $games->fetch_assoc()): ?>
            <div class="game-item">
                <h4><?php echo $game['name']; ?></h4>
                <p><?php echo $game['description']; ?></p>
                <p>Precio: $<?php echo $game['price']; ?></p>
                <a href="?add_to_cart=<?php echo $game['id']; ?>">Agregar al Carrito</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
