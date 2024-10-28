<?php
require_once __DIR__ . '/../../config/database.php';

class GoalModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getGoals() {
        // Исправляем название поля с allocationAmount на allocated_amount
        $sql = "SELECT id, category, title, amount, IFNULL(allocated_amount, 0) as allocated_amount FROM goals";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGoal($userId, $category, $title, $amount) {
        $sql = "INSERT INTO goals (user_id, category, title, amount) VALUES (:userId, :category, :title, :amount)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':amount', $amount);
        $stmt->execute();
    }

    public function deleteGoal($goalId) {
        $sql = "DELETE FROM goals WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $goalId);
        $stmt->execute();
    }

    public function getTotalAllocatedAmount() {
        $sql = "SELECT SUM(allocated_amount) as total_allocated FROM goals";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_allocated'];
    }

    public function updateGoalProgress($goalId, $allocationAmount) {
        $sql = "UPDATE goals SET allocated_amount = allocated_amount + :allocationAmount WHERE id = :goalId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':allocationAmount', $allocationAmount);
        $stmt->bindParam(':goalId', $goalId);
        $stmt->execute();
    }
    
}