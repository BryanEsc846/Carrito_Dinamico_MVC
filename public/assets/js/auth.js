// Archivo: /public/assets/js/auth.js
// Validación y manejo de formularios de autenticación con AJAX puro

document.addEventListener("DOMContentLoaded", function () {
  const registerForm = document.getElementById("registerForm");

  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      handleRegister();
    });
  }
});

/**
 * Validaciones Frontend para Registro
 */
function validateRegisterForm() {
  const nombre = document.getElementById("nombre").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  // Limpiar mensajes de error previos
  clearErrors();

  let isValid = true;

  // Validar nombre
  if (nombre === "") {
    showError("errorNombre", "El nombre es obligatorio.");
    isValid = false;
  } else if (nombre.length < 3) {
    showError("errorNombre", "El nombre debe tener al menos 3 caracteres.");
    isValid = false;
  }

  // Validar email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "") {
    showError("errorEmail", "El correo es obligatorio.");
    isValid = false;
  } else if (!emailRegex.test(email)) {
    showError("errorEmail", "El correo electrónico no es válido.");
    isValid = false;
  }

  // Validar contraseña
  if (password === "") {
    showError("errorPassword", "La contraseña es obligatoria.");
    isValid = false;
  } else if (password.length < 6) {
    showError(
      "errorPassword",
      "La contraseña debe tener al menos 6 caracteres.",
    );
    isValid = false;
  }

  // Validar confirmación de contraseña
  if (confirmPassword === "") {
    showError("errorConfirm", "Debes confirmar la contraseña.");
    isValid = false;
  } else if (password !== confirmPassword) {
    showError("errorConfirm", "Las contraseñas no coinciden.");
    isValid = false;
  }

  return isValid;
}

/**
 * Manejar el envío del formulario de registro
 */
function handleRegister() {
  // Validar primero en frontend
  if (!validateRegisterForm()) {
    showMessage("Por favor, corrige los errores en el formulario.", "danger");
    return;
  }

  const formData = new FormData(document.getElementById("registerForm"));

  // Enviar datos mediante AJAX (XMLHttpRequest)
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "index.php?action=register", true);

  // Indicar que es una solicitud AJAX
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.success) {
            showMessage(
              "¡Registro exitoso! Redirigiendo al login...",
              "success",
            );

            // Limpiar formulario
            document.getElementById("registerForm").reset();

            // Redirigir después de 2 segundos
            setTimeout(function () {
              window.location.href = "index.php?action=login";
            }, 2000);
          } else {
            showMessage(
              response.message || "Error al registrar el usuario.",
              "danger",
            );
          }
        } catch (e) {
          showMessage("Error al procesar la respuesta del servidor.", "danger");
        }
      } else {
        showMessage(
          "Error de comunicación con el servidor. (HTTP " + xhr.status + ")",
          "danger",
        );
      }
    }
  };

  xhr.onerror = function () {
    showMessage("Error de conexión. Por favor, intenta de nuevo.", "danger");
  };

  // Enviar los datos del formulario
  xhr.send(formData);
}

/**
 * Mostrar mensaje de error en campo específico
 */
function showError(fieldId, message) {
  const errorElement = document.getElementById(fieldId);
  if (errorElement) {
    errorElement.textContent = message;
  }
}

/**
 * Limpiar todos los mensajes de error
 */
function clearErrors() {
  const errorElements = document.querySelectorAll('[id^="error"]');
  errorElements.forEach(function (element) {
    element.textContent = "";
  });
}

/**
 * Mostrar mensaje en el contenedor de mensajes
 */
function showMessage(message, type) {
  const messageContainer = document.getElementById("messageContainer");

  if (!messageContainer) return;

  const alertClass = type === "success" ? "alert-success" : "alert-danger";
  const alertHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;

  messageContainer.innerHTML = alertHTML;

  // Auto-cerrar el mensaje después de 5 segundos (solo para errores)
  if (type === "danger") {
    setTimeout(function () {
      messageContainer.innerHTML = "";
    }, 5000);
  }
}
