<?php
class UsuarioController {
    private $usuarioModel;
    private $reservaModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Usuario.php';
        require_once BASE_PATH . '/app/models/Reserva.php';
        $this->usuarioModel = new Usuario();
        $this->reservaModel = new Reserva();
    }

    public function showLogin() {
        if (Auth::check()) {
            Helper::redirect('perfil');
        }
        ob_start();
        include BASE_PATH . '/app/views/usuarios/login.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('login');
        }

        $user = $this->usuarioModel->login($email, $password);

        if ($user) {
            Auth::login($user);
            Helper::redirect('perfil');
        } else {
            $_SESSION['error'] = 'Credenciales incorrectas';
            Helper::redirect('login');
        }
    }

    public function showRegistro() {
        if (Auth::check()) {
            Helper::redirect('perfil');
        }
        ob_start();
        include BASE_PATH . '/app/views/usuarios/registro.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function registro() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            Helper::redirect('registro');
        }

        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            Helper::redirect('registro');
        }

        if ($this->usuarioModel->emailExists($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            Helper::redirect('registro');
        }

        if ($this->usuarioModel->register($nombre, $email, $password)) {
            $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión';
            Helper::redirect('login');
        } else {
            $_SESSION['error'] = 'Error al registrar el usuario';
            Helper::redirect('registro');
        }
    }

    public function perfil() {
        Auth::checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Auth::id();
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = 'Nombre y email son obligatorios.';
                Helper::redirect('perfil');
            }
            if (!empty($password) && $password !== $password_confirm) {
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                Helper::redirect('perfil');
            }

            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            } else {
                $password = null;
            }
            $this->usuarioModel->update($id, $nombre, $email, $password);

            $passwordToSave = !empty($password) ? $password : null;
            $result = $this->usuarioModel->update($id, $nombre, $email, $passwordToSave);

            if ($result) {
                $_SESSION['success'] = 'Perfil actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil.';
            }
            Helper::redirect('perfil');
        }

        $usuario = $this->usuarioModel->getById(Auth::id());
        $reservas = $this->reservaModel->getByUsuario(Auth::id());

        ob_start();
        include BASE_PATH . '/app/views/usuarios/perfil.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function logout() {
        Auth::logout();
        session_start();
        $_SESSION['success'] = 'Has cerrado sesión correctamente.';
        Helper::redirect('login');
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

            if (empty($id) || empty($nombre) || empty($email)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                Helper::redirect('/admin/usuarios');
                return;
            }

            $usuarioModel = new Usuario();
            $result = $usuarioModel->update($id, $nombre, $email, $password);

            if ($result) {
                $_SESSION['success'] = 'Usuario actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el usuario.';
            }

            Helper::redirect('/admin/usuarios');
        }
    }
}