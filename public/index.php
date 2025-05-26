<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir la ruta base
define('BASE_PATH', dirname(__DIR__));

// Cargar configuraciones
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Cargar clases core
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Helper.php';

// Iniciar sesion (MOVIDO AQUI, DESPUES DE CARGAR app.php)
session_start();

// Inicializar el router
$router = new Router();

// Cargar rutas
require_once BASE_PATH . '/config/routes.php';

// Procesar la solicitud
$router->dispatch();