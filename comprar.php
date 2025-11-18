<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $direccion = htmlspecialchars($_POST["direccion"]);
    $telefono = htmlspecialchars($_POST["telefono"]);
    $email = htmlspecialchars($_POST["email"]);
    $pago = htmlspecialchars($_POST["pago"]);
    $productos_json = $_POST["productos"];

    // Decodificar los productos (espera formato JSON)
    $productos = json_decode($productos_json, true);
    if (!is_array($productos)) {
        die("<script>alert('Error: formato de productos inválido.'); window.history.back();</script>");
    }

    // Convertir a texto legible para guardar en base de datos
    $detalle_productos = "";
    foreach ($productos as $p) {
        $detalle_productos .= "{$p['nombre']} x{$p['cantidad']} (${$p['precio']})\n";
    }

    // Insertar la compra en la base de datos
    $estado = "Comprado";
    $stmt = $conn->prepare("INSERT INTO compras (nombre, direccion, telefono, email, pago, productos, estado, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $nombre, $direccion, $telefono, $email, $pago, $detalle_productos, $estado);

    if ($stmt->execute()) {

        // 🔄 Actualizar el stock de cada producto
        foreach ($productos as $p) {
            $id = intval($p['id']);
            $cantidad = intval($p['cantidad']);
            $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $id");
        }

        // ✉️ Enviar correo
        $asunto = "✅ Confirmación de compra - Tu pedido está en proceso";
        $mensaje = "Hola $nombre,\n\nTu compra ha sido registrada con éxito.\n\nDetalles:\n$detalle_productos\nMétodo de pago: $pago\n\nGracias por elegirnos.\nTu pedido está siendo preparado.";
        $cabeceras = "From: noreply@tutienda.com\r\nReply-To: noreply@tutienda.com";

        @mail($email, $asunto, $mensaje, $cabeceras);

        echo "<script>
            alert('✅ Compra registrada correctamente. Se envió un correo de confirmación.');
            localStorage.removeItem('productosCompra');
            localStorage.removeItem('carrito');
            window.location.href = 'index.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('❌ Error al registrar la compra.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="hojas/styles_compras.css">
  <title>Formulario de Compra</title>
</head>
<body>
  <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>
  <h1 style="text-align: center;">Formulario de Compra</h1>
  
  <form method="POST" id="formCompra">
    <label>Nombre del cliente</label>
    <input type="text" name="nombre" required>

    <label>Dirección</label>
    <input type="text" name="direccion" required>

    <label>Teléfono</label>
    <input type="tel" name="telefono" required>

    <label>E-mail</label>
    <input type="email" name="email" required>

    <label>Medio de pago</label>
    <select name="pago" required>
      <option value="">Seleccione...</option>
      <option>Tarjeta de crédito</option>
      <option>Tarjeta de débito</option>
      <option>Transferencia bancaria</option>
    </select>

    <label>Productos seleccionados</label>
    <textarea id="productos" name="productos" readonly></textarea>

    <button type="submit">Finalizar compra</button>
  </form>

  <script>
  // Rellenar el campo productos con los datos del carrito
  document.addEventListener("DOMContentLoaded", () => {
      const productos = JSON.parse(localStorage.getItem("productosCompra") || "[]");
      document.getElementById("productos").value = JSON.stringify(productos, null, 2);
  });
  </script>
</body>
</html>
