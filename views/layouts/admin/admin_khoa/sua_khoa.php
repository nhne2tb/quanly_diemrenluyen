<?php
// views/layouts/admin/admin_khoa/sua_khoa.php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$ma = $_GET['ma'] ?? '';
$stmt = $conn->prepare("SELECT * FROM tb_khoa WHERE ma_khoa=?");
$stmt->execute([$ma]);
$khoa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$khoa) {
    die("<div style='padding:20px;font-family:sans-serif'>Không tìm thấy khoa!</div>");
}

// ============= XỬ LÝ SỬA =============
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("UPDATE tb_khoa SET ten_khoa=?,truong_khoa=?,pho_khoa=?,email=?,sdt=?,dia_chi=?,website=? WHERE ma_khoa=?");
        $stmt->execute([
            trim($_POST['ten_khoa']),
            trim($_POST['truong_khoa']),
            trim($_POST['pho_khoa']),
            trim($_POST['email']),
            trim($_POST['sdt']),
            trim($_POST['dia_chi']),
            trim($_POST['website']),
            $ma
        ]);
header('Location: ' . BASE_URL . 'index.php?route=gd_khoa&msg=' . urlencode('Thêm khoa thành công') . '&type=success');
exit;

    } catch (PDOException $e) {
        $err = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sửa Khoa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
label.required::after {
  content: " *";
  color: red;
}
</style>
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:800px">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Sửa thông tin Khoa</h5>
    </div>
    <form method="post" class="card-body">
      <div class="row g-3">
        <!-- Mã khoa -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Mã khoa</label>
          <input name="ma_khoa" class="form-control bg-light" 
                 value="<?= htmlspecialchars($khoa['ma_khoa']) ?>" readonly>
        </div>

        <!-- Tên khoa (bắt buộc) -->
        <div class="col-md-8">
          <label class="form-label fw-semibold required">Tên khoa</label>
          <input name="ten_khoa" class="form-control" required 
                 placeholder="VD: Khoa Công nghệ Thông tin"
                 value="<?= htmlspecialchars($khoa['ten_khoa']) ?>">
        </div>

        <!-- Trưởng khoa -->
        <div class="col-md-6">
          <label class="form-label">Trưởng khoa</label>
          <input name="truong_khoa" class="form-control" 
                 placeholder="VD: TS. Nguyễn Văn A"
                 value="<?= htmlspecialchars($khoa['truong_khoa']) ?>">
        </div>

        <!-- Phó khoa -->
        <div class="col-md-6">
          <label class="form-label">Phó khoa</label>
          <input name="pho_khoa" class="form-control" 
                 placeholder="VD: ThS. Trần Thị B"
                 value="<?= htmlspecialchars($khoa['pho_khoa']) ?>">
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" 
                 placeholder="VD: khoa.cntt@dthu.edu.vn"
                 value="<?= htmlspecialchars($khoa['email']) ?>">
        </div>

        <!-- SĐT -->
        <div class="col-md-6">
          <label class="form-label">SĐT</label>
          <input name="sdt" class="form-control" 
                 placeholder="VD: 02773xxxxxx"
                 value="<?= htmlspecialchars($khoa['sdt']) ?>">
        </div>

        <!-- Địa chỉ -->
        <div class="col-12">
          <label class="form-label">Địa chỉ</label>
          <textarea name="dia_chi" class="form-control" rows="2" 
                    placeholder="VD: Số 783 Phạm Hữu Lầu, TP. Cao Lãnh"><?= htmlspecialchars($khoa['dia_chi']) ?></textarea>
        </div>

        <!-- Website -->
        <div class="col-12">
          <label class="form-label">Website</label>
          <input name="website" class="form-control" 
                 placeholder="VD: https://cntt.dthu.edu.vn"
                 value="<?= htmlspecialchars($khoa['website']) ?>">
        </div>
      </div>

      <!-- Nút -->
      <div class="mt-4 d-flex justify-content-end gap-2">
<a href="<?= BASE_URL ?>index.php?route=gd_khoa" class="btn btn-secondary">Hủy</a>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
