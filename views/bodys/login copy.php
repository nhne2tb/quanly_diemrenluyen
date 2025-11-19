<?php
// ===============================================
// views/bodys/login.php — Xử lý đăng nhập hệ thống QLDRL (chuẩn hóa)
// ===============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/config.php';

$conn = Database::connect();

// 1️⃣ Nhận dữ liệu từ form
$username = trim($_POST['ten_dang_nhap'] ?? '');
$password = trim($_POST['mat_khau'] ?? '');

if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
    header('Location: ' . BASE_URL . 'bodys_login');
    exit;
}

// 2️⃣ ADMIN
$stmt = $conn->prepare("SELECT * FROM tb_admin WHERE ma_ad = ? OR email = ?");
$stmt->execute([$username, $username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    $matkhau_db = $admin['mat_khau'];
    // So sánh: chuỗi thường → sha256 → password_hash
    if (
        $password === $matkhau_db ||
        hash('sha256', $password) === $matkhau_db ||
        password_verify($password, $matkhau_db)
    ) {
        $_SESSION['user'] = [
            'type'  => 'bodys_admin',
            'id'    => $admin['ma_ad'],
            'name'  => $admin['hoten'],
            'email' => $admin['email']
        ];
        header('Location: ' . BASE_URL . 'views/bodys_admin/bodys_admin.php');
        exit;
    }
}

// 3️⃣ GIẢNG VIÊN
$stmt = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ? OR email = ?");
$stmt->execute([$username, $username]);
$gv = $stmt->fetch(PDO::FETCH_ASSOC);

if ($gv) {
    $matkhau_db = $gv['mat_khau'];
    // Kiểm tra theo 3 cách: mật khẩu thường → sha256 → password_hash
    if (
        $password === $matkhau_db ||
        hash('sha256', $password) === $matkhau_db ||
        password_verify($password, $matkhau_db)
    ) {
        $_SESSION['user'] = [
            'type'  => 'bodys_giangvien',
            'id'    => $gv['ma_gv'],
            'name'  => $gv['ho_ten'],
            'email' => $gv['email'],
            'khoa'  => $gv['ma_khoa']
        ];
        header('Location: ' . BASE_URL . 'views/bodys_giangvien_lop/bodys_giangvien_lop.php');
        exit;
    }
}


// 4️⃣ TẬP THỂ LỚP
$stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_lop = ?");
$stmt->execute([$username]);
$lop = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lop && $lop['mat_khau_lop'] === $password) {
    $_SESSION['user'] = [
        'type' => 'bodys_tapthelop',
        'id'   => $lop['ma_lop'],
        'name' => $lop['ten_lop'],
        'khoa' => $lop['ma_khoa']
    ];
    header('Location: ' . BASE_URL . 'views/bodys_tapthelop/bodys_tapthelop.php');
    exit;
}

// 5️⃣ SINH VIÊN
$tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    if (strpos($table, 'sv_') === 0) {
        $stmt = $conn->prepare("SELECT * FROM `$table` WHERE mssv = ?");
        $stmt->execute([$username]);
        $sv = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sv) {
            $matkhau_db = $sv['mat_khau'];
            // Kiểm tra mật khẩu theo 3 kiểu
            if (
                $password === $matkhau_db ||
                hash('sha256', $password) === $matkhau_db ||
                password_verify($password, $matkhau_db)
            ) {
                $_SESSION['user'] = [
                    'type' => 'bodys_sinhvien',
                    'id'   => $sv['mssv'],
                    'name' => $sv['ho_ten'],
                    'lop'  => $table
                ];
                header('Location: ' . BASE_URL . 'views/bodys_sinhvien/bodys_sinhvien.php');
                exit;
            }
        }
    }
}


// ❌ Sai tài khoản hoặc mật khẩu
$_SESSION['login_error'] = 'Tài khoản hoặc mật khẩu không đúng.';
header('Location: ' . BASE_URL . 'bodys_login');
exit;
?>
