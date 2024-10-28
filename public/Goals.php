<?php
session_start();
require_once __DIR__ . '/../src/Controllers/GoalController.php';

$goalController = new GoalController();

$categories = ['Образование', 'Путешествия', 'Покупки', 'Инвестиции'];
$error = '';
$totalAllocatedAmount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category'], $_POST['goalTitle'], $_POST['goalAmount'])) {
        $category = $_POST['category'];
        $goalTitle = $_POST['goalTitle'];
        $goalAmount = $_POST['goalAmount'];

        if (!empty($category) && !empty($goalTitle) && !empty($goalAmount)) {
            $goalController->addGoal($category, $goalTitle, $goalAmount);
        } else {
            $error = "Все поля обязательны для заполнения.";
        }
    }

    if (isset($_POST['deleteGoalId'])) {
        $goalController->deleteGoal($_POST['deleteGoalId']);
    }
}

$goals = $goalController->getGoals();
$totalAllocatedAmount = $goalController->getTotalAllocatedAmount();

include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Финансовые цели</title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
    <script>
        function confirmDelete(goalTitle) {
            return confirm('Вы уверены, что хотите удалить цель "' + goalTitle + '"?');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Финансовые цели</h2>
        <p><strong>Отложено всего:</strong> <?= htmlspecialchars($totalAllocatedAmount) ?> UA</p>

        <form method="POST" action="Goals.php" class="goal-form">
            <div class="form-group">
                <label for="category">Выберите категорию</label>
                <select id="category" name="category" class="input-field" required>
                    <option value="" disabled selected>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="goalTitle">Название цели</label>
                <input type="text" id="goalTitle" name="goalTitle" class="input-field" placeholder="Название цели" required />
            </div>

            <div class="form-group">
                <label for="goalAmount">Сумма цели</label>
                <input type="number" id="goalAmount" name="goalAmount" class="input-field" placeholder="Сумма цели" required />
            </div>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <button type="submit" class="submit-button">Добавить Цель</button>
        </form>

        <ul class="goal-list">
            <?php foreach ($goals as $goal): ?>
                <li class="goal-item">
                    <div>
                        <strong><?= htmlspecialchars($goal['category']) ?></strong> (<?= htmlspecialchars($goal['title']) ?>): <?= htmlspecialchars($goal['amount']) ?> UA
                        <br />
                        <strong>Вы отложили:</strong> <?= htmlspecialchars($goal['allocated_amount']) ?> из <?= htmlspecialchars($goal['amount']) ?> UA
                    </div>
                    <form method="POST" action="Goals.php" onsubmit="return confirmDelete('<?= htmlspecialchars($goal['title']) ?>');">
                        <input type="hidden" name="deleteGoalId" value="<?= htmlspecialchars($goal['id']) ?>">
                        <button type="submit" class="delete-button">Удалить</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>

