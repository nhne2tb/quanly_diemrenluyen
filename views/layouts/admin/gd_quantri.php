<?php
// ===============================================
// views/layouts/admin/gd_quantri.php — Dashboard Quản trị QLDRL
// ===============================================

// ✅ BASE_URL động
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$projectRoot = str_contains($host, 'localhost') ? '/quanly_diemrenluyen/' : '/';
define('BASE_URL', rtrim($protocol . $host . $projectRoot, '/') . '/');

session_start();

// ✅ Thông tin admin (giả lập)
$admin = [
  'ma_ad'  => 'AD001',
  'hoten'  => 'Quản trị hệ thống',
  'email'  => 'admin@dthu.edu.vn',
  'quyen'  => 'Quản trị toàn quyền',
  'avatar' => 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang quản trị - Hệ thống QLDRL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --brand: #004aad;
      --accent: #2e8bff;
      --bg: #f5f7fa;
    }
    body {
      background: var(--bg);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }
    /* Header */
    header {
      background: #fff;
      padding: 14px 28px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h5 {
      margin: 0;
      font-weight: 700;
      color: var(--brand);
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1.15rem;
    }
    /* Card thông tin admin */
    .admin-card {
      background: #fff;
      border-radius: 16px;
      padding: 28px 20px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .admin-card img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid #eaf2ff;
      margin-bottom: 12px;
    }
    .admin-card h6 {
      font-weight: 700;
      color: var(--brand);
    }
    .admin-card p {
      font-size: 0.9rem;
      margin: 4px 0;
    }
    /* Quick cards */
    .quick-card {
      background: #fff;
      border-radius: 16px;
      text-align: center;
      padding: 28px 18px;
      height: 100%;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      transition: all .2s ease;
      text-decoration: none;
      display: block;
      color: #333;
    }
    .quick-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      background: linear-gradient(135deg, #f7faff, #f0f7ff);
    }
    .quick-card i {
      font-size: 40px;
      color: var(--brand);
      margin-bottom: 10px;
    }
    .quick-card h6 {
      font-weight: 600;
      font-size: 1rem;
    }
    .quick-card p {
      font-size: 0.85rem;
      color: #555;
      margin: 0;
    }
    .top-user {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .top-user img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
    @media (max-width: 768px) {
      .quick-card { padding: 20px 12px; }
    }
  </style>
</head>
<body>

<header>
  <h5><i class="bi bi-shield-lock-fill"></i> HỆ THỐNG QUẢN TRỊ ĐIỂM RÈN LUYỆN</h5>
  <div class="top-user">
    <img src="<?= $admin['avatar'] ?>" alt="Admin">
    <span><?= htmlspecialchars($admin['hoten']) ?></span>
  </div>
</header>

<div class="container py-4">
  <div class="row g-4">
    <!-- Card trái -->
    <div class="col-lg-4">
      <div class="admin-card">
        <img src="<?= $admin['avatar'] ?>" alt="Avatar">
        <h6><?= htmlspecialchars($admin['hoten']) ?></h6>
        <p><strong>Mã quản trị:</strong> <?= $admin['ma_ad'] ?></p>
        <p><strong>Email:</strong> <?= $admin['email'] ?></p>
        <p><strong>Quyền hạn:</strong> <?= $admin['quyen'] ?></p>
      </div>
    </div>

    <!-- Card chức năng phải -->
    <div class="col-lg-8">
      <div class="row g-3">
        <div class="col-md-6">
          <a href="<?= BASE_URL ?>views/layouts/admin/admin_khoa/gd_khoa.php" class="quick-card">
            <i class="bi bi-building"></i>
            <h6>Quản lý khoa</h6>
            <p>Thêm, sửa, xóa thông tin các khoa</p>
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?= BASE_URL ?>views/layouts/admin/admin_giangvien/gd_giangvien.php" class="quick-card">
            <i class="bi bi-people"></i>
            <h6>Quản lý giảng viên</h6>
            <p>Quản lý danh sách giảng viên toàn trường</p>
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?= BASE_URL ?>views/layouts/admin/admin_lop/gd_lop.php" class="quick-card">
            <i class="bi bi-mortarboard"></i>
            <h6>Quản lý lớp học</h6>
            <p>Thêm lớp, sửa thông tin và tự tạo bảng sinh viên lớp</p>
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?= BASE_URL ?>views/layouts/admin/admin_thongbao/gd_thongbao.php" class="quick-card">
            <i class="bi bi-megaphone"></i>
            <h6>Quản lý thông báo</h6>
            <p>Đăng và quản lý các thông báo hệ thống</p>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
