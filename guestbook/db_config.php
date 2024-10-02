<?php
// Настройки подключения
$host = 'guestbook_postgres'; // Хост
$port = '5432';      // Порт (по умолчанию 5432)
$user = 'user333';    // Имя пользователя
$password = 'password333'; // Пароль
$dbname = 'guest_book'; // Имя базы данных

try {
    // Создание строки DSN для PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    // Создание объекта PDO
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create tables if they don't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        cookie_hash VARCHAR(32) UNIQUE NOT NULL
    );

    CREATE TABLE IF NOT EXISTS themes (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) UNIQUE NOT NULL
    );

    CREATE TABLE IF NOT EXISTS messages (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id),
        theme_id INTEGER REFERENCES themes(id),
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
");
?>
