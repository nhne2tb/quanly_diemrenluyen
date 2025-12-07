<?php
// controllers/bodys_login.php
// ===============================================
// Controller cho trang chủ + Đăng nhập hệ thống QLDRL
// (phiên bản không dùng .htaccess)
// ===============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/bodys_login.php';

// Hiển thị thông báo lỗi đăng nhập (nếu có)
$login_error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);

// Gọi view
require_once __DIR__ . '/../views/bodys/bodys_login.php';
