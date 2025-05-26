<?php
define('APP_NAME', 'Spa Ibaiondo');
// La siguinte linea la comento para el docker
//define('APP_URL', 'http://localhost/spa-ibaiondo/public');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8080');
define('APP_VERSION', '1.0.0');

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/Madrid');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);