<?php include '../app/views/services/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-1">📋 Cotizaciones del Sistema</h1>
            <p class="text-muted">Monitorea todas las cotizaciones generadas</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="GET" class="d-flex gap-2 flex-wrap align-items-end">
                        <input type="hidden" name="action" value="admin/quotes">

                        <div class="flex-grow-1">
                            <label class="form-label fw-bold small">Buscar por Cliente/Email</label>
                            <input type="text" name="search" class="form-control"
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                placeholder="Nombre o correo del cliente...">
                        </div>

                        <button type="submit" class="btn btn-primary">🔍 Buscar</button>
                        <a href="index.php?action=admin/quotes" class="btn btn-secondary">Limpiar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary fw-bold"><?= $stats['total'] ?? 0 ?></h3>
                    <small class="text-muted">Total de Cotizaciones</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success fw-bold">$<?= number_format($stats['monto_total'] ?? 0, 2) ?></h3>
                    <small class="text-muted">Monto Total Cotizado</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning fw-bold">$<?= number_format($stats['promedio'] ?? 0, 2) ?></h3>
                    <small class="text-muted">Promedio por Cotización</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Cotizaciones -->
    <?php if (empty($quotes)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>📭 No hay cotizaciones</h4>
            <p class="mb-0">Aún no se han generado cotizaciones en el sistema.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-hover align-middle bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Usuario</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $q): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary" title="Código de cotización">
                                    <?= htmlspecialchars($q['codigo']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($q['usuario_nombre'] ?? 'N/A') ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($q['cliente_nombre']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($q['cliente_email']) ?></td>
                            <td class="small"><?= htmlspecialchars($q['cliente_telefono']) ?></td>
                            <td>
                                <div class="small">
                                    <strong><?= date('d/m/Y', strtotime($q['fecha_emision'])) ?></strong>
                                    <small class="text-muted d-block"><?= date('H:i', strtotime($q['fecha_emision'])) ?></small>
                                </div>
                            </td>
                            <td class="fw-bold text-success">$<?= number_format($q['total'], 2) ?></td>
                            <td>
                                <?php
                                $fecha_validez = strtotime($q['fecha_validez']);
                                $hoy = time();
                                if ($fecha_validez < $hoy) {
                                    echo '<span class="badge bg-danger">❌ Vencida</span>';
                                } else {
                                    echo '<span class="badge bg-success">✅ Vigente</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../app/views/services/footer.php'; ?>