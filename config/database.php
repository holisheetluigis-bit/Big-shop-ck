<?php
// config/database.php - Centralized PDO Database Connection

class Database {
    private static $host = '127.0.0.1';
    private static $db   = 'ck_shop';
    private static $user = 'root';
    private static $pass = '';
    private static $port = 3306;
    private static $charset = 'utf8mb4';
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db . ";charset=" . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                // Return a clean user-friendly error with guidance
                die("Database Connection Error: " . htmlspecialchars($e->getMessage()) . "<br><br>Please verify MySQL is running and that setup-db.php has been executed.");
            }
        }
        return self::$pdo;
    }
}
