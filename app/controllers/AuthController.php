<?php

require_once '../app/models/User.php';

class AuthController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = new User($this->db);

            if ($user->login($email, $password)) {
                // Guardamos los datos en la sesión segura
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->nombre;
                $_SESSION['user_role'] = $user->rol;

                // Redirigimos al catálogo
                header("Location: index.php?action=catalog");
                exit;
            } else {
                $error = "Credenciales incorrectas.";
                require_once '../app/views/auth/login.php';
            }
        } else {
            // Si entra por GET, solo mostramos la vista del formulario
            require_once '../app/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar que la solicitud es AJAX
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
                // Si no es AJAX, mostrar la vista del formulario
                require_once '../app/views/auth/register.php';
                return;
            }

            // Sanitizar y validar datos
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            // Validaciones básicas
            if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
                echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["success" => false, "message" => "Correo electrónico inválido."]);
                exit;
            }

            if (strlen($password) < 6) {
                echo json_encode(["success" => false, "message" => "La contraseña debe tener al menos 6 caracteres."]);
                exit;
            }

            if ($password !== $confirm_password) {
                echo json_encode(["success" => false, "message" => "Las contraseñas no coinciden."]);
                exit;
            }

            // Sanitizar nombre
            $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

            // Usar el modelo para registrar
            $user = new User($this->db);
            $result = $user->register($nombre, $email, $password);

            echo json_encode($result);
            exit;
        } else {
            // Si entra por GET, mostrar la vista del formulario
            require_once '../app/views/auth/register.php';
        }
    }
}
