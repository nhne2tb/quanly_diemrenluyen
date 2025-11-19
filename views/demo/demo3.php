<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cổng thông tin sinh viên - Trường Đại học Đồng Tháp</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --c1: #0056B3; /* Xanh DThU */
  --c2: #D62B28; /* Đỏ DThU */
  --c3: #F5F9FF; /* Nền nhẹ */
}

body {
  background-color: var(--c3);
  font-family: 'Segoe UI', Roboto, sans-serif;
  color: #333;
  overflow-x: hidden;
}

.header {
  background-color: var(--c1);
  color: #fff;
  padding: 1rem 0;
  text-align: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.header img {
  height: 58px;
  margin-bottom: .3rem;
}

.header h5 {
  font-weight: 700;
  letter-spacing: .5px;
}

.header p {
  margin: 0;
  font-size: 1rem;
  letter-spacing: .5px;
}

.container {
  margin-top: 2.5rem;
}

/* Cột thông báo */
.noti-card {
  border: 1px solid #e8e8e8;
  border-left: 5px solid var(--c1);
  border-radius: 6px;
  padding: 1rem 1.25rem;
  margin-bottom: 1rem;
  background: #fff;
  transition: all .2s;
}

.noti-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.noti-card small {
  color: #888;
}

.noti-card a {
  color: var(--c2);
  text-decoration: none;
  font-weight: 500;
}

.noti-card a:hover {
  text-decoration: underline;
}

/* Tabs */
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

/* Form login */
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

.btn-primary:hover {
  background-color: #00408a;
}

.btn-sso {
  border: 1px solid #ccc;
  width: 100%;
  margin-top: .5rem;
  background: #f8f9fa;
}

.btn-sso:hover {
  background: #e9ecef;
}

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
<div class="header">
  <img src="https://dthu.edu.vn/Portals/0/logo-dthu.png" alt="Logo DThU">
  <h5>BỘ GIÁO DỤC VÀ ĐÀO TẠO</h5>
  <p>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</p>
</div>

<!-- MAIN -->
<div class="container">
  <div class="row justify-content-center">
    <!-- Bên trái -->
    <div class="col-lg-7 mb-4">
      <div class="tab-header">
        <button class="active" id="btnTab1" onclick="showTab('tab1')">THÔNG BÁO SINH VIÊN</button>
        <button id="btnTab2" onclick="showTab('tab2')">THÔNG BÁO ĐÀO TẠO</button>
      </div>

      <!-- Tab 1 -->
      <div id="tab1">
        <div class="noti-card">
          <small>Tháng 09 / 12</small>
          <h6 class="fw-bold mt-1 mb-1">HƯỚNG DẪN ĐĂNG KÝ NHẬN ĐIỂM I VÀ THI LẠI/ĐÁNH GIÁ LẠI <span class="badge bg-danger">NEW</span></h6>
          <p class="mb-1">Sinh viên có lý do chính đáng vắng thi kết thúc học phần được phép đăng ký thi lại, đánh giá lại...</p>
          <a href="#">Xem chi tiết</a>
        </div>
        <div class="noti-card">
          <small>Tháng 10 / 29</small>
          <h6 class="fw-bold mt-1 mb-1">Quy chế tổ chức thi, đánh giá và quản lý kết quả học tập</h6>
          <p class="mb-1">Thông báo quy chế công tác thi, đánh giá và quản lý kết quả học tập dành cho sinh viên chính quy.</p>
          <a href="#">Xem chi tiết</a>
        </div>
        <div class="noti-card">
          <small>Tháng 12 / 06</small>
          <h6 class="fw-bold mt-1 mb-1">Thông báo thu học phí học kỳ II năm học 2024–2025</h6>
          <p class="mb-1">Sinh viên thực hiện đóng học phí học kỳ II theo hướng dẫn của phòng Đào tạo.</p>
          <a href="#">Xem chi tiết</a>
        </div>
      </div>

      <!-- Tab 2 -->
      <div id="tab2" style="display:none;">
        <div class="noti-card">
          <small>Tháng 08 / 01</small>
          <h6 class="fw-bold mt-1 mb-1">Kế hoạch học lại học kỳ hè 2025</h6>
          <p class="mb-1">Các học phần chưa đạt sẽ được tổ chức học lại theo kế hoạch mới nhất của Khoa.</p>
          <a href="#">Xem chi tiết</a>
        </div>
      </div>

      <div class="text-end mt-3">
        <a href="#" class="fw-bold text-danger text-decoration-none">XEM THÊM <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>

    <!-- Bên phải: Đăng nhập -->
    <div class="col-lg-4">
      <div class="card-login">
        <h6 class="text-center">CỔNG THÔNG TIN SINH VIÊN</h6>
        <form>
          <div class="mb-3">
            <label class="form-label">Mã sinh viên</label>
            <input type="text" class="form-control" placeholder="Nhập mã sinh viên">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" placeholder="Nhập mật khẩu">
          </div>
          <div class="d-flex justify-content-between mb-3 align-items-center">
            <div>
              <input type="checkbox" id="chk">
              <label for="chk">Đã tốt nghiệp</label>
            </div>
            <a href="#" class="small text-decoration-none text-primary">Quên mật khẩu?</a>
          </div>
          <div class="mb-3 d-flex align-items-center">
            <input type="text" class="form-control me-2" placeholder="Nhập mã">
            <img src="https://dummyimage.com/80x32/ccc/000.png&text=GV15" alt="captcha" height="32">
          </div>
          <button type="submit" class="btn btn-primary w-100 mb-2">ĐĂNG NHẬP</button>
          <button type="button" class="btn btn-sso"><i class="bi bi-microsoft"></i> Đăng nhập SSO Office 365</button>
          <button type="button" class="btn btn-sso"><i class="bi bi-google"></i> Đăng nhập SSO Google</button>
          <div class="text-center mt-3">
            <a href="#" class="btn btn-outline-primary btn-sm">Dành cho phụ huynh</a>
          </div>
          <hr>
          <div class="text-center">
            <p class="small text-muted mb-1">Tải ứng dụng DThU Student</p>
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5f/Download_on_the_App_Store_Badge.svg" height="40">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<div class="footer">
  © <?= date('Y') ?> Trường Đại học Đồng Tháp – 
  <a href="https://dthu.edu.vn" class="text-decoration-none text-primary fw-semibold">dthu.edu.vn</a>
</div>

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
