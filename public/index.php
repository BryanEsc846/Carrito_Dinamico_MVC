<?php
// Archivo: /public/index.php
session_start();

// 1. Carga de configuración y conexión
require_once '../app/config/database.php';
$database = new Database();
$db = $database->getConnection();

// 2. Importación de Controladores
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/ServiceController.php';
require_once '../app/controllers/CartController.php';
require_once '../app/controllers/QuoteController.php';

// 3. Captura de la acción (por defecto el catálogo)
$action = $_GET['action'] ?? 'catalog';

// ==========================================
// GUARDIA DE SEGURIDAD GLOBAL
// Si no hay sesión y la acción no es 'login' o 'register', se bloquea el acceso
// ==========================================
if (!isset($_SESSION['user_id']) && $action !== 'login' && $action !== 'register') {
    header("Location: index.php?action=login");
    exit;
}

// 4. Enrutamiento (Routing)
switch ($action) {
    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController($db);
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;

    case 'catalog':
        $controller = new ServiceController($db);
        $controller->index();
        break;

    // Endpoints AJAX para el Carrito
    case 'add_to_cart':
        $controller = new CartController($db);
        $controller->add();
        break;

    case 'update_cart':
        $controller = new CartController($db);
        $controller->update();
        break;

    case 'remove_from_cart':
        $controller = new CartController($db);
        $controller->remove();
        break;

    // Generación y visualización de Cotizaciones
    case 'process_quote':
        $controller = new QuoteController($db);
        $controller->process();
        break;

    case 'history':
        $controller = new QuoteController($db);
        $controller->history();
        break;

    // ==========================================
    // RUTAS DEL PANEL DE ADMINISTRADOR
    // ==========================================


    case 'admin/services':
        $controller = new ServiceController($db);
        $controller->adminList();
        break;

    case 'admin/services/create':
        $controller = new ServiceController($db);
        $controller->adminCreate();
        break;

    case 'admin/services/edit':
        $controller = new ServiceController($db);
        $controller->adminEdit();
        break;

    case 'admin/services/delete':
        $controller = new ServiceController($db);
        $controller->delete();
        break;

    case 'admin/services/update':
        $controller = new ServiceController($db);
        $controller->update();
        break;

    case 'admin/quotes':
        $controller = new QuoteController($db);
        $controller->adminQuotes();
        break;

    default:
        header("Location: index.php?action=catalog");
        break;
}
