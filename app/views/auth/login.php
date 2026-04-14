<?php include '../app/views/services/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h3 class="fw-bold text-center mb-4">Iniciar Sesión</h3>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>
                <form action="index.php?action=login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2">Entrar 🛡️</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/services/footer.php'; ?>