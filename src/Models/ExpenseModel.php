<?php
require_once __DIR__ . '/../../config/database.php';

class ExpenseModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Метод для добавления расхода
    public function add($userId, $category, $amount) {
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (user_id, category, amount) 
            VALUES (?, ?, ?)"
        );
        return $stmt->execute([$userId, $category, $amount]);
    }

    // Метод для получения всех расходов пользователя
    public function getExpenses($userId) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Метод для удаления расхода
    public function deleteExpense($expenseId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
        return $stmt->execute([$expenseId, $userId]);
    }
}
