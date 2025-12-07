<?php
// config/config.php
// ===============================================
// config/config.php — Cấu hình chung hệ thống QLDRL DThU
// ===============================================

// ===== TỰ ĐỘNG XÁC ĐỊNH BASE_URL =====
// ===== XÁC ĐỊNH BASE_URL CHUẨN =====
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $projectRoot = '/quanly_diemrenluyen/public/'; // ⚠️ đúng tên thư mục gốc trong htdocs
    define('BASE_URL', $protocol . $host . $projectRoot);
}


// ===== MÚI GIỜ =====
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ===== THÔNG TIN WEBSITE =====
$SITE_INFO = [
    'title'       => 'Hệ thống Quản lý Điểm rèn luyện - DThU',
    'author'      => 'Sinh viên Khoa Sư phạm Toán - Tin',
    'description' => 'Website quản lý điểm rèn luyện sinh viên Trường Đại học Đồng Tháp.',
    'version'     => '1.0.0'
];

// ===== THÔNG TIN TRƯỜNG =====
$SCHOOL_INFO = [
    'ten_truong' => 'Trường Đại học Đồng Tháp',
    'ma_truong'  => 'SPD',
    'website'    => 'https://dthu.edu.vn',
    'logo'       => BASE_URL . 'assets/images/logo/logoweb.png',
    'mau1'       => '#0056B3', // Xanh DThU
    'mau2'       => '#D62B28', // Đỏ DThU
    'mau3'       => '#F5F9FF', // Nền sáng nhẹ
];

// ===== CƠ SỞ DỮ LIỆU =====
$DB_CONFIG = [
    'host'     => 'localhost',
    'dbname'   => 'quanly_diemrenluyen',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4'
];


?>
