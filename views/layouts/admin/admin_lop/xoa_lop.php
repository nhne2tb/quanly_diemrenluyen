<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$ma = $_GET['ma'] ?? '';
$stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_lop=?");
$stmt->execute([$ma]);
$lop = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$lop) die("Không tìm thấy lớp!");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1️⃣ Lấy thông tin lớp để biết mã khoa
        $ma_khoa = strtolower($lop['ma_khoa']);
        $ma_lop_lower = strtolower($lop['ma_lop']);

        // 2️⃣ Xóa bảng sinh viên tương ứng
        $table_sv = "sv_" . $ma_khoa . "_" . $ma_lop_lower;
        $conn->exec("DROP TABLE IF EXISTS `$table_sv`;");

        // 3️⃣ Xóa bảng phiếu rèn luyện tương ứng (đồng bộ với them_lop.php)
        $table_drl = "phieu_ren_luyen_" . $ma_khoa . "_" . $ma_lop_lower;
        $conn->exec("DROP TABLE IF EXISTS `$table_drl`;");

        // 4️⃣ Cuối cùng xóa lớp trong tb_lop
        $stmt = $conn->prepare("DELETE FROM tb_lop WHERE ma_lop=?");
        $stmt->execute([$lop['ma_lop']]);

        header('Location: index.php?route=gd_lop&msg=' . urlencode('Đã xóa lớp và dữ liệu liên quan thành công!') . '&type=success');
        exit;
    } catch (PDOException $e) {
        die("Lỗi khi xóa: " . $e->getMessage());
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Xóa Lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:500px">
  <div class="card shadow-sm">
    <div class="card-header bg-danger text-white">
      <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Xác nhận xóa lớp</h5>
    </div>
    <div class="card-body">
      <p>Bạn có chắc chắn muốn xóa lớp:</p>
      <p class="fw-bold"><?= htmlspecialchars($lop['ten_lop']) ?> (<?= htmlspecialchars($lop['ma_lop']) ?>)</p>
      <div class="alert alert-warning small">
        ⚠ Khi xóa lớp này, toàn bộ dữ liệu liên quan (bảng sinh viên & phiếu rèn luyện) sẽ bị xóa vĩnh viễn và không thể khôi phục!
      </div>
      <form method="post" class="d-flex justify-content-end gap-2">
        <a href="index.php?route=gd_lop" class="btn btn-secondary">Hủy</a>
        <button type="submit" class="btn btn-danger">Xóa</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
