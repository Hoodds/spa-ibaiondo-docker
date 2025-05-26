<?php
class TrabajadorController {
    private $trabajadorModel;
    private $reservaModel;
    private $valoracionModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';

        $this->trabajadorModel = new Trabajador();
        $this->reservaModel = new Reserva();
        $this->valoracionModel = new Valoracion();
    }

    public function login() {
        if (Auth::check() && isset($_SESSION['trabajador'])) {
            Helper::redirect('trabajador/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                Helper::redirect('trabajador/login');
                return;
            }

            $trabajador = $this->trabajadorModel->login($email, $password);

            if ($trabajador) {
                $_SESSION['trabajador'] = true;
                $_SESSION['trabajador_id'] = $trabajador['id'];
                $_SESSION['trabajador_nombre'] = $trabajador['nombre'];
                $_SESSION['trabajador_rol'] = $trabajador['rol'];

                if ($trabajador['rol'] === 'admin') {
                    Auth::login($trabajador, true);
                    Helper::redirect('admin');
                    return;
                }

                Helper::redirect('trabajador/dashboard');
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                Helper::redirect('trabajador/login');
            }
        }

        ob_start();
        include BASE_PATH . '/app/views/trabajadores/login.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function dashboard() {
        $this->checkTrabajador();

        $trabajador = $this->trabajadorModel->getById($_SESSION['trabajador_id']);

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            $reservas = $this->reservaModel->getAll();
            $valoraciones = $this->valoracionModel->getAll();

            require_once BASE_PATH . '/app/models/Servicio.php';
            $servicioModel = new Servicio();
            $servicios = $servicioModel->getAll();

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard_recepcionista.php';
            $content = ob_get_clean();
        } else {
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function misReservas() {
        $this->checkTrabajador();

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            require_once BASE_PATH . '/app/models/Servicio.php';
            require_once BASE_PATH . '/app/models/Usuario.php';
            $servicioModel = new Servicio();
            $usuarioModel = new Usuario();

            $servicios = $servicioModel->getAll();
            $trabajadores = $this->trabajadorModel->getAll();
            $usuarios = $usuarioModel->getAll();

            $filtros = [
                'fecha' => $_GET['filtroFecha'] ?? null,
                'servicio' => $_GET['filtroServicio'] ?? null,
                'trabajador' => $_GET['filtroTrabajador'] ?? null,
                'estado' => $_GET['filtroEstado'] ?? null,
            ];
            $reservas = $this->reservaModel->getFiltered($filtros);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/reservas_recepcionista.php';
            $content = ob_get_clean();
        } else {
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_reservas.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function misValoraciones() {
        $this->checkTrabajador();

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            $valoraciones = $this->valoracionModel->getAll();

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/valoraciones_recepcionista.php';
            $content = ob_get_clean();
        } else {
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_valoraciones.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function logout() {
        unset($_SESSION['trabajador']);
        unset($_SESSION['trabajador_id']);
        unset($_SESSION['trabajador_nombre']);
        unset($_SESSION['trabajador_rol']);

        session_regenerate_id(true);

        $_SESSION['success'] = 'Has cerrado sesión correctamente.';
        Helper::redirect('login');
    }

    private function checkTrabajador() {
        if (!isset($_SESSION['trabajador']) || !$_SESSION['trabajador'] ||
            !isset($_SESSION['trabajador_id']) || !isset($_SESSION['trabajador_rol'])) {
            $_SESSION['error'] = 'Debes iniciar sesión como trabajador';
            Helper::redirect('trabajador/login');
            exit;
        }
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

            if (empty($id) || empty($nombre) || empty($email) || empty($rol)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/trabajadores');
                return;
            }

            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $password = null;
            }
            $this->trabajadorModel->update($id, $nombre, $email, $rol, $password);

            $trabajadorModel = new Trabajador();
            $result = $trabajadorModel->update($id, $nombre, $email, $rol, $password);

            if ($result) {
                $_SESSION['success'] = 'Trabajador actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el trabajador.';
            }

            Helper::redirect('/admin/trabajadores');
        }
    }

    public function completarReserva($id) {
        $this->checkTrabajador();

        $reserva = $this->reservaModel->getById($id);

        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        if ($_SESSION['trabajador_rol'] !== 'recepcionista' &&
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes completar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        $resultado = $this->reservaModel->actualizarEstado($id, 'confirmada');

        if ($resultado) {
            $_SESSION['success'] = 'Reserva marcada como completada.';
        } else {
            $_SESSION['error'] = 'No se pudo completar la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }

    public function cancelarReserva($id) {
        $this->checkTrabajador();

        $reserva = $this->reservaModel->getById($id);

        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        if ($_SESSION['trabajador_rol'] !== 'recepcionista' &&
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes cancelar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        $resultado = $this->reservaModel->actualizarEstado($id, 'cancelada');

        if ($resultado) {
            $_SESSION['success'] = 'Reserva cancelada correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo cancelar la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }

    public function editarReserva() {
        $this->checkTrabajador();
        if ($_SESSION['trabajador_rol'] !== 'recepcionista') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            Helper::redirect('trabajador/dashboard');
            return;
        }

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
                Helper::redirect('/trabajador/reservas');
                return;
            }

            $fechaHora = $fecha . ' ' . $hora . ':00';

            if ($estado !== 'cancelada') {
                $reservaActual = $this->reservaModel->getById($id);

                if ($fechaHora != $reservaActual['fecha_hora'] || $idTrabajador != $reservaActual['id_trabajador']) {
                    $disponibilidad = $this->reservaModel->verificarDisponibilidad($idTrabajador, $fechaHora, $id);

                    if (!$disponibilidad) {
                        $_SESSION['error'] = 'El trabajador ya tiene una reserva en ese horario.';
                        Helper::redirect('/trabajador/reservas');
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

            Helper::redirect('/trabajador/reservas');
        }
    }

    public function crearReserva() {
        $this->checkTrabajador();
        if ($_SESSION['trabajador_rol'] !== 'recepcionista') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            Helper::redirect('trabajador/dashboard');
            return;
        }

        $idUsuario = $_POST['id_usuario'] ?? '';
        $idServicio = $_POST['id_servicio'] ?? '';
        $idTrabajador = $_POST['id_trabajador'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $estado = $_POST['estado'] ?? 'pendiente';

        if (empty($idUsuario) || empty($idServicio) || empty($idTrabajador) || empty($fecha) || empty($hora)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        $fechaHora = $fecha . ' ' . $hora . ':00';

        if ($estado !== 'cancelada') {
            $disponibilidad = $this->reservaModel->getDisponibilidad($idServicio, $fecha);
            $trabajadorDisponible = false;
            $horaDisponible = false;

            foreach ($disponibilidad as $disp) {
                if ($disp['id_trabajador'] == $idTrabajador) {
                    $trabajadorDisponible = true;
                    if (in_array($hora, $disp['horas_disponibles'])) {
                        $horaDisponible = true;
                    }
                    break;
                }
            }

            if (!$trabajadorDisponible) {
                $_SESSION['error'] = 'El trabajador seleccionado no está disponible en esta fecha.';
                Helper::redirect('trabajador/reservas');
                return;
            }

            if (!$horaDisponible) {
                $_SESSION['error'] = 'La hora seleccionada no está disponible para este trabajador.';
                Helper::redirect('trabajador/reservas');
                return;
            }
        }

        $resultado = $this->reservaModel->create($idUsuario, $idServicio, $idTrabajador, $fechaHora);

        if ($resultado) {
            if ($estado !== 'pendiente') {
                $db = Database::getInstance()->getConnection();
                $reservaId = $db->lastInsertId();
                $this->reservaModel->updateEstado($reservaId, $estado);
            }
            $_SESSION['success'] = 'Reserva creada correctamente.';
        } else {
            $_SESSION['error'] = 'Error al crear la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }


    public function listarServicios() {
        $this->checkTrabajador();

        if ($_SESSION['trabajador_rol'] !== 'recepcionista') {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
            Helper::redirect('trabajador/dashboard');
            return;
        }

        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';

        $servicioModel = new Servicio();
        $valoracionModel = new Valoracion();

        $servicios = $servicioModel->getAll();

        foreach ($servicios as $key => $servicio) {
            $puntuacion = $valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicios[$key]['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicios[$key]['total_valoraciones'] = $puntuacion['total'];
        }

        ob_start();
        include BASE_PATH . '/app/views/trabajadores/servicios_recepcionista.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
}

