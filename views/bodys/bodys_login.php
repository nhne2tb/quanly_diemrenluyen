<?php
// views/bodys/bodys_login.php
// Giao diện trang chủ + Đăng nhập hệ thống QLDRL
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($SITE_INFO['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bodys_login.css">

</head>
<body>

<div class="container container-main">
  <div class="row justify-content-center">

    <!-- CỘT TRÁI: THÔNG BÁO -->
    <div class="col-lg-7 mb-4">
      <div class="tab-header">
        <button class="active" id="btnTab1" onclick="showTab('tab1')">THÔNG BÁO RÈN LUYỆN</button>
        <button id="btnTab2" onclick="showTab('tab2')">QUY ĐỊNH – BIỂU MẪU</button>
      </div>

      <div id="tab1" class="fade show">
        <?php foreach ($thong_bao_tab1 as $row): ?>
          <div class="noti-card">
            <small><?= date('d/m/Y', strtotime($row['ngay'])) ?></small>
            <h6 class="fw-bold mt-1 mb-1">
              <?= htmlspecialchars($row['tieu_de']) ?>
              <?php if ($row['moi']): ?><span class="badge bg-danger">MỚI</span><?php endif; ?>
            </h6>
            <p class="mb-1"><?= nl2br(htmlspecialchars($row['noi_dung'])) ?></p>
            <?php if (!empty($row['lien_ket'])): ?>
              <a href="<?= htmlspecialchars($row['lien_ket']) ?>" target="_blank">Xem chi tiết</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div id="tab2" class="fade">
        <?php foreach ($thong_bao_tab2 as $row): ?>
          <div class="noti-card">
            <small><?= date('d/m/Y', strtotime($row['ngay'])) ?></small>
            <h6 class="fw-bold mt-1 mb-1"><?= htmlspecialchars($row['tieu_de']) ?></h6>
            <p class="mb-1"><?= nl2br(htmlspecialchars($row['noi_dung'])) ?></p>
            <?php if (!empty($row['lien_ket'])): ?>
              <a href="<?= htmlspecialchars($row['lien_ket']) ?>" target="_blank">Xem chi tiết</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- CỘT PHẢI: ĐĂNG NHẬP -->
    <div class="col-lg-4">
      <div class="card-login">
        <h6 class="text-center mb-3">ĐĂNG NHẬP HỆ THỐNG</h6>

        <?php if($login_error): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>

        <!-- ✅ Form xử lý đăng nhập khi KHÔNG dùng .htaccess -->
        <form action="<?= BASE_URL ?>index.php?route=login" method="POST">
          <div class="mb-3">
            <label class="form-label">Tên đăng nhập / Mã sinh viên</label>
            <input type="text" name="ten_dang_nhap" class="form-control" placeholder="Nhập tên đăng nhập hoặc MSSV" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="mat_khau" class="form-control" placeholder="Nhập mật khẩu" required>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
            </div>
            <!-- chưa làm quên mật khẩu -->
            <a href="index.php?route=quenmatkhau" class="text-decoration-none text-primary">Quên mật khẩu?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100">ĐĂNG NHẬP</button>
        </form>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= BASE_URL ?>/assets/js/bodys_login.js"></script>

</body>
</html>
