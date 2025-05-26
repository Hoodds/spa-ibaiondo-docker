<?php
// Configuración de la base de datos
$config = [
    'host' => getenv('DB_HOST') ?: 'db', // 'db' es el nombre del servicio en docker-compose
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'spa_docker',
    'username' => getenv('DB_USERNAME') ?: 'spauser',
    'password' => getenv('DB_PASSWORD') ?: 'spapassword',
    'charset' => 'utf8mb4'
];

class Database {
    private static $instance = null;
    private $conn;
    private $config;

    private function __construct() {
        global $config;
        $this->config = $config;
        
        try {
            $this->conn = new PDO(
                "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}",
                $this->config['username'],
                $this->config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Devolver la configuración
return $config;