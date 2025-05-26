<?php
class MensajeContacto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function guardar($data) {
        $stmt = $this->db->prepare("
            INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['asunto'],
            $data['mensaje']
        ]);
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM mensajes_contacto ORDER BY fecha DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}