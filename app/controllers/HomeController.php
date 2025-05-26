<?php
class HomeController {
    private $servicioModel;

    public function __construct() {
        require_once BASE_PATH . '/app/models/Servicio.php';
        $this->servicioModel = new Servicio();
    }

    public function index() {
        $serviciosDestacados = $this->servicioModel->getAll();

        ob_start();
        include BASE_PATH . '/app/views/home/home.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }

    public function contacto() {
        ob_start();
        include BASE_PATH . '/app/views/home/contacto.php';
        $content = ob_get_clean();
        include BASE_PATH . '/app/views/layouts/main.php';
    }
}