<?php
require_once "conexion.php";

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Datos del nuevo admin
$nombre = "Administrador";
$email = "admin@tienda.com";
$password_plano = "admin123"; // 🔑 Contraseña que usarás para ingresar
$rol = "admin";

// Hashear la contraseña (muy importante)
$password_hash = password_hash($password_plano, PASSWORD_DEFAULT);

// Insertar el usuario
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

if ($stmt->execute()) {
    echo "✅ Usuario administrador creado correctamente.<br>";
    echo "📧 Email: $email<br>";
    echo "🔐 Contraseña: $password_plano<br>";
} else {
    echo "❌ Error al crear el administrador: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
