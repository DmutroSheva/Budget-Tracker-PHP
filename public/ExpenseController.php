<?php
session_start();
require_once __DIR__ . '/../src/Controllers/ExpenseController.php';

$expenseController = new ExpenseController();
$categories = ['Еда', 'Транспорт', 'Развлечения', 'Здоровье', 'Прочее'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка действия: добавление или удаление
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete') {
            $expenseId = $_POST['id'];
            if ($expenseController->deleteExpense($expenseId)) {
            } else {
                $error = "Ошибка при удалении расхода.";
            }
        }
    } else {
        // Добавление расхода
        $category = $_POST['category'] ?? '';
        $amount = $_POST['amount'] ?? '';

        if (!empty($category) && !empty($amount)) {
            $expenseController->addExpense($category, $amount);
        } else {
            $error = "Все поля обязательны для заполнения.";
        }
    }
}

$expenses = $expenseController->getExpenses();

include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расходы</title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
    <script>
        function confirmDelete(event, form) {
            event.preventDefault(); // Останавливаем отправку формы
            if (confirm("Вы уверены, что хотите удалить этот расход?")) {
                form.submit(); // Отправляем форму, если подтверждено
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Расходы</h2>
        <form method="POST" action="ExpenseController.php" class="expense-form">
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
                <label for="amount">Сумма</label>
                <input type="number" id="amount" name="amount" class="input-field" placeholder="Сумма" required />
            </div>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <button type="submit" class="submit-button">Добавить Расход</button>
        </form>

        <ul class="expense-list">
            <?php foreach ($expenses as $expense): ?>
                <li class="expense-item">
                    <?= htmlspecialchars($expense['category']) ?>: <?= htmlspecialchars($expense['amount']) ?> UA
                    <p><small>Добавлено: <?= date('d.m.Y H:i', strtotime($expense['created_at'])) ?></small></p>
                    <form method="POST" action="ExpenseController.php" style="display:inline;" onsubmit="confirmDelete(event, this);">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($expense['id']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-button">Удалить</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>

