<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'smarttech_db');
define('DB_USER', 'smarttech_user');
define('DB_PASS', 'SmartT3ch_2025!');

// Connexion PDO
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES    => false,
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    return $pdo;
}

// Logger une action CRUD (utilisé par le script de notification)
function logAction(string $action, string $table, int $recordId, string $details): void {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO action_logs (action, table_name, record_id, details, user_ip) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$action, $table, $recordId, $details, $_SERVER['REMOTE_ADDR'] ?? 'CLI']);
}
