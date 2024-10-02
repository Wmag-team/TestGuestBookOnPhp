<?php
class Message {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addMessage($userId, $themeId, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO messages (user_id, theme_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $themeId, $content]);
    }

    public function getMessagesByTheme($themeId) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.id as user_id
            FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.theme_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$themeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
