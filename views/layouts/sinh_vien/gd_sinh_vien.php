<?php
// views/layouts/sinh_vien/gd_sinh_vien.php
// ================== CẤU HÌNH DỰ ÁN ==================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
  ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$projectRoot = str_contains($host, 'localhost') ? '/quanly_diemrenluyen/' : '/';
define('BASE_URL', rtrim($protocol . $host . $projectRoot, '/') . '/');

// ================== DỮ LIỆU MẪU SINH VIÊN ==================
$student = [
  'mssv' => '0022410322',
  'hoten' => 'Nguyễn Hồ Ninh Em',
  'lop' => '002241140210A',
  'khoa' => 'Khóa 2022',
  'bac' => 'Đại học',
  'loaihinh' => 'Chính Quy',
  'nganh' => 'Sư phạm Tin học',
  'gioitinh' => 'Nam',
  'ngaysinh' => '01/02/2004',
  'noisinh' => 'Đồng Tháp',
  'trang_thai' => 'Đang học'
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
  --bg:linear-gradient(135deg,#eef3f9 0%,#f7f9fc 100%);
}
body{
  background:var(--bg);
  font-family:'Segoe UI',sans-serif;
  color:#333;
  padding: 24px;
}
h4.page-title{
  font-weight:800;
  color:var(--brand);
  text-align:center;
  margin-bottom:1.5rem;
  letter-spacing:0.5px;
}
.card{
  border:none;
  border-radius:18px;
  background:rgba(255,255,255,.75);
  backdrop-filter:blur(12px);
  -webkit-backdrop-filter:blur(12px);
  box-shadow:0 4px 20px rgba(0,0,0,0.05);
  transition:.25s ease;
}
.card:hover{
  transform:translateY(-3px);
  box-shadow:0 8px 24px rgba(0,0,0,0.1);
}
.student-info img{
  border:4px solid #e9f2ff;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
  transition:.25s;
}
.student-info img:hover{ transform:scale(1.08) rotate(1deg); }
.student-name{
  font-weight:600;
  font-size:1.05rem;
  margin-bottom:4px;
}
.badge-status{
  background:var(--brand);
  font-size:0.8rem;
  padding:6px 12px;
  border-radius:999px;
}
.info-line{
  margin-bottom:4px;
  color:#555;
  font-size:0.95rem;
}
.info-line b{
  font-weight:600;
  color:#2e2e2e;
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
.icon-xl{font-size:32px;color:var(--brand);}
.container-xxl{max-width:1180px;}
</style>
</head>
<body>

<div class="container-xxl my-4">
  <!-- TIÊU ĐỀ -->
  <h4 class="page-title"><i class="bi bi-award me-2"></i>HỆ THỐNG QUẢN LÝ ĐIỂM RÈN LUYỆN SINH VIÊN</h4>

  <!-- THÔNG TIN SINH VIÊN -->
  <div class="card p-4 mb-4 student-info">
    <div class="row align-items-center">
      <!-- Avatar trái -->
      <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="rounded-circle mb-2" width="120" height="120" alt="Avatar">
        <div class="student-name"><?= htmlspecialchars($student['hoten']) ?></div>
        <span class="badge badge-status"><?= htmlspecialchars($student['trang_thai']) ?></span>
      </div>

      <!-- Thông tin phải -->
      <div class="col-lg-9 col-md-8">
        <div class="row">
          <div class="col-sm-6">
            <div class="info-line">MSSV: <b><?= $student['mssv'] ?></b></div>
            <div class="info-line">Họ tên: <b><?= $student['hoten'] ?></b></div>
            <div class="info-line">Giới tính: <b><?= $student['gioitinh'] ?></b></div>
            <div class="info-line">Ngày sinh: <b><?= $student['ngaysinh'] ?></b></div>
            <div class="info-line">Nơi sinh: <b><?= $student['noisinh'] ?></b></div>
          </div>
          <div class="col-sm-6">
            <div class="info-line">Lớp học: <b><?= $student['lop'] ?></b></div>
            <div class="info-line">Khóa học: <b><?= $student['khoa'] ?></b></div>
            <div class="info-line">Bậc đào tạo: <b><?= $student['bac'] ?></b></div>
            <div class="info-line">Loại hình đào tạo: <b><?= $student['loaihinh'] ?></b></div>
            <div class="info-line">Ngành: <b><?= $student['nganh'] ?></b></div>
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
