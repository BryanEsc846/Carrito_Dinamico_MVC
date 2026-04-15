<?php
// Archivo: /app/config/database.php

class Database
{
    private $host = "localhost";
    private $db_name = "cotizador_db"; // Asegúrate de haber creado esta BD en phpMyAdmin
    private $username = "root"; // Usuario por defecto de XAMPP
    private $password = "belen2duo";     // Contraseña por defecto de XAMPP (vacía)
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            // Configurar PDO para que lance excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
