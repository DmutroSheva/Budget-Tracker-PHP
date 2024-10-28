<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Баланс и цели</title>
    <style>
        body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
}

header {
  background-color: #35424a;
  color: #ffffff;
  padding: 20px 0;
  text-align: center;
}

nav a {
  color: #ffffff;
  margin: 0 15px;
  text-decoration: none;
}

</style>
</head>
<header style="background-color: #6610f2; color: #fff; padding: 10px; text-align: center;">
        <a href="/../public/dashboard.php" style="text-decoration: none; color: #fff;"> <h1>Budget Tracker</h1></a>
        <nav>
            <?php 
            if (isset($_SESSION['user_id'])): 
            ?>
                <a href="/../public/IncomeTracker.php" style="width: 70px; color: #fff; text-decoration: none;">Доходы</a>
                <a href="/../public/ExpenseController.php" style="width: 70px; color: #fff; text-decoration: none;">Расходы</a>
                <a href="/../public/Goals.php" style="width: 70px; color: #fff; text-decoration: none;">Цели</a>
                <a href="/../public/Balance.php" style="width: 70px; color: #fff; text-decoration: none;" >Баланс</a>
                <a href="/../public/logout.php" style="width: 70px; color: #fff; text-decoration: none;" >Выход</a>
                <?php else: ?>
                <a href="/../public/login.php" class="mr-3 align-self-center" style="width: 70px; color: #fff; text-decoration: none;">Вход</a>

                <a href="/../public/register.php" class="mr-3 align-self-center" style="width: 70px; color: #fff; text-decoration: none;">Регистрация</a>

                <?php endif; ?>
        </nav>
    </header>

