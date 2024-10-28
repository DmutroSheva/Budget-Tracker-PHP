<?php
session_start();
require_once __DIR__ . '/../src/Controllers/AuthController.php';

$auth = new AuthController();
$error = '';

// Инициализация переменных для полей формы
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Сохраняем введённый email
    $password = $_POST['password']; // Сохраняем введённый пароль

    try {
        // Попытка выполнить логин
        if ($auth->login($email, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Неверный email или пароль.";
        }
    } catch (Exception $e) {
        // Обработка исключений и вывод сообщения об ошибке
        $error = 'Ошибка: ' . $e->getMessage();
    }
}

include __DIR__ . '/../templates/header.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
    <style>
        /* Стили для кнопки показа/скрытия пароля */
        .password-toggle {
            background-color: #6610f2;;
            cursor: pointer;
            border: none;
            /* background-color: transparent; */
            position: absolute;
            right: 10px;
            top: 38px;
            font-size: 16px;
        }

        /* Стили для всплывающего окна */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Вход</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="input-field"
                    value="<?= htmlspecialchars($email) ?>"
                    required
                />
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <div class="input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field"
                        value="<?= htmlspecialchars($password) ?>"
                        required
                    />
                    <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <button type="submit" class="submit-button">Войти</button>
        </form>

        <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </div>

    <!-- Всплывающее окно для ошибки -->
    <?php if (!empty($error)) : ?>
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Показать/скрыть пароль
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈'; // Изменить иконку на "скрыть"
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️'; // Изменить иконку на "показать"
            }
        }

        //для показа и закрытия всплывающего окна
        window.onload = function() {
            const modal = document.getElementById("errorModal");
            const closeModal = document.getElementById("closeModal");

            // Если есть ошибка, показываем модальное окно
            <?php if (!empty($error)) : ?>
                modal.style.display = "flex"; // Показываем окно
            <?php endif; ?>

            // Закрытие окна по клику на крестик
            closeModal.onclick = function() {
                modal.style.display = "none";
            };

            // Закрытие окна при клике за пределами модального окна
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        };
    </script>
</body>
</html>

<?php include __DIR__ . '/../templates/footer.php'; ?>
