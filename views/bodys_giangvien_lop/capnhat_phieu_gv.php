<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','msg'=>'Invalid request']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();
if (($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    echo json_encode(['status'=>'error','msg'=>'Không có quyền cập nhật']);
    exit;
}

$id    = intval($_POST['id'] ?? 0);
$table = $_POST['table'] ?? '';

if (!$id || !$table) {
    echo json_encode(['status'=>'error','msg'=>'Thiếu tham số']);
    exit;
}

$conn = Database::connect();

$data = $_POST;
unset($data['id'], $data['table']);

$cols = [];
$vals = [];

$total = 0;

// QUY ĐỊNH CỘT ĐIỂM — chỉ cộng cột bắt đầu bằng "diem_"
foreach ($data as $k => $v) {

    // Là điểm => đưa vào tổng
    if (strpos($k, 'diem_') === 0) {
        $total += intval($v);
    }

    // Lưu tất cả vào DB
    $cols[] = "`$k`=?";
    $vals[] = $v;
}

// Thêm thông tin lưu lịch sử
$cols[] = "`ghi_chu_co_van`=?";
$vals[] = ($data['ghi_chu_gv'] ?? '') . 
          "\n[Cập nhật bởi GV: " . ($_SESSION['user']['id'] ?? '') . 
          " | " . date("d/m/Y H:i") . "]";

$cols[] = "`tong_diem`=?";
$vals[] = $total;

$vals[] = $id;

$sql = "UPDATE `$table` SET " . implode(",", $cols) . " WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->execute($vals);

echo json_encode(['status'=>'success','tong_diem'=>$total]);
exit;
