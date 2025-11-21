<?php
ob_clean(); // XÓA SẠCH MỌI OUTPUT
header('Content-Type: application/json; charset=utf-8');

// KHÔNG ĐƯỢC CÓ KHOẢNG TRẮNG PHÍA TRÊN DÒNG NÀY !!!

// ===========================================
// views/bodys_tapthelop/capnhat_trang_thai_lop.php
// ===========================================

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// ====== KIỂM TRA QUYỀN ======
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_tapthelop') {
    echo json_encode(['status' => 'error', 'msg' => 'Không có quyền truy cập']);
    exit;
}

// ====== NHẬN POST ======
$id     = $_POST['id']     ?? null;
$action = $_POST['action'] ?? null;
$table  = $_POST['table']  ?? null;
$ghichu = $_POST['ghichu'] ?? null;

if (!$id || !$action || !$table) {
    echo json_encode(['status' => 'error', 'msg' => 'Thiếu tham số']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    echo json_encode(['status' => 'error', 'msg' => 'Tên bảng không hợp lệ']);
    exit;
}

$conn = Database::connect();

// ===== KIỂM TRA PHIẾU =====
$check = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode(['status' => 'error', 'msg' => 'Phiếu không tồn tại']);
    exit;
}

// ================== CASE DUYỆT ==================
if ($action === 'duyet') {

    $sql = "
        UPDATE `$table`
        SET 
            trang_thai_lop = 'Đã duyệt',
            ghi_chu_lop = NULL,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = ?
        WHERE id = ?
    ";
    $conn->prepare($sql)->execute([$_SESSION['user']['id'], $id]);

    echo json_encode([
        'status' => 'success',
        'row' => [
            'badge_lop'     => '<span class="badge bg-success">Đã duyệt</span>',
            'ghi_chu_lop'   => '—',
            'button_duyet'  => '—',
            'button_trave'  => '<button class="btn btn-warning btn-compact btn-trave" data-id="'.$id.'" data-bs-toggle="modal" data-bs-target="#modalTraVe">Trả về</button>'
        ]
    ]);
    exit;
}

// ================== CASE TRẢ VỀ ==================
if ($action === 'trave') {

    if (!$ghichu || trim($ghichu) === '') {
        echo json_encode(['status'=>'error','msg'=>'Vui lòng nhập ghi chú']);
        exit;
    }

    $sql = "
        UPDATE `$table`
        SET 
            trang_thai_lop = 'Trả về',
            ghi_chu_lop = ?,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = ?
        WHERE id = ?
    ";
    $conn->prepare($sql)->execute([$ghichu, $_SESSION['user']['id'], $id]);

    echo json_encode([
        'status' => 'success',
        'row' => [
            'badge_lop'     => '<span class="badge bg-warning text-dark">Trả về</span>',
            'ghi_chu_lop'   => htmlspecialchars($ghichu),
            'button_duyet'  => '<button class="btn btn-success btn-compact btn-duyet" data-id="'.$id.'">Duyệt</button>',
            'button_trave'  => '<button class="btn btn-warning btn-compact btn-trave" data-id="'.$id.'" data-bs-toggle="modal" data-bs-target="#modalTraVe">Trả về</button>'
        ]
    ]);
    exit;
}

// ========== ACTION SAI ==========
echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ']);
exit;
