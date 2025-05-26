<?php
class Valoracion {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByServicio($idServicio) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            WHERE v.id_servicio = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$idServicio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUsuario($idUsuario) {
        $stmt = $this->db->prepare("
            SELECT v.*, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.id_usuario = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEstado($estado) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.estado = ?
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByTrabajador($idTrabajador) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            JOIN reservas r ON v.id_usuario = r.id_usuario AND v.id_servicio = r.id_servicio
            WHERE r.id_trabajador = ?
            GROUP BY v.id
            ORDER BY v.fecha_creacion DESC
        ");
        $stmt->execute([$idTrabajador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeValoracion($idUsuario, $idServicio) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM valoraciones
            WHERE id_usuario = ? AND id_servicio = ?
        ");
        $stmt->execute([$idUsuario, $idServicio]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($idUsuario, $idServicio, $puntuacion, $comentario) {
        if ($this->existeValoracion($idUsuario, $idServicio)) {
            return $this->actualizar($idUsuario, $idServicio, $puntuacion, $comentario);
        }

        $stmt = $this->db->prepare("
            INSERT INTO valoraciones (id_usuario, id_servicio, puntuacion, comentario, estado)
            VALUES (?, ?, ?, ?, 'pendiente')
        ");
        return $stmt->execute([$idUsuario, $idServicio, $puntuacion, $comentario]);
    }

    public function actualizar($idUsuario, $idServicio, $puntuacion, $comentario) {
        $stmt = $this->db->prepare("
            UPDATE valoraciones
            SET puntuacion = ?, comentario = ?, fecha_creacion = CURRENT_TIMESTAMP, estado = 'pendiente'
            WHERE id_usuario = ? AND id_servicio = ?
        ");
        return $stmt->execute([$puntuacion, $comentario, $idUsuario, $idServicio]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM valoraciones WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function cambiarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE valoraciones SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function getPuntuacionMedia($idServicio) {
        $stmt = $this->db->prepare("
            SELECT AVG(puntuacion) as media, COUNT(*) as total
            FROM valoraciones
            WHERE id_servicio = ? AND estado = 'aprobada'
        ");
        $stmt->execute([$idServicio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecientes($limite = 5) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.estado = 'aprobada'
            ORDER BY v.fecha_creacion DESC
            LIMIT ?
        ");
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadisticas() {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) FROM valoraciones");
        $stats['total'] = $stmt->fetchColumn();

        $stmt = $this->db->query("
            SELECT estado, COUNT(*) as cantidad
            FROM valoraciones
            GROUP BY estado
        ");
        $stats['por_estado'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $stmt = $this->db->query("
            SELECT AVG(puntuacion) as media
            FROM valoraciones
            WHERE estado = 'aprobada'
        ");
        $stats['media_global'] = round($stmt->fetchColumn(), 1);

        $stmt = $this->db->query("
            SELECT s.id, s.nombre, AVG(v.puntuacion) as media, COUNT(*) as total
            FROM valoraciones v
            JOIN servicios s ON v.id_servicio = s.id
            WHERE v.estado = 'aprobada'
            GROUP BY s.id
            ORDER BY media DESC
            LIMIT 5
        ");
        $stats['mejores_servicios'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }
}

