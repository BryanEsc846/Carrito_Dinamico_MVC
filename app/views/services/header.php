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
    <div class="ms-auto d-flex align-items-center">
        <a href="index.php?action=history" class="btn btn-outline-light btn-lg me-3">📄 Historial</a>
        <button class="btn btn-outline-light position-relative btn-lg px-4" data-bs-toggle="offcanvas" data-bs-target="#cartPanel">
            🛒 Carrito
            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
            </span>
        </button>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php?action=logout" class="btn btn-danger ms-3">Cerrar Sesión</a>
        <?php endif; ?>
    </div>
  </div>
</nav>