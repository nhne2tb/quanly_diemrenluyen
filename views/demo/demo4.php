<?php
// =====================================================
// index.php — Giao diện đăng nhập hệ thống điểm rèn luyện
// =====================================================
header('Content-Type: text/html; charset=utf-8');

// --- Xác định BASE_URL tự động ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$projectRoot = str_contains($host, 'localhost') ? '/quanly_diemrenluyen/' : '/';
$base_url = rtrim($protocol . $host . $projectRoot, '/') . '/';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hệ thống Quản lý Điểm rèn luyện - Trường Đại học Đồng Tháp</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --c1: #0056B3; /* Xanh DThU */
  --c2: #D62B28; /* Đỏ DThU */
  --c3: #F5F9FF; /* Nền sáng */
}
body {
  background-color: var(--c3);
  font-family: 'Segoe UI', Roboto, sans-serif;
  color: #333;
  overflow-x: hidden;
}

/* --- Header --- */
.header {
  background-color: var(--c1);
  color: #fff;
  padding: .8rem 0;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.header .container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .75rem;
}
.header img {
  height: 58px;
}
.header h5 {
  font-weight: 700;
  margin: 0;
  letter-spacing: .5px;
}

/* --- Thông báo --- */
.container-main { margin-top: 2.5rem; }
.noti-card {
  border: 1px solid #e8e8e8;
  border-left: 5px solid var(--c1);
  border-radius: 6px;
  padding: 1rem 1.25rem;
  margin-bottom: 1rem;
  background: #fff;
  transition: all .2s;
}
.noti-card:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
.noti-card small { color: #888; }
.noti-card a { color: var(--c2); text-decoration: none; font-weight: 500; }
.noti-card a:hover { text-decoration: underline; }

/* --- Tabs --- */
.tab-header {
  display: flex;
  border-bottom: 3px solid #dee2e6;
  margin-bottom: 1rem;
}
.tab-header button {
  border: none;
  background: transparent;
  padding: .6rem 1.5rem;
  font-weight: 600;
  color: #666;
  transition: all .2s;
  border-bottom: 3px solid transparent;
}
.tab-header button.active {
  color: var(--c1);
  border-bottom: 3px solid var(--c1);
}

/* --- Form login --- */
.card-login {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  padding: 1.8rem;
}
.card-login h6 {
  color: var(--c1);
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 1rem;
}
.form-control:focus {
  border-color: var(--c1);
  box-shadow: 0 0 0 .2rem rgba(0,86,179,.25);
}
.btn-primary {
  background-color: var(--c1);
  border-color: var(--c1);
  font-weight: 600;
  text-transform: uppercase;
}
.btn-primary:hover { background-color: #00408a; }
a.text-link { color: var(--c1); text-decoration: none; font-size: 0.9rem; }
a.text-link:hover { text-decoration: underline; }

/* --- Footer --- */
.footer {
  text-align: center;
  color: #666;
  font-size: 0.9rem;
  padding: 1.2rem 0;
  background: #fff;
  margin-top: 3rem;
  border-top: 1px solid #ddd;
}
</style>
</head>

<body>

<!-- HEADER -->
<header class="header">
  <div class="container">
    <img src="<?= $base_url ?>assets/images/logo/logoweb.png" alt="Logo DThU">
    <div>
      <h5>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</h5>
      <p class="mb-0 small text-white-50 fst-italic">Hệ thống Quản lý Điểm rèn luyện sinh viên</p>
    </div>
  </div>
</header>

<!-- MAIN -->
<div class="container container-main">
  <div class="row justify-content-center">
    <!-- Cột trái: Thông báo -->
    <div class="col-lg-7 mb-4">
      <div class="tab-header">
        <button class="active" id="btnTab1" onclick="showTab('tab1')">THÔNG BÁO RÈN LUYỆN</button>
        <button id="btnTab2" onclick="showTab('tab2')">QUY ĐỊNH – BIỂU MẪU</button>
      </div>

      <div id="tab1">
        <div class="noti-card">
          <small>Tháng 09 / 15</small>
          <h6 class="fw-bold mt-1 mb-1">Hướng dẫn sinh viên tự đánh giá điểm rèn luyện học kỳ I năm học 2025–2026 <span class="badge bg-danger">NEW</span></h6>
          <p class="mb-1">Sinh viên truy cập hệ thống để tự đánh giá điểm rèn luyện, hoàn tất trước ngày <b>15/10/2025</b>.</p>
          <a href="#">Xem chi tiết</a>
        </div>
        <div class="noti-card">
          <small>Tháng 08 / 30</small>
          <h6 class="fw-bold mt-1 mb-1">Danh sách sinh viên đạt danh hiệu “Sinh viên 5 tốt” cấp trường</h6>
          <p class="mb-1">Khoa công bố danh sách sinh viên đạt danh hiệu “Sinh viên 5 tốt” học kỳ II năm học 2024–2025.</p>
          <a href="#">Xem chi tiết</a>
        </div>
      </div>

      <div id="tab2" style="display:none;">
        <div class="noti-card">
          <small>Tháng 08 / 01</small>
          <h6 class="fw-bold mt-1 mb-1">Biểu mẫu đánh giá điểm rèn luyện sinh viên</h6>
          <p class="mb-1">Tải mẫu phiếu đánh giá điểm rèn luyện do Phòng Công tác Sinh viên ban hành.</p>
          <a href="#">Tải xuống</a>
        </div>
        <div class="noti-card">
          <small>Tháng 07 / 10</small>
          <h6 class="fw-bold mt-1 mb-1">Quy định tính điểm rèn luyện năm học 2025–2026</h6>
          <p class="mb-1">Áp dụng mới quy chế điểm rèn luyện cho sinh viên toàn trường.</p>
          <a href="#">Xem quy định</a>
        </div>
      </div>
    </div>

    <!-- Cột phải: Đăng nhập -->
    <div class="col-lg-4">
      <div class="card-login">
        <h6 class="text-center mb-3">HỆ THỐNG QUẢN LÝ ĐIỂM RÈN LUYỆN SINH VIÊN</h6>
        <form action="dashboard.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Mã sinh viên</label>
            <input type="text" name="masv" class="form-control" placeholder="VD: DH22TIN001" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="matkhau" class="form-control" placeholder="Nhập mật khẩu" required>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
            </div>
            <a href="quenmatkhau.php" class="text-link">Quên mật khẩu?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100 mb-2">ĐĂNG NHẬP</button>
          <div class="text-center mt-3">
            <small class="text-muted">Dành cho sinh viên các khoa: Sư phạm Toán – Tin, Sinh học, Kinh tế, Ngữ văn, Tiểu học</small>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  © <?= date('Y') ?> Khoa Sư phạm Toán – Tin, Trường Đại học Đồng Tháp –
  <a href="https://dthu.edu.vn" class="text-decoration-none text-primary fw-semibold">dthu.edu.vn</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showTab(tabId) {
  document.getElementById('tab1').style.display = (tabId === 'tab1') ? 'block' : 'none';
  document.getElementById('tab2').style.display = (tabId === 'tab2') ? 'block' : 'none';
  document.getElementById('btnTab1').classList.toggle('active', tabId === 'tab1');
  document.getElementById('btnTab2').classList.toggle('active', tabId === 'tab2');
}
</script>

</body>
</html>
