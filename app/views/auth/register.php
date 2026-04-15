<?php include '../app/views/services/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 p-4">
                <h3 class="fw-bold text-center mb-4">Crear Cuenta</h3>

                <div id="messageContainer"></div>

                <form id="registerForm">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Tu nombre" required>
                        <small class="text-danger" id="errorNombre"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="tu@email.com" required>
                        <small class="text-danger" id="errorEmail"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        <small class="text-danger" id="errorPassword"></small>
                        <small class="text-muted d-block mt-1">La contraseña debe tener al menos 6 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirma tu contraseña" required>
                        <small class="text-danger" id="errorConfirm"></small>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2">Registrarse 📝</button>
                </form>

                <hr class="my-3">

                <p class="text-center text-muted">¿Ya tienes cuenta?
                    <a href="index.php?action=login" class="text-dark fw-bold">Inicia sesión aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/auth.js"></script>

<?php include '../app/views/services/footer.php'; ?>