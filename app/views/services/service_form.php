<?php include '../app/views/services/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white fw-bold fs-5">
                    <?= isset($service) ? '✏️ Editar Servicio' : '➕ Crear Nuevo Servicio' ?>
                </div>
                <div class="card-body p-4">

                    <?php if (isset($message)): ?>
                        <div class="alert alert-<?= isset($error) ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form id="serviceForm" method="POST" action="index.php?action=<?= isset($service) ? 'admin/services/update' : 'admin/services/create' ?>">

                        <?php if (isset($service)): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($service['id']) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Servicio *</label>
                            <input type="text" name="nombre" class="form-control form-control-lg"
                                value="<?= isset($service) ? htmlspecialchars($service['nombre']) : '' ?>"
                                placeholder="Ej: Desarrollo de Sitio Web" required>
                            <small class="text-muted">Máximo 100 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción *</label>
                            <textarea name="descripcion" class="form-control" rows="4"
                                placeholder="Describe los detalles del servicio..." required><?= isset($service) ? htmlspecialchars($service['descripcion']) : '' ?></textarea>
                            <small class="text-muted">Mínimo 10 caracteres</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría *</label>
                                <select name="categoria" class="form-select form-select-lg" required>
                                    <option value="">-- Selecciona una categoría --</option>
                                    <option value="Desarrollo Web" <?= isset($service) && $service['categoria'] === 'Desarrollo Web' ? 'selected' : '' ?>>Desarrollo Web</option>
                                    <option value="Marketing" <?= isset($service) && $service['categoria'] === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="Soporte Técnico" <?= isset($service) && $service['categoria'] === 'Soporte Técnico' ? 'selected' : '' ?>>Soporte Técnico</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Precio USD *</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" class="form-control"
                                        value="<?= isset($service) ? htmlspecialchars($service['precio']) : '' ?>"
                                        placeholder="0.00" step="0.01" min="50" max="10000" required>
                                </div>
                                <small class="text-muted">Entre $50 y $10,000</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-end mt-4">
                            <a href="index.php?action=admin/services" class="btn btn-secondary btn-lg">
                                ← Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <?= isset($service) ? '💾 Actualizar Servicio' : '✅ Crear Servicio' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/services/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('serviceForm');
        // Solo aplica confirmación si es edición (existe input hidden id)
        if (form && form.querySelector('input[name="id"]')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: '¿Estás seguro de actualizar este servicio?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>