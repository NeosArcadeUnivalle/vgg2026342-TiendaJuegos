<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Juegos</title>
    <link rel="stylesheet" href="../css/cli.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php">Catálogo</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container">
        <h2>Catálogo de Juegos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtener todos los juegos
                $sql = "SELECT * FROM juegos";
                $result = $conn->query($sql);

                if (!$result) {
                    echo "Error en la consulta: " . $conn->error;
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>$" . htmlspecialchars($row['price']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay juegos disponibles</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
