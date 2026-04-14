<?php
// Archivo: /app/controllers/QuoteController.php

require_once '../app/models/Service.php';
require_once '../app/models/Quote.php';

class QuoteController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Antiguo process-quote.php
    public function process() {
        if (empty($_SESSION['cart'])) {
            echo json_encode(["success" => false, "message" => "El carrito está vacío."]);
            exit;
        }

        // 1. reutiliza la validación exacta del backend
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
        $empresa = htmlspecialchars(trim($_POST['empresa'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telefono = trim($_POST['telefono'] ?? '');

        if (empty($nombre) || empty($email) || empty($telefono)) {
            echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]); exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Correo inválido."]); exit;
        }
        if (!preg_match('/^[\d\s\-+]{8,}$/', $telefono)) {
            echo json_encode(["success" => false, "message" => "Teléfono inválido."]); exit;
        }

        // 2. Preparamos el modelo con los datos de la BD
        $serviceModel = new Service($this->db);
        $stmt = $serviceModel->readAll();
        $allServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quote = new Quote($this->db);
        foreach ($_SESSION['cart'] as $id => $qty) {
            foreach ($allServices as $s) {
                if ($s['id'] == $id) {
                    $quote->agregarItem($s['id'], $qty, $s['precio'], $s['nombre']);
                }
            }
        }

        $subtotal = $quote->calcularSubtotal();

        // reutiliza la validación de $100 dólares
        if (!Quote::validarMonto($subtotal)) {
            echo json_encode(["success" => false, "message" => "El subtotal debe ser mínimo de $100.00"]);
            exit;
        }

        $quote->calcularDescuento();
        $quote->calcularIVA();
        $quote->calcularTotal();

        // 3. Generamos en BD (Usaremos el ID de sesión del usuario logueado, por ahora asumimos el ID 1)
        $userId = $_SESSION['user_id'] ?? 1; 
        
        $cliente = ['nombre' => $nombre, 'empresa' => $empresa, 'email' => $email, 'telefono' => $telefono];
        
        $resultado = $quote->generar($userId, $cliente);

        if ($resultado['success']) {
            $_SESSION['cart'] = []; // Vaciamos carrito
            echo json_encode([
                "success" => true,
                "message" => "Cotización generada con éxito.",
                "quote" => [
                    "codigo" => $resultado['codigo'],
                    "cliente" => $cliente,
                    "fecha" => date('Y-m-d H:i:s'),
                    "validez" => date('Y-m-d H:i:s', strtotime('+7 days'))
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en BD: " . $resultado['message']]);
        }
    }

    // NUEVO: Método para ver el historial (¡Ahora sí está adentro de la clase!)
    public function history() {
        // Obtenemos el ID del usuario logueado
        $userId = $_SESSION['user_id'] ?? null;
        
        // Instanciamos el modelo y le pedimos las cotizaciones de este usuario
        $quoteModel = new Quote($this->db);
        $quotes = $quoteModel->readAllByUser($userId);
        
        // Cargamos la vista pasándole la variable $quotes para que dibuje la tabla
        require_once '../app/views/quotes/history.php';
    }

} // <-- AQUÍ TERMINA LA CLASE REALMENTE
?>