<?php
// ===========================================
// views/bodys_giangvien_lop/capnhat_trang_thai_cvht.php
// ===========================================

// Xóa mọi output tránh lỗi JSON
ob_clean();
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// ======================= KIỂM TRA QUYỀN =======================
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    echo json_encode(['status' => 'error', 'msg' => 'Không có quyền truy cập']);
    exit;
}

// ======================= NHẬN POST =======================
$id     = $_POST['id']     ?? null;
$action = $_POST['action'] ?? null;
$table  = $_POST['table']  ?? null;
$ghichu = $_POST['ghichu'] ?? null;

if (!$id || !$action || !$table) {
    echo json_encode(['status' => 'error', 'msg' => 'Thiếu tham số']);
    exit;
}

// chống SQL Injection
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    echo json_encode(['status' => 'error', 'msg' => 'Tên bảng không hợp lệ']);
    exit;
}

$conn = Database::connect();

// ======================= KIỂM TRA PHIẾU =======================
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
$stmt->execute([$id]);
$phieu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$phieu) {
    echo json_encode(['status' => 'error', 'msg' => 'Phiếu không tồn tại']);
    exit;
}

$ma_cvht = $_SESSION['user']['id'];

// ==================================================================
// =========================== 1) DUYỆT ==============================
// ==================================================================
if ($action === 'duyet') {

    $sql = "
        UPDATE `$table`
        SET 
            trang_thai = 'Đã duyệt',
            ghi_chu_co_van = NULL,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = ?
        WHERE id = ?
    ";

    $conn->prepare($sql)->execute([$ma_cvht, $id]);

    echo json_encode([
        'status' => 'success',
        'row' => [
            'badge_cvht'     => '<span class="badge bg-success">Đã duyệt</span>',
            'ghi_chu_co_van' => '—',
            'button_duyet'   => '—',
            'button_trave'   => '<button class="btn btn-warning btn-compact btn-trave-cvht" data-id="'.$id.'" data-bs-toggle="modal" data-bs-target="#modalTraVe">Trả về</button>'
        ]
    ]);
    exit;
}

// ==================================================================
// ======================== 2) TRẢ VỀ ===============================
// ==================================================================
if ($action === 'trave') {

    if (!$ghichu || trim($ghichu) === '') {
        echo json_encode(['status'=>'error','msg'=>'Vui lòng nhập ghi chú']);
        exit;
    }

    $sql = "
        UPDATE `$table`
        SET 
            trang_thai = 'Trả về',
            ghi_chu_co_van = ?,
            ngay_capnhat = NOW(),
            nguoi_danh_gia = ?
        WHERE id = ?
    ";

    $conn->prepare($sql)->execute([$ghichu, $ma_cvht, $id]);

    echo json_encode([
        'status' => 'success',
        'row' => [
            'badge_cvht'     => '<span class="badge bg-warning text-dark">Trả về</span>',
            'ghi_chu_co_van' => htmlspecialchars($ghichu),
            'button_duyet'   => '<button class="btn btn-success btn-compact btn-duyet-cvht" data-id="'.$id.'">Duyệt</button>',
            'button_trave'   => '<button class="btn btn-warning btn-compact btn-trave-cvht" data-id="'.$id.'" data-bs-toggle="modal" data-bs-target="#modalTraVe">Trả về</button>'
        ]
    ]);
    exit;
}

// ==================================================================
// ====================== ACTION KHÔNG HỢP LỆ ========================
// ==================================================================
echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ']);
exit;
?>
