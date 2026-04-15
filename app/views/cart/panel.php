<div class="offcanvas offcanvas-end" tabindex="-1" id="cartPanel">
  <div class="offcanvas-header bg-dark text-white">
    <h5 class="offcanvas-title">Resumen de Cotización</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <div id="cart-content">
        <p class="text-center">Cargando carrito...</p>
    </div>
    <hr>
    <div id="cart-totals" class="d-none">
        <div class="d-flex justify-content-between mb-1"><span>Subtotal:</span><span id="st-val" class="fw-bold">$0.00</span></div>
        <div class="d-flex justify-content-between mb-1 text-danger"><span>Descuento:</span><span id="ds-val">-$0.00</span></div>
        <div class="d-flex justify-content-between mb-1 text-muted"><span>IVA (13%):</span><span id="iva-val">$0.00</span></div>
        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
            <span class="fs-5 fw-bold">Total:</span><span id="total-val" class="fs-5 fw-bold text-success">$0.00</span>
        </div>

        <form id="checkout-form" class="mt-4 border-top pt-3 d-none">
            <h6 class="fw-bold mb-3">Datos del Cliente</h6>
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre completo" required>
            <input type="text" name="empresa" class="form-control mb-2" placeholder="Empresa (Opcional)">
            <input type="email" name="email" class="form-control mb-2" placeholder="Correo electrónico" required>
            <input type="tel" name="telefono" class="form-control mb-3" placeholder="Teléfono" required>
            <button type="submit" class="btn btn-primary w-100 fw-bold">📄 Generar Cotización</button>
        </form>
    </div>
  </div>
</div>