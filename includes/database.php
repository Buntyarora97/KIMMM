<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            if (getenv('DATABASE_URL')) {
                $db_url = getenv('DATABASE_URL');
                $db_url = str_replace('postgres://', 'postgresql://', $db_url);
                $db_parts = parse_url($db_url);
                $dsn = "pgsql:host={$db_parts['host']};port=" . ($db_parts['port'] ?? 5432) . ";dbname=" . ltrim($db_parts['path'], '/');
                $username = $db_parts['user'];
                $password = $db_parts['pass'];
                $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
            } else {
                $dsn = "mysql:host=localhost;dbname=u446139296_livvra;charset=utf8mb4";
                $username = "u446139296_livvra";
                $password = "Bunty@000@";
                $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"];
            }
            $this->pdo = new PDO($dsn, $username, $password, $driver_options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) { self::$instance = new self(); }
        return self::$instance;
    }

    public function getConnection() { return $this->pdo; }
}

function db() { return Database::getInstance()->getConnection(); }
?>
