<?php
class Helper {
    public static function url($path = '') {
        $base = APP_URL;

        if (strpos($base, 'index.php') === false) {
            $base .= '/index.php';
        }

        if (empty($path)) {
            return rtrim($base, '/');
        }

        return $base . '/' . ltrim($path, '/');
    }

    public static function asset($path = '') {
        $base = APP_URL;

        if (empty($path)) {
            return rtrim($base, '/');
        }

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