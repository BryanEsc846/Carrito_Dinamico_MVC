<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ServicePOO - Sistema MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/services-catalog.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">🛡️ ServicePOO</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex align-items-center gap-3 flex-wrap">

                    <!-- MENÚ DE USUARIO ESTÁNDAR -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
                        <a href="index.php?action=catalog" class="btn btn-outline-light btn-sm">🏪 Catálogo</a>
                        <a href="index.php?action=history" class="btn btn-outline-light btn-sm">📄 Mis Cotizaciones</a>
                    <?php endif; ?>

                    <!-- MENÚ DE ADMINISTRADOR -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?action=admin/services" class="btn btn-outline-warning btn-sm">🔧 Servicios</a>
                        <a href="index.php?action=admin/quotes" class="btn btn-outline-warning btn-sm">📋 Cotizaciones</a>
                    <?php endif; ?>

                    <!-- CARRITO (Solo usuarios estándar) -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
                        <button class="btn btn-outline-light btn-sm position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartPanel">
                            🛒 Carrito
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                            </span>
                        </button>
                    <?php endif; ?>

                    <!-- INFORMACIÓN DEL USUARIO -->
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <span class="text-light me-2">👤 <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <?php endif; ?>

                    <!-- CERRAR SESIÓN -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?action=logout" class="btn btn-danger btn-sm">🚪 Salir</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>