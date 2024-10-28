<?php
require_once __DIR__ . '/../models/User.php'; 

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register($username, $email, $password, $lastName, $address) {
        return $this->userModel->register($username, $email, $password, $lastName, $address);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->login($email, $password);
            if ($user) {
                // Проверка активности сессии
                if (session_status() === PHP_SESSION_NONE) {
                    session_start(); // Начинает новый сеанс, только если его не существует
                }
                $_SESSION['user_id'] = $user['id'];
                header('Location: IncomeTracker.php');
                exit();
            } else {
                echo "Invalid credentials";
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_start(); 
            session_destroy(); 
        }
        header('Location: login.php');
        exit(); 
    }
}
