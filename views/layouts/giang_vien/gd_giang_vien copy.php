<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/db.php';

$conn = Database::connect();
$ma_gv = $_SESSION['ma_gv'] ?? 'GV001';

// ================== THÔNG TIN GIẢNG VIÊN ==================
$sql_gv = "
    SELECT gv.*, k.ten_khoa 
    FROM tb_giangvien gv 
    LEFT JOIN tb_khoa k ON gv.ma_khoa = k.ma_khoa
    WHERE gv.ma_gv = :magv
";
$stmt = $conn->prepare($sql_gv);
$stmt->execute(['magv' => $ma_gv]);
$giangvien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$giangvien) {
    die("<h3 style='text-align:center;margin-top:50px;color:red'>❌ Không tìm thấy thông tin giảng viên!</h3>");
}

// ================== DANH SÁCH LỚP PHỤ TRÁCH ==================
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if ($keyword !== '') {
    $sql_lop = "
        SELECT ma_lop, ten_lop, nien_khoa, khoa_hoc, he_dao_tao, bac_dao_tao, nganh_hoc, si_so, ghi_chu
        FROM tb_lop
        WHERE ma_gv = :magv
          AND (ma_lop LIKE :kw OR ten_lop LIKE :kw)
        ORDER BY nien_khoa DESC
    ";
    $stmt = $conn->prepare($sql_lop);
    $stmt->execute(['magv' => $ma_gv, 'kw' => "%$keyword%"]);
} else {
    $sql_lop = "
        SELECT ma_lop, ten_lop, nien_khoa, khoa_hoc, he_dao_tao, bac_dao_tao, nganh_hoc, si_so, ghi_chu
        FROM tb_lop
        WHERE ma_gv = :magv
        ORDER BY nien_khoa DESC
    ";
    $stmt = $conn->prepare($sql_lop);
    $stmt->execute(['magv' => $ma_gv]);
}
$ds_lop = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hồ sơ Giảng viên & Quản lý lớp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root { --brand:#004aad; --bg:linear-gradient(135deg,#eef3f9 0%,#f7f9fc 100%); }
    body {
      background:var(--bg);
      font-family:'Segoe UI',sans-serif;
      color:#333;
      padding:24px;
    }
    h4.page-title {
      font-weight:800;
      color:var(--brand);
      text-align:center;
      margin-bottom:1.2rem;
      letter-spacing:0.5px;
    }
    .card {
      border:none;
      border-radius:18px;
      background:rgba(255,255,255,.75);
      backdrop-filter:blur(12px);
      -webkit-backdrop-filter:blur(12px);
      box-shadow:0 4px 20px rgba(0,0,0,0.05);
      transition:.25s ease;
    }
    .card:hover {
      transform:translateY(-3px);
      box-shadow:0 8px 24px rgba(0,0,0,0.1);
    }
    .teacher-info img {
      border:4px solid #e9f2ff;
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
      transition:.25s;
    }
    .teacher-info img:hover { transform:scale(1.08) rotate(1deg); }
    .badge.bg-primary { background:var(--brand)!important; font-size:.8rem; padding:5px 10px; }
    .table thead {
      background:linear-gradient(90deg,#e9f2ff 0%,#d8e8ff 100%);
      color:var(--brand);
    }
    .table-hover tbody tr:hover { background-color:#f4f8ff; cursor:pointer; }
    .badge.bg-secondary {
      background:var(--brand)!important;
      font-size:.8rem;
      padding:6px 10px;
    }
    .ten-lop-link {
      color: var(--brand);
      font-weight: 600;
      text-decoration: none;
      transition: color .2s;
    }
    .ten-lop-link:hover {
      text-decoration: underline;
      color: #003080;
    }
  </style>
</head>
<body>

<!-- ===== THÔNG TIN GIẢNG VIÊN ===== -->
<div class="container-xxl mb-4">
  <h4 class="page-title">HỒ SƠ GIẢNG VIÊN</h4>
  <div class="card p-4 teacher-info">
    <div class="row align-items-center">
      <div class="col-md-3 text-center mb-3 mb-md-0">
        <img src="<?= htmlspecialchars($giangvien['avatar']) ?>" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
        <h5><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>
        <span class="badge bg-primary"><?= htmlspecialchars($giangvien['chuc_danh']) ?></span>
      </div>
      <div class="col-md-9">
        <div class="row g-2">
          <div class="col-md-6">
            <p><b>Mã GV:</b> <?= $giangvien['ma_gv'] ?></p>
            <p><b>Giới tính:</b> <?= $giangvien['gioi_tinh'] ?></p>
            <p><b>Ngày sinh:</b> <?= date('d/m/Y', strtotime($giangvien['ngay_sinh'])) ?></p>
            <p><b>Học hàm:</b> <?= $giangvien['hoc_ham'] ?></p>
            <p><b>Học vị:</b> <?= $giangvien['hoc_vi'] ?></p>
          </div>
          <div class="col-md-6">
            <p><b>Bộ môn:</b> <?= $giangvien['bo_mon'] ?></p>
            <p><b>Khoa:</b> <?= $giangvien['ten_khoa'] ?></p>
            <p><b>Chuyên môn:</b> <?= $giangvien['chuyen_mon'] ?></p>
            <p><b>Email:</b> <?= $giangvien['email'] ?></p>
            <p><b>Điện thoại:</b> <?= $giangvien['sdt'] ?></p>
          </div>
        </div>
        <div class="mt-2"><b>Nhiệm vụ:</b> <?= $giangvien['nhiem_vu'] ?></div>
      </div>
    </div>
  </div>
</div>

<!-- ===== DANH SÁCH LỚP PHỤ TRÁCH ===== -->
<div class="container-xxl">
  <h4 class="page-title">Danh sách lớp phụ trách</h4>

  <!-- Form tìm kiếm -->
  <form method="get" class="mb-3">
    <div class="input-group" style="max-width: 400px; margin: 0 auto;">
      <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Nhập tên hoặc mã lớp...">
      <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </div>
  </form>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Khóa học</th>
            <th>Hệ</th>
            <th>Bậc</th>
            <th>Ngành học</th>
            <th>Sĩ số</th>
            <th>Ghi chú</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($ds_lop)): ?>
            <?php foreach ($ds_lop as $i => $lop): ?>
              <tr onclick="xemSinhVien('<?= htmlspecialchars($lop['ma_lop']) ?>')">
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
                <td>
                  <a href="javascript:void(0)" class="ten-lop-link">
                    <?= htmlspecialchars($lop['ten_lop']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
                <td><?= htmlspecialchars($lop['khoa_hoc']) ?></td>
                <td><?= htmlspecialchars($lop['he_dao_tao']) ?></td>
                <td><?= htmlspecialchars($lop['bac_dao_tao']) ?></td>
                <td><?= htmlspecialchars($lop['nganh_hoc']) ?></td>
                <td class="text-center">
                  <span class="badge bg-secondary rounded-pill"><?= (int)$lop['si_so'] ?></span>
                </td>
                <td><?= htmlspecialchars($lop['ghi_chu']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center text-muted">Không tìm thấy lớp nào phù hợp</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ===== MODAL DANH SÁCH SINH VIÊN ===== -->
<div class="modal fade" id="modalSinhVien" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Danh sách sinh viên - <span id="modalTenLop"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modalContentSV" class="table-responsive"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function xemSinhVien(maLop) {
  fetch('xu_ly_sinh_vien_lop.php?ma_lop=' + encodeURIComponent(maLop))
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalTenLop').textContent = maLop;
      document.getElementById('modalContentSV').innerHTML = html;
      const modal = new bootstrap.Modal(document.getElementById('modalSinhVien'));
      modal.show();
    })
    .catch(err => console.error(err));
}
</script>
</body>
</html>
