<?php

class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para autenticar al usuario (Login)
    public function login($email, $password)
    {
        $query = "SELECT id, nombre, email, password, rol FROM " . $this->table_name . " WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Si el correo existe en la base de datos
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos si la contraseña coincide con el hash guardado (Validación de Seguridad obligatoria)
            if (password_verify($password, $row['password'])) {
                // Llenamos las propiedades del objeto con los datos del usuario
                $this->id = $row['id'];
                $this->nombre = $row['nombre'];
                $this->email = $row['email'];
                $this->rol = $row['rol'];
                return true;
            }
        }
        return false;
    }

    // Método para registrar un nuevo usuario
    public function register($nombre, $email, $password)
    {
        // Verificar si el email ya existe
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "El correo ya está registrado."];
        }

        // Hashear la contraseña usando BCRYPT
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $rol = 'user'; // Rol por defecto

        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, rol) 
                      VALUES (:nombre, :email, :password, :rol)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':rol', $rol);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Usuario registrado exitosamente."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()];
        }

        return ["success" => false, "message" => "Error al registrar el usuario."];
    }
}
