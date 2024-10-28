<?php
session_start();
require_once __DIR__ . '/../src/Controllers/BalanceController.php';
require_once __DIR__ . '/../src/Controllers/GoalController.php';

$balanceController = new BalanceController();
$goalController = new GoalController();

$balance = $balanceController->getBalance();
$totalIncome = $balanceController->getTotalIncome();
$totalExpense = $balanceController->getTotalExpense();
$goals = $goalController->getGoals();

function calculateGoalProgress($goal) {
    if ($goal['amount'] > 0) {
        return round(($goal['allocated_amount'] / $goal['amount']) * 100, 2);
    }
    return 0;
}

include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Баланс и цели</title>
    <!-- <link rel="stylesheet" type="text/css" href="/public/css/styles.css"> -->

    <style>

.progress-bar {
  background-color: #e0e0e0; /* Фон прогресс-бара */
  border-radius: 5px; /* Закругленные углы */
  height: 10px; /* Высота прогресс-бара */
  margin-top: 10px; /* Отступ сверху */
  overflow: hidden; /* Скрывает части прогресс-бара, выходящие за пределы */
  position: relative; /* Позволяет позиционировать элементы внутри */
}

/* Стиль для самого прогресса */
.progress {
  background: linear-gradient(
    90deg,
    #4caf50,
    #81c784
  ); /* Градиент от зеленого к светло-зеленому */
  height: 100%; /* Высота прогресса соответствует высоте контейнера */
  transition: width 0.3s ease; /* Плавный переход при изменении ширины */
}

/* Для анимации при заполнении прогресс-бара */
.progress-bar:before {
  content: attr(data-progress) '%'; /* Показывает процент */
  position: absolute; /* Позволяет расположить текст поверх */
  left: 50%; /* Центрирует текст по горизонтали */
  top: 50%; /* Центрирует текст по вертикали */
  transform: translate(-50%, -50%); /* Выравнивание по центру */
  color: white; /* Цвет текста */
  font-weight: bold; /* Жирный шрифт */
}

.goal-list {
  list-style-type: none; /* Убираем маркеры списка */
  padding: 0; /* Убираем отступы */
  margin: 20px 0; /* Добавляем отступы сверху и снизу */
}

.goal-item {
  background-color: #f9f9f9; /* Светлый фон для элементов списка */
  border: 1px solid #ddd; /* Легкая рамка */
  border-radius: 5px; /* Закругленные углы */
  padding: 15px; /* Внутренние отступы */
  margin-bottom: 10px; /* Отступ между элементами списка */
  transition: background-color 0.3s; /* Плавный переход фона */
}

.goal-item:hover {
  background-color: #f1f1f1; /* Цвет фона при наведении */
}

.goal-item p {
  margin: 0; /* Убираем отступы */
  font-size: 16px; /* Размер шрифта */
}
</style>
</head>
<body>
    <div class="container">
        <h2><strong>Баланс:</strong> <?= htmlspecialchars($balance) ?> UA</h2>
        <p><strong>Всего заработано:</strong> <?= htmlspecialchars($totalIncome) ?> UA</p>
        <p><strong>Всего потрачено:</strong> <?= htmlspecialchars($totalExpense) ?> UA</p>

        <h3>Прогресс по целям:</h3>
        <ul class="goal-list">
            <?php foreach ($goals as $goal): ?>
                <li class="goal-item">
                    <p><strong><?= htmlspecialchars($goal['title']) ?>:</strong> <?= calculateGoalProgress($goal) ?>% выполнено
                    <strong>Вы отложили:</strong> <?= htmlspecialchars($goal['allocated_amount']) ?> из <?= htmlspecialchars($goal['amount']) ?> UA
                    </p>
                    <div class="progress-bar" data-progress="<?= calculateGoalProgress($goal) ?>">
                        <div class="progress" style="width: <?= calculateGoalProgress($goal) ?>%;"></div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</body>
</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>
