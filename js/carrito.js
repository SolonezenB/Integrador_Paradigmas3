let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// Mostrar el carrito en la página
function mostrarCarrito() {
  const contenedor = document.getElementById("carrito-contenido");
  if (!contenedor) return;

  if (carrito.length === 0) {
    contenedor.innerHTML = "<p>No hay productos en el carrito.</p>";
    return;
  }

  contenedor.innerHTML = carrito
    .map(
      (p, i) => `
      <div class="item">
        <strong>${p.nombre}</strong> - $${p.precio}
        <button onclick="eliminarDelCarrito(${i})">❌</button>
      </div>`
    )
    .join("");
}

// Agregar producto al carrito
function agregarAlCarrito(nombre, precio) {
  carrito.push({ nombre, precio });
  localStorage.setItem("carrito", JSON.stringify(carrito));
  alert("Producto agregado al carrito 🛒");
}

// Eliminar producto individual
function eliminarDelCarrito(index) {
  carrito.splice(index, 1);
  localStorage.setItem("carrito", JSON.stringify(carrito));
  mostrarCarrito();
}

// Vaciar todo el carrito
const btnVaciar = document.getElementById("btn-vaciar");
if (btnVaciar) {
  btnVaciar.addEventListener("click", () => {
    if (confirm("¿Seguro que deseas vaciar el carrito?")) {
      carrito = [];
      localStorage.removeItem("carrito");
      mostrarCarrito();
    }
  });
}

// Ir al formulario de compra
const btnComprar = document.getElementById("btn-comprar");
if (btnComprar) {
  btnComprar.addEventListener("click", () => {
    if (carrito.length === 0) {
      alert("Tu carrito está vacío 🛒");
      return;
    }

    // Guardar los productos antes de redirigir
    localStorage.setItem("productosCompra", JSON.stringify(carrito));
    window.location.href = "comprar.php";
  });
}

// Mostrar productos seleccionados en el formulario
const textareaProductos = document.getElementById("productos");
if (textareaProductos) {
  const productosCompra = JSON.parse(localStorage.getItem("productosCompra")) || [];
  if (productosCompra.length > 0) {
    textareaProductos.value = productosCompra
      .map(p => `${p.nombre} - $${p.precio}`)
      .join("\n");
  }
}

// Mostrar carrito si estamos en carrito.html
mostrarCarrito();
