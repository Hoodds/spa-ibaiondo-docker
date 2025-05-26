<?php
class ReservaController {
    private $reservaModel;
    private $servicioModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->reservaModel = new Reserva();
        $this->servicioModel = new Servicio();
    }

    public function misReservas() {
        Auth::checkAuth();

        $reservas = $this->reservaModel->getByUsuario(Auth::id());

        ob_start();
        include BASE_PATH . '/app/views/reservas/mis_reservas.php';
        $content = ob_get_clean();

        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function showCrear($idServicio) {
        Auth::checkAuth();

        $servicio = $this->servicioModel->getById($idServicio);

        if (!$servicio) {
            $_SESSION['error'] = 'El servicio no existe';
            Helper::redirect('servicios');
            return;
        }

        ob_start();
        include BASE_PATH . '/app/views/reservas/crear.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function crear() {
        Auth::checkAuth();

        $idServicio = $_POST['id_servicio'] ?? '';
        $idTrabajador = $_POST['id_trabajador'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';

        if (empty($idServicio) || empty($idTrabajador) || empty($fecha) || empty($hora)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('reservas/crear/' . $idServicio);
            return;
        }

        $fechaHora = $fecha . ' ' . $hora . ':00';

        if ($this->reservaModel->create(Auth::id(), $idServicio, $idTrabajador, $fechaHora)) {
            $_SESSION['success'] = 'Reserva creada con éxito';
            Helper::redirect('reservas');
        } else {
            $_SESSION['error'] = 'Error al crear la reserva';
            Helper::redirect('reservas/crear/' . $idServicio);
        }
    }

    public function cancelar($id) {
        Auth::checkAuth();

        $reserva = $this->reservaModel->getById($id);

        if (!$reserva || $reserva['id_usuario'] != Auth::id()) {
            $_SESSION['error'] = 'No tienes permiso para cancelar esta reserva';
            Helper::redirect('reservas');
            return;
        }

        if ($this->reservaModel->updateEstado($id, 'cancelada')) {
            $_SESSION['success'] = 'Reserva cancelada con éxito';
        } else {
            $_SESSION['error'] = 'Error al cancelar la reserva';
        }

        Helper::redirect('reservas');
    }

    public function getDisponibilidad() {
        header('Content-Type: application/json');

        $idServicio = $_GET['id_servicio'] ?? '';
        $fecha = $_GET['fecha'] ?? '';

        if (empty($idServicio) || empty($fecha)) {
            echo json_encode(['error' => 'Parámetros incompletos']);
            return;
        }

        $disponibilidad = $this->reservaModel->getDisponibilidad($idServicio, $fecha);
        echo json_encode($disponibilidad);
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $idTrabajador = $_POST['id_trabajador'];
            $idServicio = $_POST['id_servicio'] ?? null;
            $idUsuario = $_POST['id_usuario'] ?? null;

            if (empty($id) || empty($estado) || empty($fecha) || empty($hora) || empty($idTrabajador)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/reservas');
                return;
            }

            $fechaHora = $fecha . ' ' . $hora . ':00';

            if ($estado !== 'cancelada') {
                $reservaActual = $this->reservaModel->getById($id);

                if ($fechaHora != $reservaActual['fecha_hora'] || $idTrabajador != $reservaActual['id_trabajador']) {
                    $disponibilidad = $this->reservaModel->verificarDisponibilidad($idTrabajador, $fechaHora, $id);

                    if (!$disponibilidad) {
                        $_SESSION['error'] = 'El trabajador ya tiene una reserva en ese horario.';
                        Helper::redirect('/admin/reservas');
                        return;
                    }
                }
            }

            $result = $this->reservaModel->update($id, $estado, $fechaHora, $idTrabajador);

            if ($result) {
                $_SESSION['success'] = 'Reserva actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la reserva.';
            }

            Helper::redirect('/admin/reservas');
        }
    }
}