<?php
// views/bodys_admin/bodys_admin.php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';


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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trang quản trị - Hệ thống QLDRL</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --brand: #004aad;
  --bg: #f5f7fa;
}
body {
  font-family: 'Segoe UI', sans-serif;
  background: var(--bg);
  color: #333;
  margin: 0;
  padding: 0;
}

/* ===== HEADER XANH ===== */
.header-top {
  background: var(--brand);
  color: #fff;
  padding: 10px 0;
}
.header-top .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}
.header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}
.header-left img {
  height: 50px;
  width: auto;
}
.header-left h5 {
  margin: 0;
  font-size: 1.1rem;
  line-height: 1.2;
  font-weight: 600;
}
.header-right {
  display: flex;
  align-items: center;
  gap: 10px;
}
.header-right img {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  border: 2px solid #fff;
}
.header-right span {
  font-weight: 500;
}
.logout-btn {
  background: #fff;
  color: var(--brand);
  border: none;
  border-radius: 50px;
  padding: 4px 12px;
  font-size: 0.9rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 5px;
  text-decoration: none;
}
.logout-btn:hover {
  background: #e9eefc;
  color: #002d7a;
}

/* ===== DASHBOARD ===== */
.container-dashboard {
  padding: 40px 15px;
}
.admin-card, .quick-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: all 0.25s ease-in-out;
}
.admin-card:hover, .quick-card:hover {
  transform: translateY(-4px);
}
.admin-card {
  padding: 25px 20px;
  text-align: center;
}
.admin-card img {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  margin-bottom: 12px;
}
.admin-card h6 {
  color: var(--brand);
  font-weight: 700;
}
.quick-card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 28px 18px;
  text-decoration: none;
  color: #333;
  height: 100%;
  min-height: 160px;
}
.quick-card i {
  font-size: 42px;
  color: var(--brand);
  margin-bottom: 10px;
}
.quick-card h6 {
  font-weight: 600;
  color: var(--brand);
  margin-bottom: 6px;
}
.quick-card p {
  font-size: 0.9rem;
  color: #555;
  margin: 0;
}

/* Responsive căn giữa đẹp trên mọi thiết bị */
@media (max-width: 991px) {
  .admin-card {
    margin-bottom: 20px;
  }
}
@media (max-width: 575px) {
  .quick-card {
    padding: 20px 10px;
  }
  .quick-card i {
    font-size: 36px;
  }
}

/* ===== FOOTER ===== */
footer {
  background: #fff;
  text-align: center;
  padding: 10px 0;
  font-size: 0.9rem;
  color: #666;
  border-top: 1px solid #eee;
}
footer small {
  display: block;
  color: #888;
}


</style>
</head>
<body>

<!-- ===== DASHBOARD ===== -->
<div class="container container-dashboard">
  <div class="row g-4">
    <!-- Card thông tin admin -->
    <div class="col-lg-4 col-md-6">
      <div class="admin-card">
          <a href="<?= BASE_URL ?>index.php?route=logout" class="logout-btn">
    <i class="bi bi-box-arrow-right"></i> Đăng xuất
  </a>
        <img src="<?= $admin['avatar'] ?>" alt="Avatar">
        <h6><?= htmlspecialchars($admin['hoten']) ?></h6>
        <p><strong>Mã quản trị:</strong> <?= $admin['ma_ad'] ?></p>
        <p><strong>Email:</strong> <?= $admin['email'] ?></p>
        <p><strong>Quyền hạn:</strong> <?= $admin['quyen'] ?></p>
        
      </div>
    </div>

    <!-- Các chức năng -->
    <div class="col-lg-8 col-md-6">
      <div class="row g-3">
        <div class="col-md-6 col-sm-6">
          <a href="<?= BASE_URL ?>index.php?route=gd_khoa" class="quick-card">
            <i class="bi bi-building"></i>
            <h6>Quản lý khoa</h6>
            <p>Thêm, sửa, xóa thông tin các khoa</p>
          </a>
        </div>
        <div class="col-md-6 col-sm-6">
          <a href="<?= BASE_URL ?>index.php?route=gd_giangvien" class="quick-card">
            <i class="bi bi-people"></i>
            <h6>Quản lý giảng viên</h6>
            <p>Quản lý danh sách giảng viên toàn trường</p>
          </a>
        </div>
        <div class="col-md-6 col-sm-6">
          <a href="<?= BASE_URL ?>index.php?route=gd_lop" class="quick-card">
            <i class="bi bi-mortarboard"></i>
            <h6>Quản lý lớp học</h6>
            <p>Thêm lớp, sửa thông tin và tạo bảng sinh viên</p>
          </a>
        </div>
        <div class="col-md-6 col-sm-6">
          <a href="<?= BASE_URL ?>index.php?route=gd_thongbao" class="quick-card">
            <i class="bi bi-megaphone"></i>
            <h6>Quản lý thông báo</h6>
            <p>Đăng và quản lý các thông báo hệ thống</p>
          </a>
        </div>

        <!-- 🔹 Mục mới: Quản lý tất cả tài khoản -->
        <div class="col-md-6 col-sm-6">
          <a href="<?= BASE_URL ?>index.php?route=gd_taikhoan" class="quick-card">
            <i class="bi bi-person-gear"></i>
            <h6>Quản lý tất cả tài khoản</h6>
            <p>Xem, khóa, hoặc phân quyền người dùng toàn hệ thống</p>
          </a>
        </div>

<!-- <div class="col-md-6 col-sm-6">
  <a href="<?= BASE_URL ?>index.php?route=admin_lop" class="quick-card">
    <i class="bi bi-people-fill"></i>
    <h6>Quản lý danh sách lớp</h6>
    <p>Xem, sửa, xóa lớp và thêm sinh viên vào từng lớp</p>
  </a>
</div> -->


      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
