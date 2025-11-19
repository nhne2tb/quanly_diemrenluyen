<?php
//xuly/logout.php
// ================== ĐĂNG XUẤT NGƯỜI DÙNG ==================
session_start();

// Hủy toàn bộ session hiện tại
session_unset();
session_destroy();

// Gọi config để có BASE_URL
require_once __DIR__ . '/../../config/config.php';

// ✅ Điều hướng về trang login qua router
header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
exit;
