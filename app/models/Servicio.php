<?php
class Servicio {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM servicios ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $descripcion, $duracion, $precio) {
        $stmt = $this->db->prepare("INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $duracion, $precio]);
    }

    public function update($id, $nombre, $descripcion, $duracion, $precio) {
        $stmt = $this->db->prepare("
            UPDATE servicios
            SET nombre = ?, descripcion = ?, duracion = ?, precio = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nombre, $descripcion, $duracion, $precio, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM servicios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}