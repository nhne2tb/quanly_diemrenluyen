<?php
// ===============================================
// config/db.php — Kết nối cơ sở dữ liệu (PDO)
// ===============================================

class Database {
    private static $host = 'localhost';
    private static $db_name = 'quanly_diemrenluyen';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function connect() {
        if (self::$conn !== null) {
            return self::$conn;
        }

        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4";
            self::$conn = new PDO($dsn, self::$username, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$conn;
        } catch (PDOException $e) {
            die("❌ Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }
}
?>
