<?php
require_once "conexion.php";
$resultado = $conn->query("SELECT * FROM productos ORDER BY id_producto DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="hojas/styles_tabla.css">
    <title>Listado de Productos - Tabla</title>
</head>
<body>
    <button class="btn-back" onclick="window.history.back()">⬅ Volver</button>
    <h1>Listado de Productos (Tabla)</h1>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($producto = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['tipo']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td><a href="producto.php?id=<?= $producto['id_producto'] ?>">Ver más</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align:center;">No hay productos disponibles en este momento.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
