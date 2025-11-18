<?php
session_start();
require_once "conexion.php";

// Verificar que el usuario sea administrador
if (!isset($_SESSION["usuario_rol"]) || $_SESSION["usuario_rol"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$tipo = $_POST['tipo'] ?? '';
$precio = $_POST['precio'] ?? 0;
$stock = $_POST['stock'] ?? 0;
$imagen = '';

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $nombreArchivo = time() . "_" . basename($_FILES['imagen']['name']);
    $rutaDestino = "uploads/" . $nombreArchivo;

    // Crear carpeta uploads si no existe
    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Mover imagen
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = $nombreArchivo;
    }
}
// 1. Recibir la descripción del formulario
$tipo = $_POST['tipo'];
$descripcion = $_POST['descripcion']; // <--- Nuevo Campo
$precio = $_POST['precio'];
$stock = $_POST['stock'];
// ... lógica para subir la imagen ...

// 2. Modificar el INSERT para incluir la descripción
$stmt = $conn->prepare("INSERT INTO productos (tipo, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
// Asegúrate de añadir 's' al tipo de dato y la variable $descripcion
$stmt->bind_param("ssdis", $tipo, $descripcion, $precio, $stock, $nombre_imagen);

// Verificar que los campos estén completos
if (!empty($tipo) && $precio > 0 && $stock >= 0) {
    $stmt = $conn->prepare("INSERT INTO productos (tipo, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $tipo, $descripcion, $precio, $stock, $imagen);


    if ($stmt->execute()) {
        echo "✅ Producto agregado con éxito.";
    } else {
        echo "❌ Error al agregar el producto: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "⚠️ Faltan datos o valores inválidos.";
}

$conn->close();
?>
