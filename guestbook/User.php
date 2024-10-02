<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getOrCreateUser($cookieHash) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE cookie_hash = ?");
        $stmt->execute([$cookieHash]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $stmt = $this->pdo->prepare("INSERT INTO users (cookie_hash) VALUES (?) RETURNING id");
            $stmt->execute([$cookieHash]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $user['id'];
    }
}
?>
