<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Services/ValidationService.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (!$this->db) {
            throw new Exception("Ошибка соединения с базой данных");
        }
    }

    public function register($username, $email, $password, $lastName, $address) {
        // Валидация email и пароля
        if (!ValidationService::validateEmail($email)) {
            throw new Exception("Неверный формат email.");
        }

        if (!ValidationService::validatePassword($password)) {
            throw new Exception("Пароль должен содержать не менее 8 символов.");
        }

        // Подготовка SQL-запроса
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, last_name, address) 
            VALUES (:username, :email, :password, :last_name, :address)
        ");
        
        // Выполнение запроса
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'last_name' => $lastName,
            'address' => $address
        ]);
    }

    public function login($email, $password) {
        // Валидация email
        if (!ValidationService::validateEmail($email)) {
            throw new Exception("Неверный формат email.");
        }

        // Подготовка и выполнение SQL-запроса
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка пароля
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
