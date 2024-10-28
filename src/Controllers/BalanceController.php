<?php
require_once __DIR__ . '/../Models/BalanceModel.php';

class BalanceController {
    private $balance;

    public function __construct() {
        $this->balance = new Balance();
    }

    // Метод для получения общего баланса
    public function getBalance() {
        $userId = $_SESSION['user_id']; // Получаем ID пользователя из сессии
        return $this->balance->getBalance($userId);
    }

    // Метод для получения общего дохода
    public function getTotalIncome() {
        $userId = $_SESSION['user_id'];
        return $this->balance->getTotalIncome($userId);
    }

    // Метод для получения общего расхода
    public function getTotalExpense() {
        $userId = $_SESSION['user_id'];
        return $this->balance->getTotalExpense($userId);
    }
}
