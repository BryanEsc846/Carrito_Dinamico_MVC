<?php include '../app/views/services/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-1">🔧 Gestión de Servicios</h1>
                <p class="text-muted">Administra el catálogo de servicios disponibles</p>
            </div>
            <a href="index.php?action=admin/services/create" class="btn btn-success btn-lg">
                ➕ Nuevo Servicio
            </a>
        </div>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?= isset($error) ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($services)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>📭 No hay servicios registrados</h4>
            <p class="mb-0">
                <a href="index.php?action=admin/services/create" class="btn btn-primary">
                    Crear el primer servicio
                </a>
            </p>
        </div>
    <?php else: ?>
        <div id="servicesContainer" class="table-responsive shadow-sm">
            <table class="table table-hover align-middle bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($s['id']) ?></strong></td>
                            <td class="fw-bold"><?= htmlspecialchars($s['nombre']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($s['categoria']) ?></span></td>
                            <td>
                                <small class="text-muted">
                                    <?= strlen($s['descripcion']) > 50
                                        ? substr(htmlspecialchars($s['descripcion']), 0, 50) . '...'
                                        : htmlspecialchars($s['descripcion']) ?>
                                </small>
                            </td>
                            <td class="fw-bold text-success">$<?= number_format($s['precio'], 2) ?></td>
                            <td>
                                <a href="index.php?action=admin/services/edit&id=<?= $s['id'] ?>"
                                    class="btn btn-sm btn-warning" title="Editar">
                                    ✏️ Editar
                                </a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="deleteService(this, <?= $s['id'] ?>)"
                                    title="Eliminar">
                                    🗑️ Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteService(button, serviceId) {
        Swal.fire({
            title: '¿Eliminar servicio?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', serviceId);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'index.php?action=admin/services/delete', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                const row = button.closest('tr');
                                if (row) row.remove();

                                const tableBody = document.querySelector('#servicesContainer table tbody');
                                if (tableBody && tableBody.querySelectorAll('tr').length === 0) {
                                    document.getElementById('servicesContainer').innerHTML = `
                                        <div class="alert alert-info text-center py-5">
                                            <h4>📭 No hay servicios registrados</h4>
                                            <p class="mb-0">
                                                <a href="index.php?action=admin/services/create" class="btn btn-primary">
                                                    Crear el primer servicio
                                                </a>
                                            </p>
                                        </div>`;
                                }

                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 2500,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        } catch (e) {
                            const serverResponse = xhr.responseText || 'No se pudo procesar la respuesta del servidor.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: serverResponse
                            });
                        }
                    }
                };

                xhr.send(formData);
            }
        });
    }
</script>

<?php include '../app/views/services/footer.php'; ?>