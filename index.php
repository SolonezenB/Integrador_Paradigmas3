<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="hojas/styles.css">
    <title>Artículos Nutricionales</title>
</head>
<body>

    <header>
        <nav>
            <ul> 
                <?php 
                // Verificar si el usuario ha iniciado sesión
                if (isset($_SESSION["usuario_id"])): ?>
                    
                    <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
                        <li><a href="admin.php"><strong>Panel Admin</strong></a></li>
                    <?php endif; ?>

                    <li><a href="logout.php"><strong>Cerrar Sesión</strong></a></li>
                    
                <?php else: ?>
                    
                    <li><a href="login.php"><strong>Iniciar Sesión</strong></a></li>
                    <li><a href="registro.php"><strong>Registrar Usuario</strong></a></li>
                    
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>

        <h1>Nutritional Supplements Store</h1>

        <div class="avatar">
            <img class="logo-circular" src="imagenes/logo.png" alt="Logo de la Tienda">
        </div>

        <p><strong>Suplementos Nutricionales y Deportivos</strong></p>

        <div>
            <a href="comprar.php" class="button">Comprar</a>
            <a href="listado_box.php" class="button">Ver Productos</a>
            <a href="listado_tabla.php" class="button">Ver Productos en Tabla</a>
        </div>

    </main>

    <footer>
  <p>&copy; 2025 Nutritional Supplements Store | Todos los derechos reservados</p>
  <p>Contacto: 
    <a href="mailto:solonezenb@gmail.com">solonezenb@gmail.com</a>
  </p>
</footer>

    <script src="js/index.js"></script>
</body>
</html>
