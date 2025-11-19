<?php
// Bật hiển thị lỗi (giúp phát hiện lỗi khi lập trình)
ini_set('display_errors', 1);               // Hiển thị lỗi trong quá trình thực thi
ini_set('display_startup_errors', 1);       // Hiển thị lỗi xảy ra khi khởi động PHP
error_reporting(E_ALL);                     // Báo tất cả các loại lỗi

// Nhúng file chứa class Database
require_once 'config/db.php';         // Đường dẫn đến file Database.php (điều chỉnh nếu file này không nằm cùng cấp)

// Gọi hàm connect từ class Database để kiểm tra kết nối
$conn = Database::connect();                // Gọi phương thức static để lấy kết nối PDO

// Nếu kết nối thành công, in thông báo ra màn hình
echo "✅ Kết nối CSDL thành công!";
