<?php
// Datos de conexión
$host = "localhost";      // Servidor (en XAMPP suele ser localhost)
$usuario = "root";        // Usuario de MySQL
$contraseña = "";         // Contraseña (vacía por defecto en XAMPP)
$base_datos = "nutricion"; // 

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    error_log("Error de conexión a MySQL: " . $conn->connect_error);
    die("Error al conectar con la base de datos. Intente más tarde.");
}

// Opcional: establecer el conjunto de caracteres a UTF-8
$conn->set_charset("utf8");
?>
