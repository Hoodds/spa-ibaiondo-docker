<?php
class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, fecha_registro FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email, fecha_registro FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, contrasena FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Si la contraseña almacenada parece un hash, usar password_verify
            if (strlen($user['contrasena']) > 30) {
                if (password_verify($password, $user['contrasena'])) {
                    unset($user['contrasena']);
                    return $user;
                }
            } else {
                // Comparación directa para contraseñas antiguas en texto plano
                if ($password === $user['contrasena']) {
                    unset($user['contrasena']);
                    return $user;
                }
            }
        }
        return false;
    }

    public function register($nombre, $email, $password) {
        // Hashear la contraseña antes de guardarla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, contrasena, fecha_registro) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$nombre, $email, $hashedPassword]);
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function update($id, $nombre, $email, $password = null) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?";
        $params = [$nombre, $email];

        if ($password) {
            $query .= ", contrasena = ?";
            $params[] = $password;
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function crear($data) {
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, fecha_registro) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nombre'], $data['email'], $data['password']]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}