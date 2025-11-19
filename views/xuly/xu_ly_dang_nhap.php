<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

$conn = Database::connect();

// Nhận dữ liệu từ form
$ten_dang_nhap = trim($_POST['ten_dang_nhap'] ?? '');
$mat_khau = trim($_POST['mat_khau'] ?? '');

if ($ten_dang_nhap === '' || $mat_khau === '') {
    header('Location: ../views/dang_nhap.php?error=empty');
    exit;
}

// Hàm kiểm tra đăng nhập trong 1 bảng
function kiemTraDangNhap(PDO $conn, string $bang, string $cotTen, string $cotMatKhau, string $cotVaiTro, string $tenDangNhap, string $matKhau) {
    $sql = "SELECT * FROM {$bang} WHERE {$cotTen} = :ten";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['ten' => $tenDangNhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($matKhau, $user[$cotMatKhau])) {
        return $user;
    }
    return null;
}

// =======================
// 1️⃣ Kiểm tra ADMIN
// =======================
$user = kiemTraDangNhap($conn, 'quantri', 'ten_dang_nhap', 'mat_khau', 'vai_tro', $ten_dang_nhap, $mat_khau);
if ($user) {
    $_SESSION['user'] = $user;
    $_SESSION['vai_tro'] = 'admin';
    header('Location: ../quantri/trang_chu.php');
    exit;
}

// =======================
// 2️⃣ Kiểm tra GIẢNG VIÊN
// =======================
$user = kiemTraDangNhap($conn, 'giangvien', 'ten_dang_nhap', 'mat_khau', 'vai_tro', $ten_dang_nhap, $mat_khau);
if ($user) {
    $_SESSION['user'] = $user;
    $_SESSION['vai_tro'] = 'giangvien';
    header('Location: ../giangvien/trang_chu.php');
    exit;
}

// =======================
// 3️⃣ Kiểm tra TÀI KHOẢN LỚP
// =======================
$user = kiemTraDangNhap($conn, 'taikhoan_lop', 'ten_dang_nhap', 'mat_khau', 'vai_tro', $ten_dang_nhap, $mat_khau);
if ($user) {
    $_SESSION['user'] = $user;
    $_SESSION['vai_tro'] = 'lop';
    header('Location: ../lop/trang_chu.php');
    exit;
}

// =======================
// 4️⃣ Kiểm tra SINH VIÊN
// =======================
// Lấy danh sách bảng sinh viên từ bảng `lop` để duyệt
$bangLop = $conn->query("SELECT ma_lop FROM lop")->fetchAll(PDO::FETCH_COLUMN);

foreach ($bangLop as $maLop) {
    $tenBangSV = 'sinhvien_' . $maLop;
    $sqlCheck = "SHOW TABLES LIKE :tenbang";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->execute(['tenbang' => $tenBangSV]);
    if ($stmt->rowCount() === 0) continue; // Nếu bảng không tồn tại

    $user = kiemTraDangNhap($conn, $tenBangSV, 'ma_sinh_vien', 'mat_khau', 'vai_tro', $ten_dang_nhap, $mat_khau);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['vai_tro'] = 'sinhvien';
        $_SESSION['lop'] = $maLop;
        header('Location: ../sinhvien/trang_chu.php');
        exit;
    }
}

// =======================
// ❌ Không tìm thấy tài khoản
// =======================
header('Location: ../views/dang_nhap.php?error=invalid');
exit;
