document.addEventListener("DOMContentLoaded", function () {
  const cartContent = document.getElementById("cart-content");
  const cartTotals = document.getElementById("cart-totals");
  const categoryFilter = document.getElementById("category-filter");
  
  // Preguntar al servidor por el estado del carrito al entrar a la página
  fetch("index.php?action=update_cart", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "action=load",
  })
  .then(res => res.json())
  .then(data => {
      if (data.cart && Object.keys(data.cart).length > 0) {
          refreshCartUI(data);
      } else {
          cartContent.innerHTML = '<p class="text-center">El carrito está vacío</p>';
      }
  });

  function filterByCategory(category) {
    const cards = document.querySelectorAll(".custom-card");
    cards.forEach((card) => {
      const categoryElement = card.querySelector(".text-primary");
      if (!category || categoryElement.textContent.trim() === category) {
        card.parentElement.style.display = "block";
      } else {
        card.parentElement.style.display = "none";
      }
    });
  }

  if (categoryFilter) {
    categoryFilter.addEventListener("change", function () {
      filterByCategory(this.value);
    });
  }

function refreshCartUI(data) {
  const cartData = data.cart;
  let totalItems = Object.values(cartData).reduce((a, b) => a + b, 0);
  document.getElementById("cart-count").textContent = totalItems;

  if (totalItems === 0) {
    cartContent.innerHTML = '<p class="text-center">El carrito está vacío</p>';
    cartTotals.classList.add("d-none");
    return;
  }

  cartContent.innerHTML = "";
  for (const [id, qty] of Object.entries(cartData)) {
    let nombre = `Servicio ID: ${id}`;
    let subtotalItem = "0.00";

    if (data.totals && data.totals.itemsDetalle && data.totals.itemsDetalle[id]) {
        nombre = data.totals.itemsDetalle[id].nombre;
        subtotalItem = data.totals.itemsDetalle[id].subtotal;
    }

    cartContent.innerHTML += `
              <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                  <div class="flex-grow-1 pe-3">
                      <small class="d-block fw-bold mb-1 text-dark">${nombre}</small>
                      <div class="d-flex align-items-center justify-content-between mt-1">
                          <div class="btn-group btn-group-sm shadow-sm">
                              <button class="btn btn-outline-secondary update-qty fw-bold" data-id="${id}" data-action="remove">-</button>
                              <span class="px-3 border d-flex align-items-center bg-light">${qty}</span>
                              <button class="btn btn-outline-secondary update-qty fw-bold" data-id="${id}" data-action="add">+</button>
                          </div>
                          <span class="text-success fw-bold small ms-2">$${subtotalItem}</span>
                      </div>
                  </div>
                  <button class="btn btn-sm btn-danger remove-item shadow-sm" data-id="${id}" title="Eliminar servicio">🗑</button>
              </div>`;
  }
  cartTotals.classList.remove("d-none");

  if (data.totals) {
    const subtotalEl = document.getElementById('st-val');
    const descuentoEl = document.getElementById('ds-val');
    const ivaEl = document.getElementById('iva-val');
    const totalEl = document.getElementById('total-val');

    if (subtotalEl) subtotalEl.textContent = '$' + data.totals.subtotal;
    if (descuentoEl) descuentoEl.textContent = '-$' + data.totals.descuento;
    if (ivaEl) ivaEl.textContent = '$' + data.totals.iva;
    if (totalEl) totalEl.textContent = '$' + data.totals.total;
}
}

document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", function () {
        let id = this.dataset.id;
        // CAMBIO 1: Ruta actualizada a MVC
        fetch("index.php?action=add_to_cart", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id,
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                refreshCartUI(data); 
                Swal.fire({
                    toast: true, position: 'top-begin', icon: 'success',
                    title: data.message, showConfirmButton: false, timer: 3000
                });
            } else {
                Swal.fire({ icon: 'warning', title: 'Atención', text: data.message });
            }
        });
    });
});

cartContent.addEventListener("click", function (e) {
    if (e.target.classList.contains("update-qty")) {
        const id = e.target.dataset.id;
        const action = e.target.dataset.action;
        // CAMBIO 2: Ruta actualizada a MVC
        fetch("index.php?action=update_cart", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&action=${action}`,
        })
        .then((res) => res.json())
        .then((data) => refreshCartUI(data)); 
    }

    if (e.target.classList.contains("remove-item")) {
        const id = e.target.dataset.id;
        // CAMBIO 3: Ruta actualizada a MVC
        fetch("index.php?action=remove_from_cart", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}`,
        })
        .then((res) => res.json())
        .then((data) => refreshCartUI(data)); 
    }
});

const checkoutForm = document.getElementById("checkout-form");
  const cartTotalsBox = document.getElementById("cart-totals");

  function toggleCheckoutForm() {
      const currentCount = parseInt(document.getElementById("cart-count").textContent) || 0;
      if (currentCount > 0) {
          checkoutForm.classList.remove("d-none");
          cartTotalsBox.classList.remove("d-none");
      } else {
          checkoutForm.classList.add("d-none");
      }
  }
  
  const observer = new MutationObserver(toggleCheckoutForm);
  observer.observe(document.getElementById("cart-count"), { childList: true });

  checkoutForm.addEventListener("submit", function (e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const email = formData.get('email').trim();
      const telefono = formData.get('telefono').trim();

      const subtotalText = document.getElementById('st-val').textContent.replace('$', '').replace(',', '');
      if (parseFloat(subtotalText) < 100) {
          Swal.fire({ icon: 'warning', title: 'Monto insuficiente', text: 'El subtotal debe ser mínimo de $100.00' });
          return;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
          Swal.fire({ icon: 'error', title: 'Correo Inválido', text: 'Por favor, ingresa un correo electrónico válido.' });
          return;
      }

      const phoneRegex = /^[\d\s\-+]{8,}$/; 
      if (!phoneRegex.test(telefono)) {
          Swal.fire({ icon: 'error', title: 'Teléfono Inválido', text: 'El teléfono debe tener al menos 8 dígitos.' });
          return;
      }

      // CAMBIO 4: Ruta actualizada a MVC
      fetch("index.php?action=process_quote", {
          method: "POST",
          body: formData
      })
      .then(res => res.json())
      .then(data => {
          if (data.success) {
              refreshCartUI({ cart: {}, totals: null });
              this.reset();
              
              const offcanvasEl = document.getElementById('cartPanel');
              bootstrap.Offcanvas.getInstance(offcanvasEl).hide();

              document.getElementById("modal-codigo").textContent = data.quote.codigo;
              document.getElementById("modal-cliente").textContent = data.quote.cliente.nombre;
              document.getElementById("modal-empresa").textContent = data.quote.cliente.empresa || "N/A";
              
              const fEmision = new Date(data.quote.fecha).toLocaleDateString('es-ES');
              const fValidez = new Date(data.quote.validez).toLocaleDateString('es-ES');
              document.getElementById("modal-fecha").textContent = fEmision;
              document.getElementById("modal-validez").textContent = fValidez;

              new bootstrap.Modal(document.getElementById('quoteModal')).show();
          } else {
              Swal.fire({ icon: 'warning', title: 'Atención', text: data.message });
          }
      }).catch(err => {
          console.error("Error del servidor:", err);
          Swal.fire({ icon: 'error', title: 'Error', text: 'Error interno al procesar.' });
      });
  });
  toggleCheckoutForm();
});