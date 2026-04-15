<?php include '../app/views/services/header.php'; ?>

<section class="hero-section text-white text-center py-5">
    <div class="container">
        <h1 class="fw-bold display-5">🌐 Servicios Disponibles</h1>
        <p class="lead">Tu sistema profesional de cotización de servicios</p>

        <div class="stats-box mt-4 bg-white text-dark rounded-4 shadow-sm p-3 d-inline-block">
            <div class="d-flex align-items-center gap-4 justify-content-center px-4">
                
                <div class="text-center">
                    <h3 class="fw-bold text-primary mb-0"><?= count($services) ?></h3>
                    <small class="text-muted">Servicios Disponibles</small>
                </div>

                <div class="text-center border-start border-end px-4">
                    <h3 class="fw-bold text-primary mb-0">
                        <?= count(array_unique(array_column($services, 'categoria'))) ?>
                    </h3>
                    <small class="text-muted">Categorías</small>
                </div>

                <div class="text-center pe-3">
                    <h3 class="fw-bold text-primary mb-0">100%</h3>
                    <small class="text-muted">Por expertos</small>
                </div>

                <select id="category-filter" class="form-select border-primary" style="max-width: 220px;">
                    <option value="">Todas las categorías</option>
                    <option value="Desarrollo Web">Desarrollo Web</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Soporte Técnico">Soporte Técnico</option>
                </select>
                
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <?php foreach ($services as $s): ?>
            <div class="col-md-4 mb-4">
                <div class="card custom-card shadow-lg h-100 border-0">
                    <img src="assets/img/<?= $s['imagen'] ?>" class="card-img-top" alt="<?= $s['nombre'] ?>">
                    <div class="card-body">
                        <h4 class="fw-bold"><?= $s['nombre'] ?></h4>
                        <p class="text-primary fw-semibold mb-2"><?= $s['categoria'] ?></p>
                        <p class="text-muted small mb-3"><?= $s['descripcion'] ?></p>
                        <div class="price-box text-center">$<?= number_format($s['precio'], 2) ?></div>
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn add-to-cart flex-grow-1" data-id="<?= $s['id'] ?>">
                                🛒 Añadir al carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../app/views/services/footer.php'; ?>