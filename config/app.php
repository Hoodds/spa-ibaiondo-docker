<?php
// Configuración de la aplicación
define('APP_NAME', 'Spa Ibaiondo');
// La siguinte linea la comento para el docker
//define('APP_URL', 'http://localhost/spa-ibaiondo/public');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8080');
define('APP_VERSION', '1.0.0');

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Zona horaria
date_default_timezone_set('Europe/Madrid');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);