<?php
session_start();
require_once "conexion.php";

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    error_log("Error de conexión: " . $conn->connect_error);
    die("Error al conectar con la base de datos.");
}

$error = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Por favor, completá todos los campos.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            // Encriptar contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $hash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Error al registrar el usuario.";
                error_log("Error SQL: " . $stmt->error);
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="hojas/usuarios.css">
</head>
<body>
    <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>

    <div class="form-container">
        <h2>Crear cuenta</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="usuario@correo.com" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">

            <button type="submit" class="btn-submit">Registrarme</button>
        </form>

        <p>¿Ya tenés una cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
