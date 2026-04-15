<?php require_once '../app/views/cart/panel.php'; ?>

<div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="quoteModalLabel">¡Cotización Generada! 🎉</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <h2 class="display-6 fw-bold text-primary mb-3" id="modal-codigo">COT-0000-0000</h2>
        <p class="lead mb-4">Tu cotización ha sido guardada exitosamente.</p>
        <div class="bg-light p-3 rounded-3 text-start">
            <p class="mb-1"><strong>Cliente:</strong> <span id="modal-cliente"></span></p>
            <p class="mb-1"><strong>Empresa:</strong> <span id="modal-empresa"></span></p>
            <p class="mb-1"><strong>Fecha de Emisión:</strong> <span id="modal-fecha"></span></p>
            <p class="mb-0 text-danger fw-bold"><strong>Válida hasta:</strong> <span id="modal-validez"></span></p>
        </div>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Seguir comprando</button>
        <a href="index.php?action=history" class="btn btn-primary px-4 fw-bold">Ver Historial</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/services-catalog.js"></script>
</body>
</html>