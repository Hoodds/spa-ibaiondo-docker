<?php
class AdminController {
    private $usuarioModel;
    private $trabajadorModel;
    private $servicioModel;
    private $reservaModel;
    private $valoracionModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Usuario.php';
        require_once BASE_PATH . '/app/models/Trabajador.php';
        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';

        $this->usuarioModel = new Usuario();
        $this->trabajadorModel = new Trabajador();
        $this->servicioModel = new Servicio();
        $this->reservaModel = new Reserva();
        $this->valoracionModel = new Valoracion();

        Auth::checkAdmin();
    }

    public function dashboard() {
        $stats = [
            'usuarios' => count($this->usuarioModel->getAll()),
            'trabajadores' => count($this->trabajadorModel->getAll()),
            'servicios' => count($this->servicioModel->getAll()),
            'reservas' => count($this->reservaModel->getAll()),
            'valoraciones' => $this->valoracionModel->getEstadisticas()
        ];

        ob_start();
        include BASE_PATH . '/app/views/admin/dashboard.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function listarUsuarios() {
        $usuarios = $this->usuarioModel->getAll();

        ob_start();
        include BASE_PATH . '/app/views/admin/usuarios.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function listarTrabajadores() {
        $trabajadores = $this->trabajadorModel->getAll();

        ob_start();
        include BASE_PATH . '/app/views/admin/trabajadores.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function listarServicios() {
        $servicios = $this->servicioModel->getAll();

        foreach ($servicios as $key => $servicio) {
            $puntuacion = $this->valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicios[$key]['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicios[$key]['total_valoraciones'] = $puntuacion['total'];
        }

        ob_start();
        include BASE_PATH . '/app/views/admin/servicios.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function listarReservas() {
        $servicios = $this->servicioModel->getAll();
        $trabajadores = $this->trabajadorModel->getAll();
        $usuarios = $this->usuarioModel->getAll();

        $filtros = [
            'fecha' => $_GET['filtroFecha'] ?? null,
            'servicio' => $_GET['filtroServicio'] ?? null,
            'trabajador' => $_GET['filtroTrabajador'] ?? null,
            'estado' => $_GET['filtroEstado'] ?? null,
        ];
        $reservas = $this->reservaModel->getFiltered($filtros);

        ob_start();
        include BASE_PATH . '/app/views/admin/reservas.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function listarValoraciones() {
        $valoraciones = $this->valoracionModel->getAll();

        ob_start();
        include BASE_PATH . '/app/views/admin/valoraciones.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function valoracionesPendientes() {
        $valoraciones = $this->valoracionModel->getByEstado('pendiente');

        ob_start();
        include BASE_PATH . '/app/views/admin/valoraciones_pendientes.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/admin.php';
    }

    public function aprobarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);

        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }

        if ($this->valoracionModel->cambiarEstado($id, 'aprobada')) {
            $_SESSION['success'] = 'Valoración aprobada correctamente';
        } else {
            $_SESSION['error'] = 'Error al aprobar la valoración';
        }

        Helper::redirect('admin/valoraciones/pendientes');
    }

    public function rechazarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);

        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }

        if ($this->valoracionModel->cambiarEstado($id, 'rechazada')) {
            $_SESSION['success'] = 'Valoración rechazada correctamente';
        } else {
            $_SESSION['error'] = 'Error al rechazar la valoración';
        }

        Helper::redirect('admin/valoraciones/pendientes');
    }

    public function eliminarValoracion($id) {
        $valoracion = $this->valoracionModel->getById($id);

        if (!$valoracion) {
            $_SESSION['error'] = 'La valoración no existe';
            Helper::redirect('admin/valoraciones');
            return;
        }

        if ($this->valoracionModel->eliminar($id)) {
            $_SESSION['success'] = 'Valoración eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la valoración';
        }

        Helper::redirect('admin/valoraciones');
    }

    public function eliminarReserva($id) {
        if ($this->reservaModel->eliminar($id)) {
            $_SESSION['success'] = 'Reserva eliminada correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar la reserva.';
        }
        Helper::redirect('admin/reservas');
    }

    public function eliminarServicio($id) {
        if ($this->servicioModel->delete($id)) {
            $_SESSION['success'] = 'Servicio eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el servicio.';
        }
        Helper::redirect('admin/servicios');
    }

    public function eliminarTrabajador($id) {
        if ($this->trabajadorModel->eliminar($id)) {
            $_SESSION['success'] = 'Trabajador eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el trabajador.';
        }
        Helper::redirect('admin/trabajadores');
    }

    public function eliminarUsuario($id) {
        if ($this->usuarioModel->eliminar($id)) {
            $_SESSION['success'] = 'Usuario eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario.';
        }
        Helper::redirect('admin/usuarios');
    }

    public function crearUsuario() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('admin/usuarios');
            return;
        }

        if ($this->usuarioModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado.';
            Helper::redirect('admin/usuarios');
            return;
        }

        if ($this->usuarioModel->crear([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password
        ])) {
            $_SESSION['success'] = 'Usuario creado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al crear el usuario.';
        }
        Helper::redirect('admin/usuarios');
    }

    public function crearTrabajador() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $rol = $_POST['rol'] ?? 'trabajador';
        $password = $_POST['password'] ?? '';

        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('admin/trabajadores');
            return;
        }

        if ($this->trabajadorModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado.';
            Helper::redirect('admin/trabajadores');
            return;
        }

        if ($this->trabajadorModel->crear([
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol,
            'password' => $password
        ])) {
            $_SESSION['success'] = 'Trabajador creado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al crear el trabajador.';
        }
        Helper::redirect('admin/trabajadores');
    }

    public function crearServicio() {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $duracion = $_POST['duracion'] ?? '';
        $precio = $_POST['precio'] ?? '';

        if (empty($nombre) || empty($descripcion) || empty($duracion) || empty($precio)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('admin/servicios');
            return;
        }

        if ($this->servicioModel->create($nombre, $descripcion, $duracion, $precio)) {
            $_SESSION['success'] = 'Servicio creado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al crear el servicio.';
        }
        Helper::redirect('admin/servicios');
    }

    public function crearReserva() {
        $idUsuario = $_POST['id_usuario'] ?? '';
        $idServicio = $_POST['id_servicio'] ?? '';
        $idTrabajador = $_POST['id_trabajador'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $estado = $_POST['estado'] ?? 'pendiente';

        if (empty($idUsuario) || empty($idServicio) || empty($idTrabajador) || empty($fecha) || empty($hora)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('admin/reservas');
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
                Helper::redirect('admin/reservas');
                return;
            }

            if (!$horaDisponible) {
                $_SESSION['error'] = 'La hora seleccionada no está disponible para este trabajador.';
                Helper::redirect('admin/reservas');
                return;
            }
        }

        if ($this->reservaModel->create($idUsuario, $idServicio, $idTrabajador, $fechaHora)) {
            if ($estado !== 'pendiente') {
                $db = Database::getInstance()->getConnection();
                $reservaId = $db->lastInsertId();
                $this->reservaModel->updateEstado($reservaId, $estado);
            }
            $_SESSION['success'] = 'Reserva creada correctamente.';
        } else {
            $_SESSION['error'] = 'Error al crear la reserva.';
        }

        Helper::redirect('admin/reservas');
    }
}

