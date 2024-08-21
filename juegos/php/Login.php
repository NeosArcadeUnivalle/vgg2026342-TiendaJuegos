<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM usuarios WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === 'admin') {
                    header("Location: index.php");
                } else if ($row['role'] === 'cliente') {
                    header("Location: cli.php");
                }
                exit();
            } else {
                echo "Contraseña incorrecta";
            }
        } else {
            echo "Usuario no encontrado";
        }
    }

    if (isset($_POST['register'])) {
        $username = $_POST['new_username'];
        $password = $_POST['new_password'];
        $role = $_POST['role'];

        $sql = "INSERT INTO usuarios (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "Usuario registrado correctamente";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <script>
        function toggleForm(formType) {
            document.getElementById('login-form').style.display = formType === 'login' ? 'block' : 'none';
            document.getElementById('register-form').style.display = formType === 'register' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <div id="login-form">
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="login">Ingresar</button>
            </form>
            <p>¿No tienes una cuenta? <a href="#" onclick="toggleForm('register')">Regístrate aquí</a></p>
        </div>

        <div id="register-form" style="display: none;">
            <form action="login.php" method="post">
                <input type="text" name="new_username" placeholder="Usuario" required>
                <input type="password" name="new_password" placeholder="Contraseña" required>
                <select name="role" required>
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit" name="register">Registrar</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="#" onclick="toggleForm('login')">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>
</html>