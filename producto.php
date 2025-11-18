<?php
require_once "conexion.php";

$conn = new mysqli($host, $usuario, $contraseña, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Producto no especificado.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Producto no encontrado.");
}

$productos = $resultado->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="hojas/styles_producto.css">
    <title><?= htmlspecialchars($productos['tipo']) ?></title>
</head>
<body>
    <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>
    <div class="producto">
    <img src="uploads/<?= htmlspecialchars($productos['imagen'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($productos['tipo']) ?>">
        <h3><?= htmlspecialchars($productos['tipo']) ?></h3>
        <p><?= htmlspecialchars($productos['stock']) ?></p>
        <p class="precio">$<?= htmlspecialchars($productos['precio']) ?></p>
        <a href="comprar.php?id=<?= $productos['id_producto'] ?>">Comprar ahora</a>
    </div>
</body>
</html>

