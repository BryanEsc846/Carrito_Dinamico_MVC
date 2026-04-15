<?php

class Service
{
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
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Mantenemos tu método de validación como pide la rúbrica
    public function validarDatos($nombre, $descripcion, $precio, $categoria)
    {
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
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getCategoria()
    {
        return $this->categoria;
    }

    // NUEVO: Método para leer de MySQL
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $descripcion, $precio, $categoria, $imagen = '')
    {
        $query = "INSERT INTO " . $this->table_name . " (nombre, descripcion, precio, categoria, imagen) 
                  VALUES (:nombre, :descripcion, :precio, :categoria, :imagen)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':categoria' => $categoria,
            ':imagen' => $imagen
        ]);
    }

    public function update($id, $nombre, $descripcion, $precio, $categoria, $imagen = null)
    {
        if ($imagen === null) {
            $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria = :categoria WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':id' => $id
            ]);
        }

        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria = :categoria, imagen = :imagen WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':categoria' => $categoria,
            ':imagen' => $imagen,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Cuenta cuántas veces está referido este servicio en quote_details
    public function countUsageInQuotes($id)
    {
        $query = "SELECT COUNT(*) as cnt FROM quote_details WHERE service_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['cnt']) ? intval($row['cnt']) : 0;
    }
}
