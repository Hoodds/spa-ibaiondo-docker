<?php
class Reserva {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio,
                   t.nombre as nombre_trabajador, s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUsuario($idUsuario) {
        $stmt = $this->db->prepare("
            SELECT r.*, s.nombre as nombre_servicio, t.nombre as nombre_trabajador,
                   s.duracion, s.precio
            FROM reservas r
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            WHERE r.id_usuario = ?
            ORDER BY r.fecha_hora DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByTrabajador($idTrabajador) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio,
                   s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            WHERE r.id_trabajador = ?
            ORDER BY r.fecha_hora
        ");
        $stmt->execute([$idTrabajador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio,
                   t.nombre as nombre_trabajador, s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            ORDER BY r.fecha_hora DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($idUsuario, $idServicio, $idTrabajador, $fechaHora) {
        $stmt = $this->db->prepare("
            INSERT INTO reservas (id_usuario, id_servicio, id_trabajador, fecha_hora, estado)
            VALUES (?, ?, ?, ?, 'pendiente')
        ");
        return $stmt->execute([$idUsuario, $idServicio, $idTrabajador, $fechaHora]);
    }

    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function update($id, $estado, $fechaHora, $idTrabajador) {
        $stmt = $this->db->prepare("
            UPDATE reservas
            SET estado = ?, fecha_hora = ?, id_trabajador = ?
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $fechaHora, $idTrabajador, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM reservas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getDisponibilidad($idServicio, $fecha) {
        $stmt = $this->db->prepare("
            SELECT t.id, t.nombre
            FROM trabajadores t
            WHERE t.rol IN ('masajista', 'terapeuta')
            ORDER BY t.nombre
        ");
        $stmt->execute();
        $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT duracion FROM servicios WHERE id = ?");
        $stmt->execute([$idServicio]);
        $duracion = $stmt->fetchColumn();

        $horaInicio = 9;
        $horaFin = 20;

        $disponibilidad = [];

        foreach ($trabajadores as $trabajador) {
            $stmt = $this->db->prepare("
                SELECT r.fecha_hora, s.duracion
                FROM reservas r
                JOIN servicios s ON r.id_servicio = s.id
                WHERE r.id_trabajador = ?
                AND DATE(r.fecha_hora) = ?
                AND r.estado != 'cancelada'
            ");
            $stmt->execute([$trabajador['id'], $fecha]);
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ocupado = [];
            foreach ($reservas as $reserva) {
                $dt = new DateTime($reserva['fecha_hora']);
                $horaReserva = (int)$dt->format('G');
                $minutoReserva = (int)$dt->format('i');
                $inicioEnMinutos = $horaReserva * 60 + $minutoReserva;
                $finEnMinutos = $inicioEnMinutos + $reserva['duracion'];

                $ocupado[] = [
                    'inicio' => $inicioEnMinutos,
                    'fin' => $finEnMinutos
                ];
            }

            $horasDisponibles = [];
            for ($hora = $horaInicio; $hora < $horaFin; $hora++) {
                for ($minuto = 0; $minuto < 60; $minuto += 30) {
                    $inicioEnMinutos = $hora * 60 + $minuto;
                    $finEnMinutos = $inicioEnMinutos + $duracion;

                    $disponible = true;
                    foreach ($ocupado as $rango) {
                        if ($inicioEnMinutos < $rango['fin'] && $finEnMinutos > $rango['inicio']) {
                            $disponible = false;
                            break;
                        }
                    }

                    if ($disponible && $finEnMinutos <= $horaFin * 60) {
                        $horaFormato = sprintf("%02d:%02d", $hora, $minuto);
                        $horasDisponibles[] = $horaFormato;
                    }
                }
            }

            if (!empty($horasDisponibles)) {
                $disponibilidad[] = [
                    'id_trabajador' => $trabajador['id'],
                    'nombre_trabajador' => $trabajador['nombre'],
                    'horas_disponibles' => $horasDisponibles
                ];
            }
        }

        return $disponibilidad;
    }

    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function getFiltered($filtros) {
        $sql = "SELECT r.*, u.nombre as nombre_usuario, s.nombre as nombre_servicio,
                   t.nombre as nombre_trabajador, s.duracion, s.precio
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN servicios s ON r.id_servicio = s.id
            JOIN trabajadores t ON r.id_trabajador = t.id
            WHERE 1=1";
        $params = [];

        if ($filtros['fecha']) {
            $sql .= " AND DATE(r.fecha_hora) = ?";
            $params[] = $filtros['fecha'];
        }
        if ($filtros['servicio']) {
            $sql .= " AND r.id_servicio = ?";
            $params[] = $filtros['servicio'];
        }
        if ($filtros['trabajador']) {
            $sql .= " AND r.id_trabajador = ?";
            $params[] = $filtros['trabajador'];
        }
        if ($filtros['estado']) {
            $sql .= " AND r.estado = ?";
            $params[] = $filtros['estado'];
        }

        $sql .= " ORDER BY r.fecha_hora DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM reservas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verificarDisponibilidad($idTrabajador, $fechaHora, $idReservaExcluir = null) {
        $dtReserva = new DateTime($fechaHora);
        $fechaSolo = $dtReserva->format('Y-m-d');

        $duracionMinutos = 60;

        $dtFinReserva = clone $dtReserva;
        $dtFinReserva->modify("+{$duracionMinutos} minutes");

        $sql = "SELECT r.*, s.duracion
                FROM reservas r
                JOIN servicios s ON r.id_servicio = s.id
                WHERE r.id_trabajador = ?
                AND DATE(r.fecha_hora) = ?
                AND r.estado != 'cancelada'";

        $params = [$idTrabajador, $fechaSolo];

        if ($idReservaExcluir) {
            $sql .= " AND r.id != ?";
            $params[] = $idReservaExcluir;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reservas as $reserva) {
            $dtReservaExistente = new DateTime($reserva['fecha_hora']);

            $duracionExistente = $reserva['duracion'];
            $dtFinReservaExistente = clone $dtReservaExistente;
            $dtFinReservaExistente->modify("+{$duracionExistente} minutes");

            if ($dtReserva < $dtFinReservaExistente && $dtFinReserva > $dtReservaExistente) {
                return false;
            }
        }

        return true;
    }
}