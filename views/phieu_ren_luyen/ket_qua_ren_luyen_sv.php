<?php
// views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// ==================== KIỂM TRA ĐĂNG NHẬP ====================
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_sinhvien') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$conn = Database::connect();

// ==================== LẤY THÔNG TIN SV ====================
$mssv = $_SESSION['user']['id'];
$lopTable = $_SESSION['user']['lop']; // vd: sv_sptin_tin22a

$stmt = $conn->prepare("SELECT * FROM `$lopTable` WHERE mssv = ?");
$stmt->execute([$mssv]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Không tìm thấy sinh viên trong bảng lớp!</div>");
}

// ==================== TẠO BẢNG PHIẾU DRL ====================
$ma_khoa = strtolower($sv['ma_khoa']);
$ma_lop  = strtolower($sv['lop']);
$tableRenLuyen = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";

// Kiểm tra bảng DRL tồn tại
$check = $conn->query("SHOW TABLES LIKE '$tableRenLuyen'");
if ($check->rowCount() === 0) {
    die("<div class='alert alert-warning text-center mt-5'>
            ⚠ Lớp <b>{$sv['lop']}</b> chưa được tạo bảng phiếu rèn luyện!
        </div>");
}

// ==================== LẤY PHIẾU DRL THEO MSSV ====================
$stmt = $conn->prepare("
    SELECT *
    FROM `$tableRenLuyen`
    WHERE mssv = ?
    ORDER BY nam_bd ASC, hoc_ky ASC
");
$stmt->execute([$mssv]);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Kết quả rèn luyện</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  background: #f0f4ff;
  font-family: "Segoe UI", sans-serif;
}

.container-box {
  background: #fff;
  max-width: 1050px;
  margin: 40px auto;
  padding: 35px 40px;
  border-radius: 20px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.08);
  border: 1px solid #e4ecff;
}

h3 {
  font-weight: 800;
  color: #004aad;
  letter-spacing: .3px;
}

.table {
  border-radius: 12px;
  overflow: hidden;
}

.table thead th {
  background: #e7efff !important;
  color: #004aad !important;
  font-size: .9rem;
  font-weight: 700;
  vertical-align: middle;
  border-bottom: none !important;
}

.table tbody tr {
  background: #fff;
  transition: .15s ease;
}

.table tbody tr:hover {
  background: #f5f9ff;
}

.badge {
  padding: 6px 12px;
  font-size: .75rem;
  border-radius: 8px;
}

.badge-warning {
  background: #f9d46c !important;
  color: #5c4500 !important;
}

.badge-success {
  background: #47c46d !important;
}

.btn-view {
  border-radius: 8px;
  font-size: .8rem;
  padding: 5px 14px;
  border: 1px solid #004aad;
  color: #004aad;
  transition: .15s;
}

.btn-view:hover {
  background: #004aad;
  color: #fff;
}

td {
  padding: 12px 10px !important;
  font-size: .9rem;
  vertical-align: middle;
}

