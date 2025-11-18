let lastScroll = 0;
const header = document.getElementById("index-header");

window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset;

  if (currentScroll > lastScroll) {
    header.style.top = "0"; // Siempre visible al bajar
  }

  lastScroll = currentScroll;
});
