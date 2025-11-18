<?php
require_once "conexion.php";
$resultado = $conn->query("SELECT * FROM productos ORDER BY id_producto DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="hojas/styles_box.css">
  <title>Listado de Productos - Box</title>
</head>
<body>

  <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>
  <a href="carrito.php" class="btn-cart">🛒 Ver Carrito</a>

  <h1>Listado de Productos (Box)</h1>

  <div class="grid">
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($producto = $resultado->fetch_assoc()): ?>
        <div class="producto">
          <img src="imagenes/suplemento.png" alt="<?= htmlspecialchars($producto['tipo']) ?>">
          <h3><?= htmlspecialchars($producto['tipo']) ?></h3>
          
          <p class="descripcion"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
          
          <p>$<?= number_format($producto['precio'], 2) ?></p>
          <button onclick="agregarAlCarrito('<?= htmlspecialchars($producto['tipo']) ?>', <?= $producto['precio'] ?>)">Agregar al carrito</button>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">No hay productos disponibles en este momento.</p>
    <?php endif; ?>
  </div>

  <div id="mensaje" style="display:none;color:green;text-align:center;"></div>

  <script src="js/carrito.js"></script>
</body>
</html>