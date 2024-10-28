<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = parse_ini_file(__DIR__ . '/../config/db.ini', true)['db'];

        $host = $config['host'];
        $db = $config['dbname'];
        $user = $config['user'];
        $pass = $config['password'];
        $charset = $config['charset'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
