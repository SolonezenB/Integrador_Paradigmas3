<?php
session_start();

// Verificar que haya sesión y que sea admin
if (!isset($_SESSION["usuario_rol"]) || $_SESSION["usuario_rol"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "conexion.php";
$resultado = $conn->query("SELECT * FROM productos ORDER BY id_producto DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="hojas/styles_admin.css">
</head>
<body>
<header>
    <nav class="nav-bar">
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="registro.php">Registrar Usuario</a></li>
            <li><a href="logout.php" class="btn-logout">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <h1>Administración de Productos</h1>
</header>


<main class="panel">
    <h2>Lista de Productos</h2>
    <button class="btn-agregar" onclick="mostrarFormulario()">➕ Agregar Producto</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Tipo</th>
                <th>Descripción</th> <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id_producto'] ?></td>
                <td>
                    <?php if (!empty($fila['imagen'])): ?>
                        <img src="uploads/<?= htmlspecialchars($fila['imagen']) ?>" alt="Producto" width="70">
                    <?php else: ?>
                        <span>Sin imagen</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($fila['tipo']) ?></td>
                
                <td>
                    <textarea id="descripcion_<?= $fila['id_producto'] ?>" rows="3" style="width: 250px;"><?= htmlspecialchars($fila['descripcion']) ?></textarea>
                </td>
                
                <td><input type="number" value="<?= $fila['precio'] ?>" id="precio_<?= $fila['id_producto'] ?>"></td>
                <td><input type="number" value="<?= $fila['stock'] ?>" id="stock_<?= $fila['id_producto'] ?>"></td>
                <td>
                    <button onclick="actualizarProducto(<?= $fila['id_producto'] ?>)">💾 Guardar</button>
                    <button onclick="eliminarProducto(<?= $fila['id_producto'] ?>)">🗑 Eliminar</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</main>

<div id="modalAgregar" class="modal" style="display:none;">
    <div class="modal-contenido">
        <h3>Agregar Nuevo Producto</h3>
        <form id="formAgregarProducto" enctype="multipart/form-data">
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="">Seleccione...</option>
                <option value="Proteína">Proteína</option>
                <option value="Vitaminas">Vitaminas</option>
                <option value="Aminoácidos">Aminoácidos</option>
                <option value="Suplemento Energético">Suplemento Energético</option>
            </select>
            
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="4" required></textarea>

            <label for="precio">Precio ($):</label>
            <input type="number" name="precio" id="precio" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>

            <label for="imagen">Imagen del Producto:</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>

            <button type="submit">Agregar</button>
            <button type="button" onclick="cerrarFormulario()">Cancelar</button>
        </form>
    </div>
</div>

<script>
function eliminarProducto(id) {
    if (confirm("¿Deseas eliminar este producto?")) {
        fetch("eliminar_producto.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "id_producto=" + id
        }).then(() => location.reload());
    }
}

// CAMBIO CLAVE: Actualizar función para incluir la descripción
function actualizarProducto(id) {
    const precio = document.getElementById("precio_" + id).value;
    const stock = document.getElementById("stock_" + id).value;
    const descripcion = document.getElementById("descripcion_" + id).value; // Obtener la descripción
    
    fetch("actualizar_producto.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        // Incluir la descripción, usando encodeURIComponent para manejar el texto largo
        body: "id_producto=" + id + 
              "&precio=" + precio + 
              "&stock=" + stock + 
              "&descripcion=" + encodeURIComponent(descripcion) 
    }).then(() => location.reload());
}

function mostrarFormulario() {
    document.getElementById("modalAgregar").style.display = "block";
}

function cerrarFormulario() {
    document.getElementById("modalAgregar").style.display = "none";
}

document.getElementById("formAgregarProducto").onsubmit = e => {
    e.preventDefault();
    const datos = new FormData(e.target);
    fetch("agregar_producto.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.text())
    .then(respuesta => {
        alert(respuesta);
        cerrarFormulario();
        location.reload();
    })
    .catch(error => console.error(error));
};
</script>

</body>
</html>