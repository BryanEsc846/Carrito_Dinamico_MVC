<?php
// Archivo: /app/models/Quote.php

class Quote
{
    private $conn;
    private $table_quotes = "quotes";
    private $table_details = "quote_details";

    // Propiedades privadas obligatorias 
    private $codigo;
    private $cliente;
    private $items = [];
    private $subtotal = 0;
    private $descuento = 0;
    private $iva = 0;
    private $total = 0;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para agregar ítems al objeto antes de procesar
    public function agregarItem($service_id, $cantidad, $precio_unitario, $nombre)
    {
        $this->items[] = [
            'service_id' => $service_id,
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario
        ];
    }

    // Cálculos Atómicos exigidos por el documento [cite: 148-151]
    public function calcularSubtotal()
    {
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['precio_unitario'] * $item['cantidad'];
        }
        return $this->subtotal;
    }

    public function calcularDescuento()
    {
        if ($this->subtotal >= 2500) $porc = 0.15;
        elseif ($this->subtotal >= 1000) $porc = 0.10;
        elseif ($this->subtotal >= 500) $porc = 0.05;
        else $porc = 0;

        $this->descuento = $this->subtotal * $porc;
        return $this->descuento;
    }

    public function calcularIVA()
    {
        $this->iva = ($this->subtotal - $this->descuento) * 0.13;
        return $this->iva;
    }

    public function calcularTotal()
    {
        $this->total = ($this->subtotal - $this->descuento) + $this->iva;
        return $this->total;
    }

    // El corazón de la Fase 2: Guardado en MySQL [cite: 152, 219]
    public function generar($userId, $datosCliente)
    {
        try {
            $this->conn->beginTransaction();

            $this->codigo = self::generarCodigo();
            $fecha_emision = date('Y-m-d H:i:s');
            $fecha_validez = date('Y-m-d H:i:s', strtotime('+7 days'));

            // 1. Insertar Cabecera de la Cotización
            $query = "INSERT INTO " . $this->table_quotes . " 
                      (codigo, user_id, cliente_nombre, cliente_empresa, cliente_email, cliente_telefono, subtotal, descuento, iva, total, fecha_emision, fecha_validez)
                      VALUES (:codigo, :user_id, :nom, :emp, :email, :tel, :sub, :desc, :iva, :total, :emision, :validez)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':codigo' => $this->codigo,
                ':user_id' => $userId,
                ':nom' => $datosCliente['nombre'],
                ':emp' => $datosCliente['empresa'],
                ':email' => $datosCliente['email'],
                ':tel' => $datosCliente['telefono'],
                ':sub' => $this->subtotal,
                ':desc' => $this->descuento,
                ':iva' => $this->iva,
                ':total' => $this->total,
                ':emision' => $fecha_emision,
                ':validez' => $fecha_validez
            ]);

            $quoteId = $this->conn->lastInsertId();

            // 2. Insertar Detalles (Items)
            $queryDetail = "INSERT INTO " . $this->table_details . " 
                            (quote_id, service_id, cantidad, precio_unitario)
                            VALUES (:qid, :sid, :cant, :precio)";
            $stmtDetail = $this->conn->prepare($queryDetail);

            foreach ($this->items as $item) {
                $stmtDetail->execute([
                    ':qid' => $quoteId,
                    ':sid' => $item['service_id'],
                    ':cant' => $item['cantidad'],
                    ':precio' => $item['precio_unitario']
                ]);
            }

            $this->conn->commit();
            return ["success" => true, "codigo" => $this->codigo];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public static function generarCodigo()
    {
        // En MVC real, podrías contar las filas en la BD para el correlativo
        return "COT-" . date('Y') . "-" . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public static function validarMonto($monto)
    {
        return $monto >= 100;
    }

    // Método para leer todas las cotizaciones de un usuario específico
    public function readAllByUser($userId)
    {
        $query = "SELECT * FROM " . $this->table_quotes . " WHERE user_id = :uid ORDER BY fecha_emision DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NUEVOS MÉTODOS PARA ADMINISTRADOR

    // Obtener todas las cotizaciones del sistema (para admin)
    public function readAll()
    {
        $query = "SELECT q.*, u.nombre as usuario_nombre 
                  FROM " . $this->table_quotes . " q
                  LEFT JOIN users u ON q.user_id = u.id
                  ORDER BY q.fecha_emision DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cotizaciones recientes (últimas N cotizaciones)
    public function readAllRecent($limit = 5)
    {
        $query = "SELECT q.*, u.nombre as usuario_nombre 
                  FROM " . $this->table_quotes . " q
                  LEFT JOIN users u ON q.user_id = u.id
                  ORDER BY q.fecha_emision DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cotizaciones por búsqueda (cliente o email)
    public function readAllWithSearch($search)
    {
        $search = "%{$search}%";
        $query = "SELECT q.*, u.nombre as usuario_nombre 
                  FROM " . $this->table_quotes . " q
                  LEFT JOIN users u ON q.user_id = u.id
                  WHERE q.cliente_nombre LIKE :search 
                  OR q.codigo LIKE :search
                  ORDER BY q.fecha_emision DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cotizaciones que contienen un servicio específico
    public function getQuotesByServiceId($service_id)
    {
        $query = "SELECT q.id, q.codigo, q.fecha_emision, q.total
                  FROM " . $this->table_quotes . " q
                  INNER JOIN " . $this->table_details . " d ON d.quote_id = q.id
                  WHERE d.service_id = :sid
                  GROUP BY q.id
                  ORDER BY q.fecha_emision DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sid', $service_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
