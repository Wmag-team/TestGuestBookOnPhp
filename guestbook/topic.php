<?php
require_once 'db_config.php';
require_once 'User.php';
require_once 'Theme.php';
require_once 'Message.php';

$user = new User($pdo);
$theme = new Theme($pdo);
$message = new Message($pdo);

// Cookie-based user identification
if (!isset($_COOKIE['user_id'])) {
    header('Location: index.php');
    exit();
}

$cookieHash = $_COOKIE['user_id'];
$userId = $user->getOrCreateUser($cookieHash);

// Handle new theme creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_theme'])) {
    $newThemeId = $theme->addTheme($_POST['new_theme']);
    if ($newThemeId) {
        header("Location: topic.php?id=$newThemeId");
        exit();
    }
}

// Handle theme display and message posting
if (isset($_GET['id'])) {
    $themeId = $_GET['id'];
    $currentTheme = $theme->getThemeById($themeId);

    if (!$currentTheme) {
        header('Location: index.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $message->addMessage($userId, $themeId, $_POST['message']);
    }

    $messages = $message->getMessagesByTheme($themeId);
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тема: <?= htmlspecialchars($currentTheme['name']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Тема: <?= htmlspecialchars($currentTheme['name']) ?></h1>
    <a href="index.php"> < Назад</a>
    <form action="" method="post">
        <textarea name="message" placeholder="Введите сообщение" required></textarea>
        <button type="submit">Отправить сообщение</button>
    </form>
    <h2>Сообщения:</h2>
    <ul>
        <?php foreach ($messages as $m): ?>
            <li>
                User #<?= $m['user_id'] ?>, 
                message time <?= date('d-m-Y H:i', strtotime($m['created_at'])) ?>, 
                <?= htmlspecialchars($m['content']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
