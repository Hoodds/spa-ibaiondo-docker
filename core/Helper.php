<?php
class Helper {
    public static function url($path = '') {
        // Asegurarse de que la URL incluya index.php
        $base = APP_URL;

        // Si APP_URL no incluye index.php, añadirlo
        if (strpos($base, 'index.php') === false) {
            $base .= '/index.php';
        }

        // Si la ruta está vacía, devolver la URL base
        if (empty($path)) {
            return rtrim($base, '/');
        }

        // Añadir la ruta a la URL base
        return $base . '/' . ltrim($path, '/');
    }

    // Nueva función para archivos estáticos
    public static function asset($path = '') {
        // Para archivos estáticos, NO incluir index.php
        $base = APP_URL;

        // Si la ruta está vacía, devolver la URL base
        if (empty($path)) {
            return rtrim($base, '/');
        }

        // Añadir la ruta a la URL base
        return $base . '/assets/' . ltrim($path, '/');
    }

    public static function redirect($path) {
        header('Location: ' . self::url($path));
        exit;
    }

    public static function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function formatDate($date, $format = 'd/m/Y H:i') {
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }

    public static function formatPrice($price) {
        return number_format($price, 2, ',', '.') . ' €';
    }

    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            die('Error de validación CSRF');
        }
    }
}