<?php
require_once "conexion.php";
$id = $_POST['id_producto'];

$stmt = $conn->prepare("DELETE FROM productos WHERE id_producto=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();
?>
