<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

// Lấy danh sách giảng viên
$giangviens = $conn->query("SELECT ma_gv, ho_ten FROM tb_giangvien ORDER BY ho_ten ASC")->fetchAll(PDO::FETCH_ASSOC);

// ============= XỬ LÝ THÊM =============
$msg = null; $msgType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO tb_khoa(ma_khoa,ten_khoa,truong_khoa,pho_khoa,email,sdt,dia_chi,website,ngay_tao)
                                VALUES(?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            strtoupper(trim($_POST['ma_khoa'])),
            trim($_POST['ten_khoa']),
            trim($_POST['truong_khoa']),
            trim($_POST['pho_khoa']),
            trim($_POST['email']),
            trim($_POST['sdt']),
            trim($_POST['dia_chi']),
            trim($_POST['website'])
        ]);
        header('Location: gd_khoa.php?msg='.urlencode('Thêm khoa thành công').'&type=success');
        exit;
    } catch (PDOException $e) {
        $msg = "Lỗi: ".$e->getMessage();
        $msgType = 'danger';
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thêm Khoa</title>
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
      <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Thêm Khoa Mới</h5>
    </div>
    <form method="post" class="card-body">
      <div class="row g-3">
        <!-- Mã khoa -->
        <div class="col-md-4">
          <label class="form-label fw-semibold required">Mã khoa</label>
          <input name="ma_khoa" class="form-control" required 
                 placeholder="VD: CNTT" 
                 oninput="this.value=this.value.toUpperCase()">
          <div class="form-text">Nhập viết tắt, không dấu. VD: <b>CNTT</b></div>
        </div>

        <!-- Tên khoa -->
        <div class="col-md-8">
          <label class="form-label fw-semibold required">Tên khoa</label>
          <input name="ten_khoa" class="form-control" required placeholder="VD: Khoa Công nghệ Thông tin">
        </div>

        <!-- Trưởng khoa -->
        <div class="col-md-6">
          <label class="form-label">Trưởng khoa</label>
          <select name="truong_khoa" class="form-select">
            <option value="">-- Chọn giảng viên --</option>
            <?php foreach($giangviens as $gv): ?>
              <option value="<?= htmlspecialchars($gv['ho_ten']) ?>">
                <?= htmlspecialchars($gv['ho_ten']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Phó khoa -->
        <div class="col-md-6">
          <label class="form-label">Phó khoa</label>
          <select name="pho_khoa" class="form-select">
            <option value="">-- Chọn giảng viên --</option>
            <?php foreach($giangviens as $gv): ?>
              <option value="<?= htmlspecialchars($gv['ho_ten']) ?>">
                <?= htmlspecialchars($gv['ho_ten']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="VD: khoa.cntt@dthu.edu.vn">
        </div>

        <!-- SĐT -->
        <div class="col-md-6">
          <label class="form-label">SĐT</label>
          <input name="sdt" class="form-control" placeholder="VD: 02773xxxxxx">
        </div>

        <!-- Địa chỉ -->
        <div class="col-12">
          <label class="form-label">Địa chỉ</label>
          <textarea name="dia_chi" class="form-control" rows="2" placeholder="VD: Phòng 303, Tầng 3, H3"></textarea>
        </div>

        <!-- Website -->
        <div class="col-12">
          <label class="form-label">Website</label>
          <input name="website" class="form-control" placeholder="VD: https://cntt.dthu.edu.vn">
        </div>
      </div>

      <!-- Nút -->
      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="gd_khoa.php" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Thêm khoa</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
