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
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check() && isset($_SESSION['trabajador'])) {
            Helper::redirect('trabajador/dashboard');
        }

        // Procesar el formulario de login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                Helper::redirect('trabajador/login');
                return;
            }

            // Intentar login
            $trabajador = $this->trabajadorModel->login($email, $password);

            if ($trabajador) {
                // Guardar datos del trabajador en la sesión
                $_SESSION['trabajador'] = true;
                $_SESSION['trabajador_id'] = $trabajador['id'];
                $_SESSION['trabajador_nombre'] = $trabajador['nombre'];
                $_SESSION['trabajador_rol'] = $trabajador['rol'];

                // Si es admin, redirigir al panel de admin
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

        // Mostrar formulario de login
        ob_start();
        include BASE_PATH . '/app/views/trabajadores/login.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function dashboard() {
        // Verificar si es trabajador
        $this->checkTrabajador();

        // Obtener datos del trabajador
        $trabajador = $this->trabajadorModel->getById($_SESSION['trabajador_id']);

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las reservas y valoraciones
            $reservas = $this->reservaModel->getAll();
            $valoraciones = $this->valoracionModel->getAll();

            // Obtener servicios (para estadísticas)
            require_once BASE_PATH . '/app/models/Servicio.php';
            $servicioModel = new Servicio();
            $servicios = $servicioModel->getAll();

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus datos
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/dashboard.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function misReservas() {
        // Verificar si es trabajador
        $this->checkTrabajador();

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las reservas (como admin)
            require_once BASE_PATH . '/app/models/Servicio.php';
            require_once BASE_PATH . '/app/models/Usuario.php'; // Add this line
            $servicioModel = new Servicio();
            $usuarioModel = new Usuario(); // Add this line

            $servicios = $servicioModel->getAll();
            $trabajadores = $this->trabajadorModel->getAll();
            $usuarios = $usuarioModel->getAll(); // Add this line

            // Aplicar filtros si los hay
            $filtros = [
                'fecha' => $_GET['filtroFecha'] ?? null,
                'servicio' => $_GET['filtroServicio'] ?? null,
                'trabajador' => $_GET['filtroTrabajador'] ?? null,
                'estado' => $_GET['filtroEstado'] ?? null,
            ];
            $reservas = $this->reservaModel->getFiltered($filtros);

            // Usar la vista de reservas para recepcionistas
            ob_start();
            include BASE_PATH . '/app/views/trabajadores/reservas_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus reservas
            $reservas = $this->reservaModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_reservas.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function misValoraciones() {
        // Verificar si es trabajador
        $this->checkTrabajador();

        if ($_SESSION['trabajador_rol'] === 'recepcionista') {
            // Para recepcionistas, mostrar todas las valoraciones
            $valoraciones = $this->valoracionModel->getAll();

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/valoraciones_recepcionista.php';
            $content = ob_get_clean();
        } else {
            // Para otros trabajadores, mostrar solo sus valoraciones
            $valoraciones = $this->valoracionModel->getByTrabajador($_SESSION['trabajador_id']);

            ob_start();
            include BASE_PATH . '/app/views/trabajadores/mis_valoraciones.php';
            $content = ob_get_clean();
        }

        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }

    public function logout() {
        // Eliminar todas las variables de sesión relacionadas con el trabajador
        unset($_SESSION['trabajador']);
        unset($_SESSION['trabajador_id']);
        unset($_SESSION['trabajador_nombre']);
        unset($_SESSION['trabajador_rol']);

        // Como medida adicional, podemos regenerar el ID de sesión
        session_regenerate_id(true);

        // Redirigir al login
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

            // Validar los datos
            if (empty($id) || empty($nombre) || empty($email) || empty($rol)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/trabajadores');
                return;
            }

            // Cuando el trabajador edita su perfil
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $password = null;
            }
            $this->trabajadorModel->update($id, $nombre, $email, $rol, $password);

            // Actualizar en la base de datos
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

        // Obtener la reserva
        $reserva = $this->reservaModel->getById($id);

        // Validar que la reserva exista
        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Si es recepcionista, puede confirmar cualquier reserva
        // Si es otro trabajador, solo las suyas
        if ($_SESSION['trabajador_rol'] !== 'recepcionista' &&
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Solo permitir completar si está pendiente
        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes completar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Actualizar el estado de la reserva a 'confirmada'
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

        // Obtener la reserva
        $reserva = $this->reservaModel->getById($id);

        // Validar que la reserva exista
        if (!$reserva) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Si es recepcionista, puede cancelar cualquier reserva
        // Si es otro trabajador, solo las suyas
        if ($_SESSION['trabajador_rol'] !== 'recepcionista' &&
            $reserva['id_trabajador'] != $_SESSION['trabajador_id']) {
            $_SESSION['error'] = 'No tienes permiso para gestionar esta reserva.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Solo permitir cancelar si está pendiente
        if ($reserva['estado'] !== 'pendiente') {
            $_SESSION['error'] = 'Solo puedes cancelar reservas pendientes.';
            Helper::redirect('trabajador/reservas');
            return;
        }

        // Actualizar el estado de la reserva a 'cancelada'
        $resultado = $this->reservaModel->actualizarEstado($id, 'cancelada');

        if ($resultado) {
            $_SESSION['success'] = 'Reserva cancelada correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo cancelar la reserva.';
        }

        Helper::redirect('trabajador/reservas');
    }

    public function editarReserva() {
        // Verificar permisos - solo recepcionista puede editar
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

            // Validar los datos
            if (empty($id) || empty($estado) || empty($fecha) || empty($hora) || empty($idTrabajador)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/trabajador/reservas');
                return;
            }

            // Crear la fecha y hora en formato MySQL
            $fechaHora = $fecha . ' ' . $hora . ':00';

            // Verificar disponibilidad del trabajador en esa fecha/hora
            // Esta verificación se omite si el estado es 'cancelada'
            if ($estado !== 'cancelada') {
                $reservaActual = $this->reservaModel->getById($id);

                // Solo verificar conflictos si se cambió la fecha/hora/trabajador
                if ($fechaHora != $reservaActual['fecha_hora'] || $idTrabajador != $reservaActual['id_trabajador']) {
                    $disponibilidad = $this->reservaModel->verificarDisponibilidad($idTrabajador, $fechaHora, $id);

                    if (!$disponibilidad) {
                        $_SESSION['error'] = 'El trabajador ya tiene una reserva en ese horario.';
                        Helper::redirect('/trabajador/reservas');
                        return;
                    }
                }
            }

            // Actualizar en la base de datos
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
        // Verificar permisos - solo recepcionista puede crear reservas
        $this->checkTrabajador();
        if ($_SESSION['trabajador_rol'] !== 'recepcionista') {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            Helper::redirect('trabajador/dashboard');
            return;
        }

        // Validar datos del formulario
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

        // Crear la fecha y hora en formato MySQL
        $fechaHora = $fecha . ' ' . $hora . ':00';

        // Verificar disponibilidad del trabajador
        if ($estado !== 'cancelada') {
            // Obtener instancia del modelo Reserva para usar getDisponibilidad
            $disponibilidad = $this->reservaModel->getDisponibilidad($idServicio, $fecha);
            $trabajadorDisponible = false;
            $horaDisponible = false;

            foreach ($disponibilidad as $disp) {
                if ($disp['id_trabajador'] == $idTrabajador) {
                    $trabajadorDisponible = true;
                    // Verificar si la hora solicitada está en las disponibles
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

        // Crear la reserva
        $resultado = $this->reservaModel->create($idUsuario, $idServicio, $idTrabajador, $fechaHora);

        if ($resultado) {
            // Si se desea un estado diferente a pendiente, actualizar después de crear
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

    // Add this method to your TrabajadorController class

    public function listarServicios() {
        // Verify user is a worker
        $this->checkTrabajador();

        // Only receptionists should access this view
        if ($_SESSION['trabajador_rol'] !== 'recepcionista') {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
            Helper::redirect('trabajador/dashboard');
            return;
        }

        // Load the service model
        require_once BASE_PATH . '/app/models/Servicio.php';
        require_once BASE_PATH . '/app/models/Valoracion.php';

        $servicioModel = new Servicio();
        $valoracionModel = new Valoracion();

        // Get all services with their ratings
        $servicios = $servicioModel->getAll();

        // For each service, get its average rating
        foreach ($servicios as $key => $servicio) {
            $puntuacion = $valoracionModel->getPuntuacionMedia($servicio['id']);
            $servicios[$key]['puntuacion_media'] = $puntuacion['media'] ? round($puntuacion['media'], 1) : 0;
            $servicios[$key]['total_valoraciones'] = $puntuacion['total'];
        }

        // Load the view
        ob_start();
        include BASE_PATH . '/app/views/trabajadores/servicios_recepcionista.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/trabajador.php';
    }
}

