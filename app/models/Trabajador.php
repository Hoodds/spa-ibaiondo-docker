<?php
class Trabajador {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol FROM trabajadores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email, rol FROM trabajadores ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByRol($rol) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol FROM trabajadores WHERE rol = ? ORDER BY nombre");
        $stmt->execute([$rol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, contrasena, rol FROM trabajadores WHERE email = ?");
        $stmt->execute([$email]);
        $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($trabajador) {
            // Si la contraseña almacenada parece un hash, usar password_verify
            if (strlen($trabajador['contrasena']) > 30) {
                if (password_verify($password, $trabajador['contrasena'])) {
                    unset($trabajador['contrasena']);
                    return $trabajador;
                }
            } else {
                // Comparación directa para contraseñas antiguas en texto plano
                if ($password === $trabajador['contrasena']) {
                    unset($trabajador['contrasena']);
                    return $trabajador;
                }
            }
        }
        return false;
    }

    public function update($id, $nombre, $email, $rol, $password = null) {
        $query = "UPDATE trabajadores SET nombre = ?, email = ?, rol = ?";
        $params = [$nombre, $email, $rol];

        if ($password) {
            $query .= ", contrasena = ?";
            $params[] = $password;
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM trabajadores WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function crear($data) {
        $sql = "INSERT INTO trabajadores (nombre, email, rol, contrasena) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nombre'], $data['email'], $data['rol'], $data['password']]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM trabajadores WHERE id = ?");
        return $stmt->execute([$id]);
    }
}