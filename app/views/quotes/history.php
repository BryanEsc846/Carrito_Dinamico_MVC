<?php require_once '../app/views/services/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="fw-bold mb-4">📜 Historial de Cotizaciones</h2>
    <?php if (empty($quotes)): ?>
        <div class="alert alert-info">No has generado cotizaciones todavía.</div>
    <?php else: ?>
        <div class="table-responsive d-none d-md-block shadow-sm bg-white rounded p-3">
            <table class="table align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th><th>Cliente</th><th>Emisión</th><th>Vencimiento</th><th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $q): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= $q['codigo'] ?></span></td>
                            <td><?= htmlspecialchars($q['cliente_nombre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($q['fecha_emision'])) ?></td>
                            <td class="text-danger fw-bold"><?= date('d/m/Y', strtotime($q['fecha_validez'])) ?></td>
                            <td class="fw-bold text-success">$<?= number_format($q['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/services/footer.php'; ?>