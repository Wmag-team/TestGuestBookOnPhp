<?php
global $pdo;
require_once 'db_config.php';
require_once 'User.php';
require_once 'Theme.php';

$user = new User($pdo);
$theme = new Theme($pdo);

// Cookie-based user identification
if (!isset($_COOKIE['user_id'])) {
    $cookieHash = md5(uniqid(rand(), true));
    setcookie('user_id', $cookieHash, time() + (86400 * 30), "/"); // 30 days expiration
} else {
    $cookieHash = $_COOKIE['user_id'];
}

$userId = $user->getOrCreateUser($cookieHash);

$themes = $theme->getAllThemes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Гостевая книга</h1>
    <form action="topic.php" method="post">
        <input type="text" name="new_theme" placeholder="Введите тему для обсуждения" required>
        <button type="submit">Добавить тему</button>
    </form>
    <ul>
        <?php foreach ($themes as $t): ?>
            <li><a href="topic.php?id=<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
