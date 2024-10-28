<?php
require_once __DIR__ . '/../Models/ExpenseModel.php';

class ExpenseController {
    private $expenseModel;

    public function __construct() {
        $this->expenseModel = new ExpenseModel();
    }

    // Метод для добавления расхода
    public function addExpense($category, $amount) {
        $userId = $_SESSION['user_id']; // Получаем ID пользователя из сессии
        return $this->expenseModel->add($userId, $category, $amount);
    }

    // Метод для получения всех расходов пользователя
    public function getExpenses() {
        $userId = $_SESSION['user_id'];
        return $this->expenseModel->getExpenses($userId);
    }

    // Метод для удаления расхода
    public function deleteExpense($expenseId) {
        $userId = $_SESSION['user_id'];
        return $this->expenseModel->deleteExpense($expenseId, $userId);
    }
}
