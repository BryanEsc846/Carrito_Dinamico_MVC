<?php

class QuoteDetail {
    private $conn;
    private $table_name = "quote_details";

    public $id;
    public $quote_id;
    public $service_id;
    public $cantidad;
    public $precio_unitario;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer los servicios dentro de una cotización específica (Para la vista de Historial)
    public function readByQuoteId($quote_id) {
        // Hacemos un JOIN con la tabla services para traer el nombre del servicio automáticamente
        $query = "SELECT qd.cantidad, qd.precio_unitario, s.nombre AS service_nombre 
                  FROM " . $this->table_name . " qd
                  LEFT JOIN services s ON qd.service_id = s.id
                  WHERE qd.quote_id = :quote_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quote_id', $quote_id);
        $stmt->execute();
        
        return $stmt; // Retornamos el conjunto de resultados para que el Controlador los use
    }
}
?>