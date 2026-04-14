<?php

class Service {
    private $conn;
    private $table_name = "services";

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $categoria;

    // Categorías válidas
    const CATEGORIAS = ['Desarrollo Web', 'Marketing', 'Soporte Técnico'];
    const PRECIO_MIN = 50;
    const PRECIO_MAX = 10000;

    // NUEVO: El constructor ahora recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Mantenemos tu método de validación como pide la rúbrica
    public function validarDatos($nombre, $descripcion, $precio, $categoria) {
        if (empty($nombre) || empty($descripcion)) {
            return false;
        }
        if ($precio < self::PRECIO_MIN || $precio > self::PRECIO_MAX) {
            return false;
        }
        if (!in_array($categoria, self::CATEGORIAS)) {
            return false;
        }
        return true;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getCategoria() { return $this->categoria; }

    // NUEVO: Método para leer de MySQL
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>