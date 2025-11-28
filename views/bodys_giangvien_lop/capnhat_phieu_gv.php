<?php
// views/bodys_giangvien_lop/capnhat_phieu_gv.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();

if (($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    echo json_encode(['status' => 'error', 'msg' => 'Không có quyền cập nhật']);
    exit;
}

$id    = intval($_POST['id'] ?? 0);
$table = $_POST['table'] ?? '';

if (!$id || !$table) {
    echo json_encode(['status' => 'error', 'msg' => 'Thiếu tham số']);
    exit;
}

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* 1. Lấy phiếu hiện tại */
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$old) {
    echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy phiếu']);
    exit;
}

/* 2. Lấy điểm mới từ form (hiện tại chỉ sửa I.3) */
$new_i3 = isset($_POST['diem_i3_vuot_kho'])
    ? intval($_POST['diem_i3_vuot_kho'])
    : intval($old['diem_i3_vuot_kho']);

$old_i3 = intval($old['diem_i3_vuot_kho']);

/* 3. Tính lại tổng điểm:
   tổng_mới = tổng_cũ - điểm_cũ_I3 + điểm_mới_I3  */
$old_total = intval($old['tong_diem']);
$new_total = $old_total - $old_i3 + $new_i3;

/* 4. Ghi chú cố vấn đã chỉnh gì */
$changes = [];
if ($new_i3 !== $old_i3) {
    $changes[] = "I.3: $old_i3 → $new_i3";
}

$ghi_chu_cu  = $old['ghi_chu_co_van'] ?? '';
$ghi_chu_moi = $ghi_chu_cu;

if (!empty($changes)) {
    $time   = date('d/m/Y H:i');
    $gv_id  = $_SESSION['user']['id'] ?? '';
    $line   = "$time - CVHT $gv_id chỉnh: " . implode(', ', $changes);

    if (trim($ghi_chu_moi) !== '') {
        $ghi_chu_moi .= "\n" . $line;
    } else {
        $ghi_chu_moi = $line;
    }
}

/* 5. Cập nhật DB */
$stmtUpd = $conn->prepare("
    UPDATE `$table`
    SET diem_i3_vuot_kho = ?,
        tong_diem        = ?,
        ghi_chu_co_van   = ?,
        nguoi_danh_gia   = ?,
        ngay_capnhat     = NOW()
    WHERE id = ?
");

$stmtUpd->execute([
    $new_i3,
    $new_total,
    $ghi_chu_moi,
    $_SESSION['user']['id'] ?? '',
    $id
]);

echo json_encode([
    'status'    => 'success',
    'tong_diem' => $new_total,
    'ghi_chu'   => $ghi_chu_moi
]);
