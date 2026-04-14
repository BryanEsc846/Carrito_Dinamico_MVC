<?php

require_once '../app/models/Service.php';

class ServiceController {
    private $db;
    private $service;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new Service($this->db);
    }

    public function index() {
        // Obtenemos todos los servicios desde el modelo (Base de Datos)
        $stmt = $this->service->readAll();
        
        // Convertimos el resultado en un arreglo asociativo fácil de leer para la vista
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargamos la vista HTML pasándole la variable $services
        require_once '../app/views/services/catalog.php';
    }
}
?>