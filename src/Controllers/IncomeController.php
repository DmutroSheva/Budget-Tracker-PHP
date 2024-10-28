<?php
require_once __DIR__ . '/../Models/IncomeModel.php';
require_once __DIR__ . '/../Controllers/GoalController.php';

class IncomeController {
    private $incomeModel;
    private $goalController;

    public function __construct() {
        $this->incomeModel = new IncomeModel();
        $this->goalController = new GoalController();
    }

    public function addIncome($category, $amount, $goalId = null, $allocationAmount = null) {
        $userId = $_SESSION['user_id'];
        $this->incomeModel->addIncome($userId, $category, $amount, $goalId, $allocationAmount);
    
        if ($goalId && $allocationAmount) {
            $this->goalController->updateGoalProgress($goalId, $allocationAmount);
        }
    }
    

    public function deleteIncome($incomeId) {
        return $this->incomeModel->deleteIncome($incomeId);
    }

    public function getIncomes() {
        $userId = $_SESSION['user_id'];
        return $this->incomeModel->getIncomes($userId);
    }
}
