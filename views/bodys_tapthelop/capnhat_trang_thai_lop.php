<?php
// ==========================================
// views/bodys_tapthelop/capnhat_trang_thai_lop.php
// ==========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// ====== KIỂM TRA QUYỀN ======
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_tapthelop') {
    header("Location: " . BASE_URL . "index.php?route=bodys_login");
    exit;
}

// ====== LẤY THAM SỐ ======
$id     = $_GET['id']     ?? null;
$action = $_GET['action'] ?? null;
$table  = $_GET['table']  ?? null;

// Nếu là "trả về" thì nhận thêm ghi chú POST
$ghi_chu = $_POST['ghi_chu_lop'] ?? null;

if (!$id || !$action || !$table) {
    die("Thiếu tham số yêu cầu.");
}

$conn = Database::connect();

// ====== KIỂM TRA TỒN TẠI PHIẾU ======
$sqlCheck = "SELECT * FROM `$table` WHERE id = :id";
$stmt = $conn->prepare($sqlCheck);
$stmt->execute([':id' => $id]);
$phieu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$phieu) {
    die("Phiếu không tồn tại.");
}

// ====== XỬ LÝ DUYỆT ======
if ($action === 'duyet') {

    $sqlUpdate = "
        UPDATE `$table`
        SET 
            trang_thai_lop = 'Đã duyệt',
            ghi_chu_lop = NULL,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = :nguoilop
        WHERE id = :id
    ";

    $stmt = $conn->prepare($sqlUpdate);
    $stmt->execute([
        ':id'        => $id,
        ':nguoilop'  => $_SESSION['user']['id'] ?? ''
    ]);

    header("Location: " . BASE_URL . "index.php?route=bodys_tapthelop");
    exit;
}

// ====== XỬ LÝ TRẢ VỀ ======
if ($action === 'trave') {

    if (!$ghi_chu || trim($ghi_chu) === '') {
        die("Thiếu ghi chú.");
    }

    $sqlUpdate = "
        UPDATE `$table`
        SET 
            trang_thai_lop = 'Trả về',
            ghi_chu_lop = :ghichu,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = :nguoilop
        WHERE id = :id
    ";

    $stmt = $conn->prepare($sqlUpdate);
    $stmt->execute([
        ':ghichu'    => $ghi_chu,
        ':nguoilop'  => $_SESSION['user']['id'] ?? '',
        ':id'        => $id
    ]);

    header("Location: " . BASE_URL . "index.php?route=bodys_tapthelop");
    exit;
}

// Nếu action không hợp lệ
die("Hành động không hợp lệ.");
