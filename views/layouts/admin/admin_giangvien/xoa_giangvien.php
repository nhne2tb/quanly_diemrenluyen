<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

// ====== Lấy mã GV ======
$ma_gv = $_GET['ma'] ?? '';
if (!$ma_gv) {
    header('Location: gd_giangvien.php');
    exit;
}

// ====== Lấy thông tin GV ======
$stmt = $conn->prepare("SELECT ma_gv, ho_ten FROM tb_giangvien WHERE ma_gv = ?");
$stmt->execute([$ma_gv]);
$gv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gv) {
    header('Location: gd_giangvien.php?msg='.urlencode('Không tìm thấy giảng viên').'&type=danger');
    exit;
}

// ====== Xử lý xóa ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $delete = $conn->prepare("DELETE FROM tb_giangvien WHERE ma_gv = ?");
        $delete->execute([$ma_gv]);
        header('Location: gd_giangvien.php?msg='.urlencode('Đã xóa giảng viên thành công').'&type=success');
        exit;
    } catch (Exception $e) {
        header('Location: gd_giangvien.php?msg='.urlencode('Lỗi khi xóa: '.$e->getMessage()).'&type=danger');
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Xóa Giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:550px">
  <div class="card shadow-sm">
    <div class="card-header bg-danger text-white d-flex align-items-center">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <strong>Xác nhận xóa giảng viên</strong>
    </div>
    <div class="card-body">
      <p>Bạn có chắc chắn muốn xóa giảng viên sau?</p>
      <p class="fw-bold fs-5 mb-3">
        <?= htmlspecialchars($gv['ho_ten']) ?> 
        <span class="text-muted">(<?= htmlspecialchars($gv['ma_gv']) ?>)</span>
      </p>
      <div class="alert alert-warning">
        ⚠️ Khi xóa, dữ liệu sẽ <strong>không thể khôi phục</strong>.  
        Hành động này có thể ảnh hưởng đến các lớp hoặc dữ liệu liên quan.
      </div>
      <form method="post" class="d-flex justify-content-end gap-2 mt-4">
        <a href="gd_giangvien.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Hủy
        </a>
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-trash"></i> Xóa
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
