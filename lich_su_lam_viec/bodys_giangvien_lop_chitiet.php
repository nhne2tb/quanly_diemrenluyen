<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_giangvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$ma_lop = $_GET['lop'] ?? '';
if (!$ma_lop) {
  echo "<script>alert('Thiếu mã lớp');window.history.back();</script>";
  exit;
}

// 🧩 1️⃣ Lấy thông tin lớp
$sqlLop = "SELECT * FROM tb_lop WHERE ma_lop = ?";
$stmtLop = $conn->prepare($sqlLop);
$stmtLop->execute([$ma_lop]);
$lop = $stmtLop->fetch(PDO::FETCH_ASSOC);
if (!$lop) {
  echo "<script>alert('Không tìm thấy lớp này');window.history.back();</script>";
  exit;
}

// 🧩 2️⃣ Lấy danh sách sinh viên trong lớp
$sqlSV = "SELECT * FROM `$ma_lop` ORDER BY ho_ten ASC";
$stmtSV = $conn->prepare($sqlSV);
$stmtSV->execute();
$sinhviens = $stmtSV->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chi tiết lớp <?= htmlspecialchars($ma_lop) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
  --brand: #004aad;
  --accent: #2e8bff;
  --bg: #f4f6fb;
}
body {
  background: var(--bg);
  font-family: 'Segoe UI', sans-serif;
  color: #333;
}
.card {
  border: none;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
h4.page-title {
  font-weight: 700;
  color: var(--brand);
}
.table thead {
  background: #e9f2ff;
  color: var(--brand);
}
.table-hover tbody tr:hover {
  background: #f4f8ff;
}
.badge {
  font-size: 13px;
  padding: 6px 10px;
}
.info-box {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.info-item {
  margin-bottom: 8px;
}
.info-item b {
  width: 130px;
  display: inline-block;
  color: var(--brand);
}
.btn-back {
  background-color: #004aad;
  color: white;
}
.btn-back:hover {
  background-color: #003a8c;
  color: white;
}
</style>
</head>

<body>
<div class="container-xxl py-4">
  <!-- Tiêu đề -->
  <div class="text-center mb-4">
    <h4 class="page-title"><i class="bi bi-people-fill me-2"></i>Chi tiết lớp <?= htmlspecialchars($lop['ten_lop']) ?></h4>
    <div class="text-muted">Mã lớp: <b><?= htmlspecialchars($lop['ma_lop']) ?></b></div>
  </div>

  <!-- Thông tin lớp -->
  <div class="info-box mb-4">
    <div class="row">
      <div class="col-md-6">
        <div class="info-item"><b>Niên khóa:</b> <?= htmlspecialchars($lop['nien_khoa']) ?></div>
        <div class="info-item"><b>Sĩ số:</b> <?= htmlspecialchars($lop['si_so'] ?? '-') ?></div>
      </div>
      <div class="col-md-6">
        <div class="info-item"><b>Khoa:</b> <?= htmlspecialchars($lop['ma_khoa']) ?></div>
        <div class="info-item"><b>Ghi chú:</b> <?= htmlspecialchars($lop['ghi_chu'] ?? 'Không có') ?></div>
      </div>
    </div>
  </div>

  <!-- Danh sách sinh viên -->
  <div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 text-primary"><i class="bi bi-list-ul me-2"></i>Danh sách sinh viên</h5>
      <span class="badge bg-secondary">Tổng: <?= count($sinhviens) ?> SV</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th style="width:120px;">MSSV</th>
            <th class="text-start">Họ và tên</th>
            <th style="width:90px;">Giới tính</th>
            <th style="width:140px;">Ngày sinh</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($sinhviens) > 0): ?>
            <?php foreach ($sinhviens as $i => $sv): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($sv['mssv']) ?></td>
                <td class="text-start"><?= htmlspecialchars($sv['ho_ten']) ?></td>
                <td><?= htmlspecialchars($sv['gioi_tinh']) ?></td>
                <td><?= date('d/m/Y', strtotime($sv['ngay_sinh'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-muted text-center py-3">Chưa có sinh viên trong lớp này.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Nút quay lại -->
  <div class="text-center mt-4">
    <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_lop" class="btn btn-back px-4 py-2 rounded-pill">
      <i class="bi bi-arrow-left-circle me-2"></i>Quay lại danh sách lớp
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
