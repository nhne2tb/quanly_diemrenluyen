<?php
require_once __DIR__ . '/../../../../config/config.php';   // cần để lấy BASE_URL
require_once __DIR__ . '/../../../../config/db.php';

$conn = Database::connect();

// ====== LẤY MÃ KHOA ======
$ma = $_GET['ma'] ?? '';

if (!$ma) {
    header('Location: ' . BASE_URL . 'index.php?route=gd_khoa&msg=' . urlencode('Thiếu mã khoa') . '&type=danger');
    exit;
}

// ====== LẤY THÔNG TIN KHOA ======
$stmt = $conn->prepare("SELECT * FROM tb_khoa WHERE ma_khoa=?");
$stmt->execute([$ma]);
$khoa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$khoa) {
    header('Location: ' . BASE_URL . 'index.php?route=gd_khoa&msg=' . urlencode('Không tìm thấy khoa') . '&type=danger');
    exit;
}

// ====== XỬ LÝ XÓA ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $delete = $conn->prepare("DELETE FROM tb_khoa WHERE ma_khoa=?");
        $delete->execute([$ma]);

        header('Location: ' . BASE_URL . 'index.php?route=gd_khoa&msg=' . urlencode('Đã xóa khoa thành công') . '&type=success');
        exit;

    } catch (PDOException $e) {

        // Lỗi khóa ngoại (FK)
        if ($e->getCode() == 23000) {
            $msg = 'Không thể xóa khoa vì có giảng viên hoặc dữ liệu liên quan!';
        } else {
            $msg = 'Lỗi khi xóa: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'index.php?route=gd_khoa&msg=' . urlencode($msg) . '&type=danger');
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Xóa Khoa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:500px">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-danger text-white">
      <h5 class="mb-0">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Xác nhận xóa khoa
      </h5>
    </div>
    <div class="card-body">
      <div class="alert alert-warning d-flex align-items-start" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div>
          <strong>Bạn có chắc chắn muốn xóa?</strong><br>
          Khi xóa, dữ liệu sẽ <span class="fw-bold text-danger">không thể khôi phục</span>.
        </div>
      </div>

      <p class="mb-1">Khoa cần xóa:</p>
      <p class="fw-bold fs-5 text-danger mb-4">
        <?= htmlspecialchars($khoa['ten_khoa']) ?> (<?= htmlspecialchars($khoa['ma_khoa']) ?>)
      </p>

      <form method="post" class="d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>index.php?route=gd_khoa" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Hủy
        </a>
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-trash-fill me-1"></i> Xóa vĩnh viễn
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
