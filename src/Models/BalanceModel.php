<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/ExpenseModel.php';
require_once __DIR__ . '/IncomeModel.php';
require_once __DIR__ . '/GoalModel.php';

class Balance {
    private $db;
    private $expenseModel;
    private $incomeModel;
    private $goalModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->expenseModel = new ExpenseModel();
        $this->incomeModel = new IncomeModel();
        $this->goalModel = new GoalModel();
    }

    // Метод для получения общего баланса
    public function getBalance($userId) {
        $totalIncome = $this->getTotalIncome($userId);
        $totalExpense = $this->getTotalExpense($userId);
        return $totalIncome - $totalExpense;
    }

    // Метод для получения общего дохода
    public function getTotalIncome($userId) {
        return array_sum(array_column($this->incomeModel->getIncomes($userId), 'amount'));
    }

    // Метод для получения общего расхода
    public function getTotalExpense($userId) {
        return array_sum(array_column($this->expenseModel->getExpenses($userId), 'amount'));
    }
}
