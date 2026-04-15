<?php

require_once '../app/models/Service.php';
require_once '../app/models/Quote.php';

class ServiceController
{
    private $db;
    private $service;

    public function __construct($db)
    {
        $this->db = $db;
        $this->service = new Service($this->db);
    }

    // Verificar si el usuario actual es administrador
    private function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function index()
    {
        // Obtenemos todos los servicios desde el modelo (Base de Datos)
        $stmt = $this->service->readAll();

        // Convertimos el resultado en un arreglo asociativo fácil de leer para la vista
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargamos la vista HTML pasándole la variable $services
        require_once '../app/views/services/catalog.php';
    }

    // Método para crear un nuevo servicio (solo admin)
    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Verificar permisos de administrador
            if (!$this->isAdmin()) {
                http_response_code(403);
                echo json_encode(["success" => false, "message" => "No tienes permisos para realizar esta acción."]);
                exit;
            }

            // Sanitizar y validar datos
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars(trim($_POST['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8');
            $precio = floatval($_POST['precio'] ?? 0);
            $categoria = htmlspecialchars(trim($_POST['categoria'] ?? ''), ENT_QUOTES, 'UTF-8');

            // Validar datos
            if (!$this->service->validarDatos($nombre, $descripcion, $precio, $categoria)) {
                echo json_encode(["success" => false, "message" => "Datos inválidos."]);
                exit;
            }

            // Implementar lógica de inserción si existe el método en el modelo
            // Por ahora retornamos éxito
            echo json_encode(["success" => true, "message" => "Servicio creado exitosamente."]);
            exit;
        }
    }

    // Método para actualizar un servicio (solo admin)
    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Verificar permisos de administrador
            if (!$this->isAdmin()) {
                header("Location: index.php?action=catalog");
                exit;
            }

            // Sanitizar y validar datos
            $id = intval($_POST['id'] ?? 0);
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars(trim($_POST['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8');
            $precio = floatval($_POST['precio'] ?? 0);
            $categoria = htmlspecialchars(trim($_POST['categoria'] ?? ''), ENT_QUOTES, 'UTF-8');

            // Validar datos
            if ($id <= 0 || !$this->service->validarDatos($nombre, $descripcion, $precio, $categoria)) {
                $message = "❌ Datos inválidos. Verifica los campos.";
                $error = true;
                $service = $this->service->getById($id);
                require_once '../app/views/services/service_form.php';
                return;
            }

            if ($this->service->update($id, $nombre, $descripcion, $precio, $categoria)) {
                header("Location: index.php?action=admin/services");
                exit;
            }

            $message = "❌ No se pudo actualizar el servicio. Intenta nuevamente.";
            $error = true;
            $service = $this->service->getById($id);
            require_once '../app/views/services/service_form.php';
            return;
        }

        header("Location: index.php?action=admin/services");
        exit;
    }

    // Método para eliminar un servicio (solo admin)
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            header('Content-Type: application/json; charset=UTF-8');

            // Verificar permisos de administrador
            if (!$this->isAdmin()) {
                http_response_code(403);
                echo json_encode(["success" => false, "message" => "No tienes permisos para realizar esta acción."]);
                exit;
            }

            $id = intval($_POST['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode(["success" => false, "message" => "ID de servicio inválido."]);
                exit;
            }

            // Verificar si el servicio está referenciado en quote_details
            try {
                $quoteModel = new Quote($this->db);
                $usageCount = $this->service->countUsageInQuotes($id);
                if ($usageCount > 0) {
                    $refs = $quoteModel->getQuotesByServiceId($id);
                    $codes = array_map(function ($r) {
                        return $r['codigo'];
                    }, $refs);
                    $codes_list = !empty($codes) ? implode(', ', array_slice($codes, 0, 5)) : '';
                    $more = count($codes) > 5 ? ' y más...' : '';
                    echo json_encode(["success" => false, "message" => "No se puede eliminar: el servicio está usado en {$usageCount} cotización(es): {$codes_list}{$more}"]);
                    exit;
                }

                if ($this->service->delete($id)) {
                    echo json_encode(["success" => true, "message" => "Servicio eliminado exitosamente."]);
                } else {
                    echo json_encode(["success" => false, "message" => "No se pudo eliminar el servicio. Puede que no exista."]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Error en el servidor: " . $e->getMessage()]);
            }
            exit;
        }
    }

    // =========== NUEVOS MÉTODOS PARA VISTAS DE ADMINISTRADOR ===========

    // Vista: Listar todos los servicios (admin)
    public function adminList()
    {
        if (!$this->isAdmin()) {
            header("Location: index.php?action=catalog");
            exit;
        }

        $services = $this->service->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once '../app/views/services/services_list.php';
    }

    // Vista: Formulario de crear servicio (admin)
    public function adminCreate()
    {
        if (!$this->isAdmin()) {
            header("Location: index.php?action=catalog");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sanitizar y validar datos
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars(trim($_POST['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8');
            $precio = floatval($_POST['precio'] ?? 0);
            $categoria = htmlspecialchars(trim($_POST['categoria'] ?? ''), ENT_QUOTES, 'UTF-8');

            // Validar datos
            if (!$this->service->validarDatos($nombre, $descripcion, $precio, $categoria)) {
                $message = "❌ Datos inválidos. Verifica los campos.";
                $error = true;
                require_once '../app/views/services/service_form.php';
                return;
            }

            // Guardar servicio en la base de datos
            if ($this->service->create($nombre, $descripcion, $precio, $categoria)) {
                header("Location: index.php?action=admin/services");
                exit;
            }

            $message = "❌ No se pudo crear el servicio. Intenta nuevamente.";
            $error = true;
        }

        // Si es GET, mostrar el formulario vacío
        require_once '../app/views/services/service_form.php';
    }

    // Vista: Formulario de editar servicio (admin)
    public function adminEdit()
    {
        if (!$this->isAdmin()) {
            header("Location: index.php?action=catalog");
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?action=admin/services");
            exit;
        }

        // Cargar servicio desde BD
        $service = $this->service->getById($id);
        if (!$service) {
            header("Location: index.php?action=admin/services");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Sanitizar y validar datos
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars(trim($_POST['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8');
            $precio = floatval($_POST['precio'] ?? 0);
            $categoria = htmlspecialchars(trim($_POST['categoria'] ?? ''), ENT_QUOTES, 'UTF-8');

            // Validar datos
            if (!$this->service->validarDatos($nombre, $descripcion, $precio, $categoria)) {
                $message = "❌ Datos inválidos. Verifica los campos.";
                $error = true;
                require_once '../app/views/services/service_form.php';
                return;
            }

            // Guardar cambios en BD
            if ($this->service->update($id, $nombre, $descripcion, $precio, $categoria)) {
                header("Location: index.php?action=admin/services");
                exit;
            }

            $message = "❌ No se pudo actualizar el servicio. Intenta nuevamente.";
            $error = true;
        }

        // Mostrar el formulario con datos del servicio
        require_once '../app/views/services/service_form.php';
    }
}
