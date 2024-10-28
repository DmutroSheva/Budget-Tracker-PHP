<?php
require_once __DIR__ . '/../../config/database.php';

class IncomeModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addIncome($userId, $category, $amount, $goalId = null, $allocationAmount = null) {
        $sql = "INSERT INTO incomes (user_id, category, amount, goal_id, allocation_amount) VALUES (:userId, :category, :amount, :goalId, :allocationAmount)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':goalId', $goalId);
        $stmt->bindParam(':allocationAmount', $allocationAmount);
        $stmt->execute();
    }

    public function deleteIncome($incomeId) {
        $sql = "DELETE FROM incomes WHERE id = :incomeId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':incomeId', $incomeId);
        return $stmt->execute();
    }

    public function getIncomes($userId) {
        $sql = "SELECT incomes.id, incomes.category, incomes.amount, incomes.goal_id, incomes.allocation_amount, incomes.created_at, goals.title as goal_title 
                FROM incomes 
                LEFT JOIN goals ON incomes.goal_id = goals.id
                WHERE incomes.user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
