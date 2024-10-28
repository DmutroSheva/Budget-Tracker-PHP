<?php
session_start();
require_once __DIR__ . '/../src/Controllers/AuthController.php';

$auth = new AuthController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];

    if ($password !== $confirmPassword) {
        $error = "Пароли не совпадают.";
    } else {
        try {
            // Вызов метода регистрации
            $auth->register($username, $email, $password, $lastName, $address);
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $error = "Ошибка регистрации: " . $e->getMessage();
        }
    }
}


include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" class="input-field" required />
            </div>

            <div class="form-group">
                <label for="last_name">Фамилия</label>
                <input type="text" id="last_name" name="last_name" class="input-field" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="input-field" required />
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="input-field" required />
            </div>

            <div class="form-group">
                <label for="confirm_password">Подтверждение пароля</label>
                <input type="password" id="confirm_password" name="confirm_password" class="input-field" required />
            </div>

            <div class="form-group">
                <label for="address">Адрес</label>
                <input type="text" id="address" name="address" class="input-field" required />
            </div>

            <?php if (!empty($error)) : ?>
                <div class="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-button">Зарегистрироваться</button>
        </form>

        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>