/* Cột ghi chú */
.note-col {
  max-width: 160px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ============================
   🎨 ĐỒNG BỘ MÀU – TONE #004aad
   Không thay đổi cấu trúc gốc
============================ */

/* Header màu */
h3, .page-title, .container-box h3 {
    color: #004aad !important;
}

/* Màu bảng head */
.table thead th {
    background: #004aad !important;
    color: #fff !important;
}

/* Border table */
.table-bordered > :not(caption) > * > * {
    border-color: #dce6ff !important;
}



.badge.bg-secondary {
    background-color: #BFCBEA !important;
}

/* Nút Xem */
.btn-view,
.btn.btn-outline-primary.btn-sm.view-detail {
    border-color: #004aad !important;
    color: #004aad !important;
    font-weight: 600;
}

.btn-view:hover,
.btn.btn-outline-primary.btn-sm.view-detail:hover {
    background: #004aad !important;
    color: #fff !important;
}

/* Nút Quay lại */
.btn-outline-primary {
    border-color: #004aad !important;
    color: #004aad !important;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background: #004aad !important;
    color: #fff !important;
}

/* Modal header màu */
.modal-header.bg-primary {
    background: #004aad !important;
}

/* Hiệu ứng hover cho hàng */
.table tbody tr:hover {
    background: #eef4ff !important;
}

</style>

</head>

<body>

<div class="container-box">

  <div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="m-0 fw-bold" style="color:#004aad;">
  Kết quả rèn luyện sinh viên
</h3>


<a href="<?= BASE_URL ?>index.php?route=bodys_sinhvien" 
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>

  </div>

  <hr>

  <!-- THÔNG TIN SINH VIÊN
  <div class="mb-4">
    <p><b>MSSV:</b> <?= htmlspecialchars($sv['mssv']) ?></p>
    <p><b>Họ tên:</b> <?= htmlspecialchars($sv['ho_ten']) ?></p>
    <p><b>Lớp:</b> <?= htmlspecialchars($sv['lop']) ?></p>
    <p><b>Ngành:</b> <?= htmlspecialchars($sv['nganh_hoc']) ?></p>
  </div> -->

  <?php if (!$list): ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle"></i> Chưa có phiếu rèn luyện nào.
    </div>

  <?php else: ?>

  <!-- BẢNG KẾT QUẢ -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-primary">
        <tr>
          <th>STT</th>
          <th>Học kỳ</th>
          <th>Năm học</th>
          <th>Tổng điểm</th>
          <th>Xếp loại</th>
          <th>Trạng thái lớp</th>
          <th>Trạng thái khoa</th>
                    <th>Ghi chú lớp</th>
          <th>Ghi chú CVHT</th>
          <th>Chi tiết</th>
        </tr>
      </thead>

      <tbody>
      <?php
      $i = 1;
      foreach ($list as $r):

        $tong = floatval($r['tong_diem']);

        // Xếp loại
        $xl = "Kém"; $class="row-kem";
        if ($tong >= 90) { $xl="Xuất sắc"; $class="row-xuatsac"; }
        elseif ($tong >= 80) { $xl="Tốt"; $class="row-tot"; }
        elseif ($tong >= 65) { $xl="Khá"; $class="row-kha"; }
        elseif ($tong >= 50) { $xl="Trung bình"; $class="row-trungbinh"; }
        elseif ($tong >= 35) { $xl="Yếu"; $class="row-yeu"; }

        // Badge trạng thái
$badgeLop = match($r['trang_thai_lop']) {
  "Đã duyệt" => "<span class='badge bg-success'>Đã duyệt</span>",
  "Trả về"   => "<span class='badge bg-warning text-dark'>Trả về</span>",
  default    => "<span class='badge bg-secondary'>Chưa duyệt</span>"
};

$badgeKhoa = match($r['trang_thai']) {
  "Đã duyệt" => "<span class='badge bg-success'>Đã duyệt</span>",
  "Trả về"   => "<span class='badge bg-warning text-dark'>Trả về</span>",
  default    => "<span class='badge bg-secondary'>Chưa duyệt</span>"
};

      ?>

        <tr class="<?= $class ?>">
          <td><?= $i++ ?></td>
          <td><?= $r['hoc_ky'] ?></td>
          <td><?= $r['nam_bd'] ?>–<?= $r['nam_kt'] ?></td>
          <td><b><?= $tong ?></b></td>
          <td><b><?= $xl ?></b></td>
          <td><?= $badgeLop ?></td>
          <td><?= $badgeKhoa ?></td>
          <td><?= htmlspecialchars($r['ghi_chu_lop'] ?: '—') ?></td>
<td><?= htmlspecialchars($r['ghi_chu_co_van'] ?: '—') ?></td>

          <td>
<a href="<?= BASE_URL ?>index.php?route=xem_phieu_ren_luyen_sv&id=<?= $r['id'] ?>&table=<?= $tableRenLuyen ?>"
   class="btn btn-outline-primary btn-sm">
    Xem
</a>


          </td>
        </tr>

      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php endif; ?>
</div>

<!-- Modal chi tiết -->
<div class="modal fade" id="detailModal">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Chi tiết phiếu rèn luyện</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalContent">
        Đang tải dữ liệu...
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.view-detail').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const modalBody = document.getElementById('modalContent');

        modalBody.innerHTML = "<p class='text-center text-muted'>⏳ Đang tải...</p>";

        const res = await fetch(
            "<?= BASE_URL ?>views/phieu_ren_luyen/xem_chi_tiet_api.php?id=" + id + "&table=<?= $tableRenLuyen ?>"
        );

        modalBody.innerHTML = await res.text();
        new bootstrap.Modal(document.getElementById('detailModal')).show();
    });
});
</script>

</body>
</html>
