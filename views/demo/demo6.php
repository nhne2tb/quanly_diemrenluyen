<?php
// ================== CẤU HÌNH DỰ ÁN ==================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
  ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$projectRoot = str_contains($host, 'localhost') ? '/quanly_diemrenluyen/' : '/';
define('BASE_URL', rtrim($protocol . $host . $projectRoot, '/') . '/');

// ================== THÔNG TIN SINH VIÊN ==================
$student = [
  'mssv' => '0022410322',
  'hoten' => 'Nguyễn Hồ Ninh Em',
  'lop' => '002241140210A',
  'khoa' => 'Khóa 2022',
  'bac' => 'Đại học',
  'loaihinh' => 'Chính quy',
  'nganh' => 'Sư phạm Tin học',
  'gioitinh' => 'Nam',
  'ngaysinh' => '01/02/2004',
  'noisinh' => 'Đồng Tháp'
];
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
<script src="https://cdn.jsdelivr.net/npm/bootchat@1.2.0/dist/bootchat.min.js"></script>

<style>
:root{
  --brand:#004aad;
  --accent:#2e8bff;
  --bg:#f4f6fb;
}
body{
  background:var(--bg);
  font-family:'Segoe UI',sans-serif;
  color:#333;
}
.navbar{
  background:#fff;
  border-bottom:1px solid #e0e0e0;
  box-shadow:0 2px 6px rgba(0,0,0,0.05);
}
.navbar .logo{height:48px;}
.brand-text b{color:var(--brand);}
.brand-text span{color:#e53935;font-weight:700;font-size:14px;}
.search-box .input-group-text{background:#fff;border-right:0;}
.search-box .form-control{border-left:0;}
.card{
  border:none;
  border-radius:14px;
  box-shadow:0 2px 8px rgba(0,0,0,0.05);
  background:#fff;
}
h5{font-weight:600;color:#222;}
.avatar{
  width:110px;
  height:110px;
  border-radius:50%;
  background:#dce3f5;
}
.quick .card{
  transition:all .2s ease;
  cursor:pointer;
}
.quick .card:hover{
  transform:translateY(-4px);
  background:linear-gradient(135deg,#e9f2ff,#f5f8ff);
  box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.quick .card:hover i{color:var(--accent);}
.quick h6{font-size:0.95rem;}
.quick p{font-size:0.8rem;}
.icon-xl{font-size:32px;color:#004aad;}
.container-xxl{max-width:1180px;}
footer{
  text-align:center;
  font-size:13px;
  color:#777;
  margin-top:40px;
}
</style>
</head>
<body>

<!-- =============== HEADER =============== -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-xxl d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <img src="<?= BASE_URL ?>assets/images/logo/logoweb.png" class="logo" alt="Logo">
      <div class="brand-text lh-sm">
        <b>BỘ GIÁO DỤC VÀ ĐÀO TẠO</b><br><span>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</span>
      </div>
    </div>

    <div class="search-box flex-grow-1 ms-3" style="max-width:460px;">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control" placeholder="Tìm kiếm...">
      </div>
    </div>

    <div class="d-flex align-items-center gap-3 ms-3">
      <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-house-door me-1"></i>Trang chủ</a>
      <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-bell me-1"></i>Tin tức</a>
      <div class="dropdown">
        <a href="#" class="text-secondary text-decoration-none dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
          <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" width="28" height="28" class="rounded-circle">
          <?= $student['hoten'] ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Thông tin cá nhân</a></li>
          <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- =============== BODY =============== -->
<div class="container-xxl my-4">
  <!-- TIÊU ĐỀ CHÍNH -->
  <div class="mb-3 text-center">
    <h4 class="fw-bold text-primary"><i class="bi bi-award me-2"></i>HỆ THỐNG QUẢN LÝ ĐIỂM RÈN LUYỆN SINH VIÊN</h4>
  </div>

  <!-- THÔNG TIN SINH VIÊN -->
  <div class="card p-4 mb-4">
    <h5 class="mb-3">Thông tin sinh viên</h5>
    <div class="row g-3 align-items-center">
      <div class="col-md-3 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="avatar mb-2">
        <div><a href="#" class="text-primary small">Xem chi tiết</a></div>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-sm-6">
            <p>MSSV: <b><?= $student['mssv'] ?></b></p>
            <p>Họ tên: <b><?= $student['hoten'] ?></b></p>
            <p>Giới tính: <?= $student['gioitinh'] ?></p>
            <p>Ngày sinh: <b><?= $student['ngaysinh'] ?></b></p>
            <p>Nơi sinh: <?= $student['noisinh'] ?></p>
          </div>
          <div class="col-sm-6">
            <p>Lớp học: <b><?= $student['lop'] ?></b></p>
            <p>Khóa học: <?= $student['khoa'] ?></p>
            <p>Bậc đào tạo: <?= $student['bac'] ?></p>
            <p>Loại hình đào tạo: <?= $student['loaihinh'] ?></p>
            <p>Ngành: <b><?= $student['nganh'] ?></b></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHỨC NĂNG DRL -->
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 quick">
    <div class="col">
      <div class="card p-4 text-center h-100">
        <i class="bi bi-journal-check icon-xl mb-2"></i>
        <h6 class="fw-semibold">Tự đánh giá kết quả rèn luyện học kỳ</h6>
        <p class="small text-secondary mb-0">Sinh viên tự chấm điểm DRL cho học kỳ hiện tại</p>
      </div>
    </div>
    <div class="col">
      <div class="card p-4 text-center h-100">
        <i class="bi bi-clipboard-data icon-xl mb-2"></i>
        <h6 class="fw-semibold">Kết quả rèn luyện</h6>
        <p class="small text-secondary mb-0">Xem tổng điểm và xếp loại DRL từng học kỳ</p>
      </div>
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

  <!-- CHAT -->
  <div id="chatbox" class="mt-4"></div>
</div>

<footer>© 2025 Trường Đại học Đồng Tháp — Hệ thống quản lý điểm rèn luyện</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  function fallbackChat(){
    const box=document.getElementById('chatbox');
    box.innerHTML=`
      <div class="card">
        <div class="card-header">Trợ lý rèn luyện (fallback)</div>
        <div class="card-body py-2">
          <div class="small text-secondary mb-2">Xin chào <?= $student['hoten'] ?>! Bạn có thể hỏi: "Điểm DRL kỳ trước?"</div>
          <div class="input-group input-group-sm">
            <input id="ask" class="form-control" placeholder="Gõ câu hỏi...">
            <button class="btn btn-outline-primary">Gửi</button>
          </div>
        </div>
      </div>`;
  }
  document.addEventListener('DOMContentLoaded',()=>{
    if(window.BootChat){
      const chat=new BootChat({
        element:"#chatbox",
        title:"Trợ lý rèn luyện",
        subtitle:"Tra cứu – Hỗ trợ điểm DRL",
        theme:"light"
      });
      chat.addMessage("Xin chào <?= $student['hoten'] ?> 👋","bot");
      chat.addMessage("Bạn muốn xem điểm rèn luyện hay gửi phúc khảo?","bot");
    }else fallbackChat();
  });
})();
</script>
</body>
</html>
