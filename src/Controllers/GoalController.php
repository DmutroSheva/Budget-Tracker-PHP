<?php
require_once __DIR__ . '/../Models/GoalModel.php';

class GoalController {
    private $goalModel;

    public function __construct() {
        $this->goalModel = new GoalModel();
    }

    public function getGoals() {
        return $this->goalModel->getGoals();
    }

    public function addGoal($category, $title, $amount) {
        $userId = $_SESSION['user_id']; // получаем user_id из сессии
        return $this->goalModel->addGoal($userId, $category, $title, $amount);
    }
    

    public function deleteGoal($goalId) {
        return $this->goalModel->deleteGoal($goalId);
    }

    public function getTotalAllocatedAmount() {
        return $this->goalModel->getTotalAllocatedAmount();
    }

    public function updateGoalProgress($goalId, $allocationAmount) {
        return $this->goalModel->updateGoalProgress($goalId, $allocationAmount);
    }
}
