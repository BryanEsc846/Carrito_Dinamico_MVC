<?php

require_once '../app/models/User.php';

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login() {
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

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
?>