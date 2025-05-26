<?php
class HomeController {
    private $servicioModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->servicioModel = new Servicio();
    }

    public function index() {
        // Obtener servicios destacados para mostrar en la página principal
        $serviciosDestacados = $this->servicioModel->getAll();

        // Cargar la vista
        ob_start();
        include BASE_PATH . '/app/views/home/home.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function contacto() {
        // Cargar la vista de contacto
        ob_start();
        include BASE_PATH . '/app/views/home/contacto.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }
}