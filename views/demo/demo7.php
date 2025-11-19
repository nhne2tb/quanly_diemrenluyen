<?php
header('Content-Type: text/html; charset=utf-8');

// ==========================
// XÁC ĐỊNH BASE_URL TỰ ĐỘNG
// ==========================
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

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
/* ==============================
   BIẾN CSS TOÀN CỤC
============================== */
:root {
  --c1: #0056B3; /* Xanh DThU */
  --c2: #D62B28; /* Đỏ DThU */
  --c3: #F5F9FF; /* Nền sáng */
  --radius: 10px;
}

/* ==============================
   CẤU HÌNH CHUNG TRANG
============================== */
body {
  background-color: var(--c3);
  font-family: 'Segoe UI', Roboto, sans-serif;
  color: #333;
  overflow-x: hidden;
  animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==============================
   HEADER - PHẦN ĐẦU TRANG
============================== */
.header {
  background: linear-gradient(135deg, var(--c1), #003a80);
  color: #fff;
  padding: 1rem 0;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.header .container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}
.header img {
  height: 65px;
  filter: drop-shadow(0 0 4px rgba(255,255,255,0.4));
  transition: transform .3s ease;
}
.header img:hover { transform: scale(1.05); }
.header h5 { font-weight: 700; margin: 0; letter-spacing: .5px; }
.header p { margin: 0; font-size: 0.95rem; color: #dfe6ff; font-style: italic; }

/* ==============================
   MAIN CONTENT
============================== */
.container-main { margin-top: 3rem; }

/* ==============================
   THẺ THÔNG BÁO (NOTI CARD)
============================== */
.noti-card {
  border: 1px solid #e8e8e8;
  border-left: 5px solid var(--c1);
  border-radius: var(--radius);
  padding: 1.1rem 1.4rem;
  margin-bottom: 1.2rem;
  background: #fff;
  transition: all .25s ease-in-out;
  box-shadow: 0 2px 5px rgba(0,0,0,0.04);
}
.noti-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 14px rgba(0,0,0,0.08);
  border-left-color: var(--c2);
}
.noti-card small { color: #888; }
.noti-card a { color: var(--c2); text-decoration: none; font-weight: 500; }
.noti-card a:hover { text-decoration: underline; }

/* ==============================
   TAB HEADER
============================== */
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
  transition: all .25s;
  border-bottom: 3px solid transparent;
}
.tab-header button.active {
  color: var(--c1);
  border-bottom: 3px solid var(--c1);
}
.tab-header button:hover {
  color: var(--c1);
  background: rgba(0,86,179,0.08);
  border-radius: 4px 4px 0 0;
}

/* ==============================
   FORM ĐĂNG NHẬP
============================== */
.card-login {
  background: #fff;
  border-radius: var(--radius);
  box-shadow: 0 8px 18px rgba(0,0,0,0.08);
  padding: 2rem;
  transition: all .3s ease-in-out;
}
.card-login:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 24px rgba(0,0,0,0.1);
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
  background: linear-gradient(135deg, var(--c1), #00408a);
  border: none;
  font-weight: 600;
  text-transform: uppercase;
  transition: all .25s ease-in-out;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 10px rgba(0,86,179,0.3);
}
a.text-link {
  color: var(--c1);
  text-decoration: none;
  font-size: 0.9rem;
  transition: .2s;
}
a.text-link:hover { text-decoration: underline; color: var(--c2); }

/* ==============================
   FOOTER
============================== */
.footer {
  text-align: center;
  color: #666;
  font-size: 0.9rem;
  padding: 1.2rem 0;
  background: #fff;
  margin-top: 3rem;
  border-top: 1px solid #ddd;
  box-shadow: 0 -2px 6px rgba(0,0,0,0.04);
}

/* Hiển thị tab khi có class .show */
.fade.show { display: block !important; opacity: 1; transition: opacity .3s ease-in-out; }
.fade { opacity: 0; transition: opacity .3s ease-in-out; }
</style>
</head>

<body>

<!-- HEADER -->
<header class="header">
  <div class="container">
    <img src="<?= $base_url ?>assets/images/logo/logoweb.png" alt="Logo DThU">
    <div>
      <h5>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</h5>
      <p>Hệ thống Quản lý Điểm rèn luyện sinh viên</p>
    </div>
  </div>
</header>

<!-- MAIN -->
<div class="container container-main">
  <div class="row justify-content-center">
    <!-- CỘT TRÁI -->
    <div class="col-lg-7 mb-4">
      <div class="tab-header">
        <button class="active" id="btnTab1" onclick="showTab('tab1')">THÔNG BÁO RÈN LUYỆN</button>
        <button id="btnTab2" onclick="showTab('tab2')">QUY ĐỊNH – BIỂU MẪU</button>
      </div>

      <!-- TAB 1 -->
      <div id="tab1" class="fade show">
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

      <!-- TAB 2 -->
      <div id="tab2" class="fade">
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

    <!-- CỘT PHẢI -->
    <div class="col-lg-4">
      <div class="card-login">
        <h6 class="text-center mb-3">ĐĂNG NHẬP HỆ THỐNG</h6>
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
  © <?= date('Y') ?> Khoa Sư phạm Toán – Tin, Trường Đại học Đồng Tháp – Phát triển bởi Nguyễn Hồ Ninh Em (Sinh viên DTHU)
</footer>

<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ===============================
// HÀM CHUYỂN TAB HIỂN THỊ
// ===============================
function showTab(tabId) {
  const tabs = ['tab1', 'tab2'];
  tabs.forEach(id => {
    const el = document.getElementById(id);
    const btn = document.getElementById('btn' + id.charAt(0).toUpperCase() + id.slice(1));
    if (el && btn) {
      const active = (id === tabId);
      el.style.display = active ? 'block' : 'none';
      el.classList.toggle('show', active);
      btn.classList.toggle('active', active);
    }
  });
}
</script>

</body>
</html>
