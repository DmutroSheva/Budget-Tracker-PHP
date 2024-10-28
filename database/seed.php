<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

$faker = Faker\Factory::create();
$db = Database::getInstance()->getConnection();

// Seed users
for ($i = 0; $i < 10; $i++) {
    $username = $faker->userName;
    $email = $faker->email;
    $password = password_hash('password', PASSWORD_BCRYPT);
    $lastName = $faker->lastName;
    $address = $faker->address;

    // Добавляем пользователей с всеми необходимыми полями
    $stmt = $db->prepare("INSERT INTO users (username, email, password, last_name, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $lastName, $address]);
}

// Seed transactions
for ($i = 0; $i < 50; $i++) {
    $amount = $faker->randomFloat(2, 10, 1000);
    $description = $faker->sentence;
    $type = $faker->randomElement(['income', 'expense']);
    $user_id = $faker->numberBetween(1, 10);
    $stmt = $db->prepare("INSERT INTO transactions (user_id, amount, description, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $amount, $description, $type]);
}

// Seed goals
for ($i = 0; $i < 10; $i++) {
    $title = $faker->sentence;
    $category = $faker->word;
    $amount = $faker->randomFloat(2, 100, 10000);
    $user_id = $faker->numberBetween(1, 10);
    $stmt = $db->prepare("INSERT INTO goals (user_id, title, category, amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $category, $amount]);
}



echo "Data seeded successfully!";
