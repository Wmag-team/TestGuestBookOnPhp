<?php
class Theme {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllThemes() {
        $stmt = $this->pdo->query("SELECT * FROM themes ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTheme($name) {
        $stmt = $this->pdo->prepare("INSERT INTO themes (name) VALUES (?) ON CONFLICT (name) DO NOTHING RETURNING id");
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    public function getThemeById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM themes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
