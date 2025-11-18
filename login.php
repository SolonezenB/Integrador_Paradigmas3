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

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "Por favor, completá todos los campos.";
    } else {
        // Evitar inyecciones SQL
        $stmt = $conn->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verificar contraseña
            if (password_verify($password, $usuario["password"])) {
                // Guardar datos en sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nombre"] = $usuario["nombre"];
                $_SESSION["usuario_email"] = $usuario["email"];
                $_SESSION["usuario_rol"] = $usuario["rol"];

                // Redirigir según rol
                if ($usuario["rol"] === "admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "No se encontró una cuenta con ese correo.";
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
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="hojas/login.css">
</head>
<body>
    <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>

    <div class="form-container">
        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="usuario@correo.com" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

            <button type="submit">Ingresar</button>
        </form>

        <p>¿No tenés una cuenta? <a href="registro.php">Registrate aquí</a></p>
    </div>
</body>
</html>
