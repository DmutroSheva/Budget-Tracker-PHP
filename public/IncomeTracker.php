<?php
session_start();
require_once __DIR__ . '/../src/Controllers/IncomeController.php';
require_once __DIR__ . '/../src/Controllers/GoalController.php';

$incomeController = new IncomeController();
$goalController = new GoalController();

$incomeCategories = ['Зарплата', 'Фриланс', 'Инвестиции', 'Прочее'];
$goals = $goalController->getGoals(); // Получение списка целей для отображения в форме
$error = '';

// Функция для установки куки
function setCookieValue($name, $value, $expire) {
    setcookie($name, $value, time() + $expire, "/"); // Путь "/" доступен для всех страниц
}

// Функция для получения куки
function getCookieValue($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
}

// Обработка удаления дохода
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $incomeId = $_POST['id'];
    if ($incomeController->deleteIncome($incomeId)) {
         // Успешное удаление, обновляем куки
         $deletedIncomes = getCookieValue('deleted_incomes');
         $deletedIncomes = $deletedIncomes ? json_decode($deletedIncomes, true) : [];
         $deletedIncomes[] = $incomeId;
         setCookieValue('deleted_incomes', json_encode($deletedIncomes), 86400); // 86400 секунд = 1 сутки 
    } else {
        $error = "Ошибка при удалении дохода.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    // Проверяем данные формы
    $category = $_POST['category'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $goalId = $_POST['goal'] ?? null;
    $allocationAmount = $_POST['allocationAmount'] ?? null;

    if ($category && $amount) {
        try {
            $incomeController->addIncome($category, $amount, $goalId, $allocationAmount);
            // Успешное добавление, обновляем куки
            $addedIncomes = getCookieValue('added_incomes');
            $addedIncomes = $addedIncomes ? json_decode($addedIncomes, true) : [];
            $addedIncomes[] = [
                'category' => $category,
                'amount' => $amount,
                'goalId' => $goalId,
                'allocationAmount' => $allocationAmount,
                'timestamp' => time()
            ];
            setCookieValue('added_incomes', json_encode($addedIncomes), 86400); // 86400 секунд = 1 сутки
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Все поля обязательны для заполнения.";
    }
}

$incomes = $incomeController->getIncomes();

include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доходы</title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>

<body>
    <div class="container">
        <h2>Доходы</h2>
        <form method="POST" action="IncomeTracker.php" class="income-form">
            <div class="form-group">
                <label for="category">Выберите категорию</label>
                <select id="category" name="category" class="input-field" required>
                    <option value="" disabled selected>Выберите категорию</option>
                    <?php foreach ($incomeCategories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Сумма</label>
                <input type="number" id="amount" name="amount" class="input-field" placeholder="Сумма" required />
            </div>

            <h3>Отложить деньги на цели?</h3>
            <div class="form-group">
                <label for="goal">Выберите цель</label>
                <select id="goal" name="goal" class="input-field">
                    <option value="" disabled selected>Выберите цель</option>
                    <?php foreach ($goals as $goal): ?>
                        <option value="<?= htmlspecialchars($goal['id']) ?>"><?= htmlspecialchars($goal['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="allocationAmount">Сумма для откладывания</label>
                <input type="number" id="allocationAmount" name="allocationAmount" class="input-field" placeholder="Сумма для откладывания" />
            </div>

            <?php if (!empty($error)): ?>
                <div id="error-popup" class="popup error">
                    <span class="close-btn" onclick="closePopup()">&times;</span>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-button">Добавить Доход</button>
        </form>

        <ul class="income-list">
            <?php foreach ($incomes as $income): ?>
                <li class="income-item">
                    <p><?= htmlspecialchars($income['category']) ?>: <?= htmlspecialchars($income['amount']) ?> UA</p>
                    <p><small>Отложено на цель: <?= htmlspecialchars($income['goal_title']) ?> (<?= htmlspecialchars($income['allocation_amount']) ?> UA)</small></p>
                    <p><small>Добавлено: <?= date('d.m.Y H:i', strtotime($income['created_at'])) ?></small></p>
                    <form method="POST" action="IncomeTracker.php" style="display:inline;" onsubmit="return confirmDelete()">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($income['id']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-button">Удалить</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="overlay" id="overlay" style="display:none;"></div>

        <script>
            function confirmDelete() {
                return confirm("Вы уверены, что хотите удалить этот доход?");
            }
        </script>
    </div>
</body>

</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>


