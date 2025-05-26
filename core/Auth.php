<?php
class Auth {
    public static function login($user, $isAdmin = false) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['is_admin'] = $isAdmin;
        $_SESSION['logged_in'] = true;
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function check() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function user() {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'nombre' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email']
            ];
        }
        return null;
    }

    public static function id() {
        return self::check() ? $_SESSION['user_id'] : null;
    }

    public static function isAdmin() {
        return self::check() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public static function checkAdmin() {
        if (!self::check() || !self::isAdmin()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }

    public static function checkAuth() {
        if (!self::check()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
}