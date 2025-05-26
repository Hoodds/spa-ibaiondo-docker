<?php
class ContactoController {
    private $mensajeModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/MensajeContacto.php';
        $this->mensajeModel = new MensajeContacto();
    }

    public function enviar() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $asunto = $_POST['asunto'] ?? '';
        $mensaje = $_POST['mensaje'] ?? '';

        if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            Helper::redirect('contacto');
            return;
        }

        if ($this->mensajeModel->guardar([
            'nombre' => $nombre,
            'email' => $email,
            'asunto' => $asunto,
            'mensaje' => $mensaje
        ])) {
            $_SESSION['success'] = 'Tu mensaje ha sido enviado correctamente.';
        } else {
            $_SESSION['error'] = 'Hubo un problema al enviar tu mensaje.';
        }

        Helper::redirect('contacto');
    }
}