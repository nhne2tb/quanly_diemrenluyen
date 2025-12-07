<?php

// views/bodys_sinhvien/bodys_sinhvien.php
// ================== CẤU HÌNH DỰ ÁN ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// Nếu chưa đăng nhập hoặc không phải sinh viên → quay về trang đăng nhập
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_sinhvien') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

// Lấy MSSV và bảng lớp từ session
$mssv = $_SESSION['user']['id'] ?? '';
$lopTable = $_SESSION['user']['lop'] ?? '';

$student = [];

if ($mssv && $lopTable) {
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM `$lopTable` WHERE mssv = ?");
    $stmt->execute([$mssv]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Nếu không lấy được thông tin → đăng xuất
if (!$student) {
    session_destroy();
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hệ thống Quản lý điểm rèn luyện</title>
<link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/logo/logoweb.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bodys_sinhvien.css">

</head>

<body>
<div class="container-xxl my-4">

  <!-- TIÊU ĐỀ -->
  <div class="position-relative">
    <h4 class="page-title">
      <i class=""></i>HỆ THỐNG QUẢN LÝ ĐIỂM RÈN LUYỆN SINH VIÊN
    </h4>
  </div>

  <!-- THÔNG TIN SINH VIÊN -->
  <div class="card p-4 mb-4 student-info position-relative">

    <!-- Nút Đăng xuất -->
    <a href="<?= BASE_URL ?>index.php?route=logout"
       class="logout-top-left fw-bold d-flex align-items-center">
      <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
    </a>

    <div class="row align-items-center">
      <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
             class="rounded-circle mb-2" width="120" height="120" alt="Avatar">
        <div class="student-name"><?= htmlspecialchars($student['ho_ten']) ?></div>
        <span class="badge badge-status"><?= htmlspecialchars($student['trang_thai']) ?></span>
      </div>

      <div class="col-lg-9 col-md-8">
        <div class="row">
          <div class="col-sm-6">
            <div class="info-line">MSSV: <b><?= htmlspecialchars($student['mssv']) ?></b></div>
            <div class="info-line">Họ tên: <b><?= htmlspecialchars($student['ho_ten']) ?></b></div>
            <div class="info-line">Giới tính: <b><?= htmlspecialchars($student['gioi_tinh']) ?></b></div>
            <div class="info-line">
              Ngày sinh:
              <b>
                <?php
                  if (!empty($student['ngay_sinh'])) {
                      echo htmlspecialchars(date('d/m/Y', strtotime($student['ngay_sinh'])));
                  } else {
                      echo '—';
                  }
                ?>
              </b>
            </div>
            <div class="info-line">Nơi sinh: <b><?= htmlspecialchars($student['noi_sinh']) ?></b></div>
          </div>

          <div class="col-sm-6">
            <div class="info-line">Lớp học: <b><?= htmlspecialchars($student['lop']) ?></b></div>
            <div class="info-line">Khóa học: <b><?= htmlspecialchars($student['nien_khoa']) ?></b></div>
            <div class="info-line">Bậc đào tạo: <b><?= htmlspecialchars($student['bac_dao_tao']) ?></b></div>
            <div class="info-line">Loại hình đào tạo: <b><?= htmlspecialchars($student['loai_hinh']) ?></b></div>
            <div class="info-line">Ngành: <b><?= htmlspecialchars($student['nganh_hoc']) ?></b></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHỨC NĂNG DRL -->
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 quick">
    <div class="col">
      <a href="<?= BASE_URL ?>index.php?route=phieu_ren_luyen_sv" class="text-decoration-none text-dark">
        <div class="card p-4 text-center h-100">
          <i class="bi bi-journal-check icon-xl mb-2"></i>
          <h6 class="fw-semibold">Tự đánh giá kết quả rèn luyện học kỳ</h6>
          <p class="small text-secondary mb-0">Sinh viên tự chấm điểm DRL cho học kỳ hiện tại</p>
        </div>
      </a>
    </div>

    <div class="col">
      <a href="<?= BASE_URL ?>index.php?route=ket_qua_ren_luyen_sv" class="text-decoration-none text-dark">
        <div class="card p-4 text-center h-100 shadow-sm">
          <i class="bi bi-clipboard-data icon-xl mb-2"></i>
          <h6 class="fw-semibold">Kết quả rèn luyện</h6>
          <p class="small text-secondary mb-0">Xem tổng điểm và xếp loại DRL từng học kỳ</p>
        </div>
      </a>
    </div>

    <div class="col">
      <div class="card p-4 text-center h-100">
        <i class="bi bi-pencil-square icon-xl mb-2"></i>
        <h6 class="fw-semibold">Phúc khảo điểm rèn luyện</h6>
        <p class="small text-secondary mb-0">Gửi yêu cầu phúc khảo điểm DRL cho học kỳ</p>
      </div>
    </div>

    <div class="col">
      <div class="card p-4 text-center h-100">
        <i class="bi bi-bar-chart-line icon-xl mb-2"></i>
        <h6 class="fw-semibold">Kết quả phúc khảo điểm rèn luyện</h6>
        <p class="small text-secondary mb-0">Xem kết quả xử lý đơn phúc khảo DRL</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
