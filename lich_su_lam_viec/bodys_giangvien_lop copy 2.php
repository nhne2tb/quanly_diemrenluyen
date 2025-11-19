<?php
// views/bodys_giangvien_lop/bodys_giangvien_lop.php
// ============================================
// HỒ SƠ GIẢNG VIÊN & DANH SÁCH LỚP PHỤ TRÁCH
// ============================================

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Kiểm tra đăng nhập giảng viên
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_giangvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$ma_gv = $_SESSION['user']['id'] ?? '';

// ======================
// 1️⃣ LẤY THÔNG TIN GIẢNG VIÊN
// ======================
$stmtGV = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ?");
$stmtGV->execute([$ma_gv]);
$giangvien = $stmtGV->fetch(PDO::FETCH_ASSOC);

if (!$giangvien) {
  session_destroy();
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

// ======================
// 2️⃣ LẤY DANH SÁCH LỚP PHỤ TRÁCH
// ======================
$stmtLop = $conn->prepare("SELECT * FROM tb_lop WHERE ma_gv = ?");
$stmtLop->execute([$ma_gv]);
$ds_lop = $stmtLop->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Giảng viên - Quản lý lớp học</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
  --brand:#004aad;
  --accent:#2e8bff;
  --bg:#f4f6fb;
}
body { background: var(--bg); font-family: 'Segoe UI', sans-serif; color: #333; }
.page-title { font-weight: 700; color: var(--brand); }
.card { border:none; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,0.05); background:#fff; }
.table thead { background:#e9f2ff; color:var(--brand); }
.table-hover tbody tr:hover { background-color:#f4f8ff; }
.btn-outline-primary { border-color:#004aad; color:#004aad; transition:all 0.2s ease-in-out; }
.btn-outline-primary:hover { background-color:#004aad; color:#fff; box-shadow:0 2px 6px rgba(0,74,173,0.25); }
.badge { font-size:0.9rem; font-weight:600; }
</style>
</head>
<body>

<div class="container-xxl my-4">
  <div class="mb-3 text-center">
    <h4 class="page-title"><i class="bi bi-person-workspace me-2"></i>HỒ SƠ GIẢNG VIÊN & QUẢN LÝ LỚP</h4>
  </div>

  <!-- THÔNG TIN GIẢNG VIÊN -->
  <div class="card p-4 mb-4">
    <div class="row">
      <div class="col-md-3 text-center">
        <img src="<?= htmlspecialchars($giangvien['avatar'] ?? BASE_URL.'assets/images/avatar_default.png') ?>" class="rounded-circle mb-3" width="120" height="120">
        <h5><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>
        <span class="badge bg-primary"><?= htmlspecialchars($giangvien['chuc_danh'] ?? 'Giảng viên') ?></span>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <p><b>Mã GV:</b> <?= htmlspecialchars($giangvien['ma_gv']) ?></p>
            <p><b>Giới tính:</b> <?= htmlspecialchars($giangvien['gioi_tinh']) ?></p>
            <p><b>Ngày sinh:</b> <?= date('d/m/Y', strtotime($giangvien['ngay_sinh'])) ?></p>
            <p><b>Học hàm/Học vị:</b> <?= htmlspecialchars($giangvien['hoc_ham'].' - '.$giangvien['hoc_vi']) ?></p>
            <p><b>Năm công tác:</b> <?= htmlspecialchars($giangvien['nam_cong_tac']) ?> năm</p>
          </div>
          <div class="col-md-6">
            <p><b>Bộ môn:</b> <?= htmlspecialchars($giangvien['bo_mon']) ?></p>
            <p><b>Khoa:</b> <?= htmlspecialchars($giangvien['ma_khoa']) ?></p>
            <p><b>Chuyên môn:</b> <?= htmlspecialchars($giangvien['chuyen_mon']) ?></p>
            <p><b>Email:</b> <?= htmlspecialchars($giangvien['email']) ?></p>
            <p><b>SĐT:</b> <?= htmlspecialchars($giangvien['sdt']) ?></p>
          </div>
        </div>
        <div class="mt-2"><b>Nhiệm vụ:</b> <?= htmlspecialchars($giangvien['nhiem_vu']) ?></div>
      </div>
    </div>
  </div>

  <!-- DANH SÁCH LỚP -->
  <div class="card p-4 shadow-sm border-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
      <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Danh sách lớp phụ trách</h5>
      <div class="input-group input-group-sm" style="width: 240px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchLop" class="form-control border-start-0 shadow-none" placeholder="Tìm kiếm lớp...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center mb-0" id="lopTable">
        <thead class="table-light border-bottom">
          <tr>
            <th>#</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Sĩ số</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($ds_lop): foreach ($ds_lop as $i => $lop): ?>
          <?php
          // ✅ Đếm sĩ số thực tế
          $ma_khoa = strtolower($lop['ma_khoa']);
          $ma_lop_lower = strtolower($lop['ma_lop']);
          $table_sv = "sv_" . $ma_khoa . "_" . $ma_lop_lower;
          $count = 0;
          try {
              $check = $conn->query("SHOW TABLES LIKE '$table_sv'");
              if ($check->rowCount() > 0) {
                  $stmtCount = $conn->query("SELECT COUNT(*) FROM `$table_sv`");
                  $count = $stmtCount->fetchColumn();
              }
          } catch (PDOException $e) { $count = 0; }
          ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
            <td class="text-start"><?= htmlspecialchars($lop['ten_lop']) ?></td>
            <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
            <td><span class="badge bg-secondary"><?= $count ?></span></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_lop_chitiet&lop=<?= urlencode($lop['ma_lop']) ?>" 
                 class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 fw-semibold shadow-sm">
                 <i class="bi bi-eye"></i> Xem chi tiết
              </a>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center text-muted py-3">Chưa có lớp phụ trách.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- QUẢN LÝ ĐIỂM RÈN LUYỆN -->
  <div class="card p-4 mt-4 shadow-sm border-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
      <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-clipboard-check-fill me-2"></i>Quản lý điểm rèn luyện</h5>
      <div class="input-group input-group-sm" style="width: 240px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchDRL" class="form-control border-start-0 shadow-none" placeholder="Tìm lớp...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center mb-0" id="drlTable">
        <thead class="table-light border-bottom">
          <tr>
            <th>#</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Sĩ số</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($ds_lop): foreach ($ds_lop as $i => $lop): ?>
          <?php
          // ✅ Đếm sĩ số thật
          $ma_khoa = strtolower($lop['ma_khoa']);
          $ma_lop_lower = strtolower($lop['ma_lop']);
          $table_sv = "sv_" . $ma_khoa . "_" . $ma_lop_lower;
          $count = 0;
          try {
              $check = $conn->query("SHOW TABLES LIKE '$table_sv'");
              if ($check->rowCount() > 0) {
                  $stmtCount = $conn->query("SELECT COUNT(*) FROM `$table_sv`");
                  $count = $stmtCount->fetchColumn();
              }
          } catch (PDOException $e) { $count = 0; }
          ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
            <td class="text-start"><?= htmlspecialchars($lop['ten_lop']) ?></td>
            <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
            <td><span class="badge bg-secondary"><?= $count ?></span></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl_lop&lop=<?= urlencode($lop['ma_lop']) ?>" 
                 class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1">
                <i class="bi bi-clipboard-check"></i> Xem DRL
              </a>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center text-muted py-3">Chưa có lớp phụ trách.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('searchLop').addEventListener('keyup', function() {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll('#lopTable tbody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
  });
});
document.getElementById('searchDRL').addEventListener('keyup', function() {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll('#drlTable tbody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
  });
});
</script>
</body>
</html>
