// Carga los productos desde localStorage
const productos = JSON.parse(localStorage.getItem("productosCompra")) || [];
const textarea = document.getElementById("productos");

if (productos.length > 0) {
  const listado = productos.map(p => `${p.nombre} x${p.cantidad} - $${p.precio * p.cantidad}`).join("\n");
  textarea.value = listado;
} else {
  textarea.value = "No hay productos en el carrito.";
}

// Enviar formulario
document.getElementById("formCompra").addEventListener("submit", function (e) {
  e.preventDefault();
  alert("✅ Compra finalizada correctamente. ¡Gracias por tu compra!");
  localStorage.removeItem("carrito");
  localStorage.removeItem("productosCompra");
  window.location.href = "index.html";
});
