<?php
// ===============================================
// views/headers/header.php
// ===============================================

// Gọi file config
require_once __DIR__ . '/../../config/config.php';

// Lấy thông tin từ config
$school_logo = !empty($SCHOOL_INFO['logo'])
    ? $SCHOOL_INFO['logo']
    : BASE_URL . 'assets/images/logo/logoweb.png';

$school_name = $SCHOOL_INFO['ten_truong'] ?? 'Trường Đại học Đồng Tháp';
$school_code = $SCHOOL_INFO['ma_truong'] ?? 'SPD';
$school_site = $SCHOOL_INFO['website'] ?? 'https://dthu.edu.vn';

$color1 = $SCHOOL_INFO['mau1'] ?? '#0056B3';
$color2 = $SCHOOL_INFO['mau2'] ?? '#D62B28';
$color3 = $SCHOOL_INFO['mau3'] ?? '#F5F9FF';

$site_title = $SITE_INFO['title'] ?? 'Hệ thống Quản lý Điểm rèn luyện - DThU';
$site_desc  = $SITE_INFO['description'] ?? 'Website quản lý điểm rèn luyện sinh viên DThU';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($site_title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= htmlspecialchars($site_desc) ?>">
  <meta name="author" content="<?= htmlspecialchars($SITE_INFO['author'] ?? 'Sinh viên DThU') ?>">
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/logo/logoweb.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root {
      --c1: <?= $color1 ?>;
      --c2: <?= $color2 ?>;
      --c3: <?= $color3 ?>;
    }

    body { background-color: var(--c3); font-family: 'Segoe UI', Roboto, sans-serif; }
    .brand-top { background: var(--c3); border-bottom: 1px solid #dee2e6; padding: .5rem 0; }
    .navbar { background: var(--c1); }
    .navbar .nav-link { color: #fff !important; font-weight: 500; transition: .2s; }
    .navbar .nav-link.active, .navbar .nav-link:hover { color: var(--c2) !important; }
    .btn-login { border-color: var(--c1); color: var(--c1); }
    .btn-login:hover { background: var(--c1); color: #fff; }
    .badge-xl { font-size: .9rem; padding: .5rem .75rem; }
    .xl-blue { background: rgba(13,110,253,.12); color: var(--c1); }
    .xl-green { background: rgba(32,201,151,.12); color: var(--c2); }
    .xl-orange { background: rgba(243,156,18,.12); color: var(--c3); }
    .brand-top img { transition: transform .2s; }
    .brand-top img:hover { transform: scale(1.05); }
  </style>
</head>

<body>
<header class="brand-top">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
      <a href="<?= BASE_URL ?>" class="d-flex align-items-center text-decoration-none">
        <img src="<?= htmlspecialchars($school_logo) ?>" alt="Logo DThU" height="55" class="me-2">
        <div>
          <h5 class="mb-0 fw-bold" style="color:var(--c1);">
            <?= htmlspecialchars($school_name) ?>
          </h5>
          <small class="text-muted"><?= htmlspecialchars($site_title) ?></small>
        </div>
      </a>
    </div>
    <a href="<?= BASE_URL ?>login.php" class="btn btn-login">
      <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
    </a>
  </div>
</header>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-uppercase" href="<?= BASE_URL ?>">QLDRL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a href="<?= BASE_URL ?>" class="nav-link <?= empty($khoa_id) ? 'active' : '' ?>">Trang chủ</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Sinh viên</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Giảng viên</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Thống kê</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Liên hệ</a></li>
      </ul>
      <span class="navbar-text text-light small"><i class="bi bi-person-circle"></i> Khách truy cập</span>
    </div>
  </div>
</nav>

<main class="container py-4">
