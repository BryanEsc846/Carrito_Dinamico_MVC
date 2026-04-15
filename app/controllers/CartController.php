<?php

require_once '../app/models/Service.php';
require_once '../app/models/Quote.php';

class CartController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Antiguo add-to-cart.php
    public function add() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $cantidadActual = $_SESSION['cart'][$id] ?? 0;
            if ($cantidadActual < 10) {
                $_SESSION['cart'][$id] = $cantidadActual + 1;
                $this->enviarRespuestaJSON("Servicio agregado al carrito", true);
            } else {
                echo json_encode(["success" => false, "message" => "Máximo 10 unidades permitidas"]);
            }
        }
    }

    // Antiguo update-cart.php
    public function update() {
        $id = $_POST['id'] ?? null;
        $action = $_POST['action'] ?? null;

        // NUEVO: Esto permite que el JS consulte el carrito al recargar F5
        if ($action === 'load') {
            $this->enviarRespuestaJSON("Carrito cargado", true);
            return;
        }

        if ($id && isset($_SESSION['cart'][$id])) {
            if ($action === 'add' && $_SESSION['cart'][$id] < 10) {
                $_SESSION['cart'][$id]++;
            } elseif ($action === 'remove' && $_SESSION['cart'][$id] > 1) {
                $_SESSION['cart'][$id]--;
            }
            $this->enviarRespuestaJSON("Carrito actualizado", true);
        }
    }

    // Antiguo remove-from-cart.php
    public function remove() {
        $id = $_POST['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $this->enviarRespuestaJSON("Servicio eliminado", true);
        }
    }

    // Función auxiliar para no repetir código (calcula y devuelve el JSON al JavaScript)
    private function enviarRespuestaJSON($mensaje, $success) {
        $serviceModel = new Service($this->db);
        $stmt = $serviceModel->readAll();
        $allServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quote = new Quote($this->db);
        $itemsDetalle = []; // NUEVO: Arreglo para guardar los nombres

        foreach ($_SESSION['cart'] as $id => $qty) {
            foreach ($allServices as $s) {
                if ($s['id'] == $id) {
                    $quote->agregarItem($s['id'], $qty, $s['precio'], $s['nombre']);
                    // NUEVO: Guardamos el nombre y subtotal para enviarlo al JS
                    $itemsDetalle[$id] = [
                        'nombre' => $s['nombre'],
                        'subtotal' => number_format($s['precio'] * $qty, 2)
                    ];
                }
            }
        }
        
        $quote->calcularSubtotal();
        $quote->calcularDescuento();
        $quote->calcularIVA();
        $quote->calcularTotal();

        echo json_encode([
            "success" => $success,
            "message" => $mensaje,
            "cart" => $_SESSION['cart'],
            "totals" => [
                "subtotal" => number_format($quote->calcularSubtotal(), 2),
                "descuento" => number_format($quote->calcularDescuento(), 2),
                "iva" => number_format($quote->calcularIVA(), 2),
                "total" => number_format($quote->calcularTotal(), 2),
                "count" => array_sum($_SESSION['cart']),
                "itemsDetalle" => $itemsDetalle // NUEVO: Ahora el JS recibirá esto
            ]
        ]);
        exit;
    }
}
?>