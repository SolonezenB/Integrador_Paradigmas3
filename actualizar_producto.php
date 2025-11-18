<?php
require_once "conexion.php"; // Asegúrate de incluir tu archivo de conexión

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir todos los datos
    $id_producto = $_POST['id_producto'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $descripcion = $_POST['descripcion']; // <-- Campo de descripción

    // Preparar la consulta de actualización, incluyendo la descripción
    $stmt = $conn->prepare("UPDATE productos SET precio = ?, stock = ?, descripcion = ? WHERE id_producto = ?");
    
    // Bindear los parámetros: d=double/float, i=integer, s=string.
    // Usamos 'disi' (Precio, Stock, Descripcion, ID)
    $stmt->bind_param("disi", $precio, $stock, $descripcion, $id_producto);

    if ($stmt->execute()) {
        // No devolver nada si todo va bien, solo un código 200 (implícito)
    } else {
        http_response_code(500); // Enviar código de error
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Método no permitido
}
?>