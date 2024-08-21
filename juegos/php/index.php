<?php
include 'config.php';
session_start();

// Verifica si el usuario está logueado y es un administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Agregar un nuevo juego
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_game'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO juegos (name, description, price) VALUES ('$name', '$description', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo juego agregado correctamente.";
    } else {
        echo "Error al agregar el juego: " . $conn->error;
    }
}

// Editar un juego
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_game'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE juegos SET name = '$name', description = '$description', price = '$price' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Juego actualizado correctamente.";
    } else {
        echo "Error al actualizar el juego: " . $conn->error;
    }
}

// Eliminar un juego
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $sql = "DELETE FROM juegos WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Juego eliminado correctamente.";
    } else {
        echo "Error al eliminar el juego: " . $conn->error;
    }
}

// Obtener todos los juegos
$games = $conn->query("SELECT * FROM juegos");

// Manejo de errores en la consulta de juegos
if (!$games) {
    die("Error en la consulta de juegos: " . $conn->error);
}

// Obtener el juego para editar
$edit_game = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sql = "SELECT * FROM juegos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        $edit_game = $result->fetch_assoc();
    } else {
        echo "Error al obtener el juego: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeosGameRoom</title>
    <link rel="stylesheet" href="../css/index.css">
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

        <!-- Formulario para agregar un nuevo juego -->
        <h3>Agregar Nuevo Juego</h3>
        <form action="index.php" method="post">
            <input type="text" name="name" placeholder="Nombre del juego" required>
            <input type="text" name="description" placeholder="Descripción" required>
            <input type="number" name="price" placeholder="Precio" step="0.01" required>
            <button type="submit" name="add_game">Agregar Juego</button>
        </form>

        <!-- Tabla de juegos existentes -->
        <h3>Juegos Disponibles</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($game = $games->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $game['id']; ?></td>
                        <td><?php echo $game['name']; ?></td>
                        <td><?php echo $game['description']; ?></td>
                        <td>$<?php echo $game['price']; ?></td>
                        <td>
                            <form action="index.php?edit_id=<?php echo $game['id']; ?>" method="post" style="display:inline;">
                                <button type="submit" class="btn-edit">Editar</button>
                            </form>
                            <form action="index.php?delete_id=<?php echo $game['id']; ?>" method="get" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este juego?');">
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Formulario para editar un juego -->
        <?php if ($edit_game): ?>
            <h3>Editar Juego</h3>
            <form action="index.php" method="post">
                <input type="hidden" name="id" value="<?php echo $edit_game['id']; ?>">
                <input type="text" name="name" value="<?php echo $edit_game['name']; ?>" required>
                <input type="text" name="description" value="<?php echo $edit_game['description']; ?>" required>
                <input type="number" name="price" value="<?php echo $edit_game['price']; ?>" step="0.01" required>
                <button type="submit" name="edit_game">Guardar Cambios</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
