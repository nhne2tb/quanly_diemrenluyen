<?php
// ===============================
// views/headers/header_login.php
// ===============================
require_once __DIR__ . '/../../config/config.php';
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

<!-- CSS Header Login -->
<style>
:root {
  --c1: #0056B3;
  --c2: #D62B28;
  --c3: #F5F9FF;
  --radius: 10px;
}

/* Vùng bao riêng biệt cho header login */
.header-login-wrapper {
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

/* Header riêng biệt */
.header-login-wrapper .header {
  background: linear-gradient(135deg, var(--c1), #003a80);
  color: #fff;
  padding: 1rem 0;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.header-login-wrapper .header .container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}
.header-login-wrapper .header img {
  height: 65px;
  filter: drop-shadow(0 0 4px rgba(255,255,255,0.4));
  transition: transform .3s ease;
}
.header-login-wrapper .header img:hover { transform: scale(1.05); }
.header-login-wrapper .header h5 { font-weight: 700; margin: 0; letter-spacing: .5px; }
.header-login-wrapper .header p { margin: 0; font-size: 0.95rem; color: #dfe6ff; font-style: italic; }
</style>
</head>

<body class="header-login-wrapper">
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <img src="<?= BASE_URL ?>assets/images/logo/logoweb.png" alt="Logo DThU">
      <div>
        <h5>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</h5>
        <p>Hệ thống Quản lý Điểm rèn luyện sinh viên</p>
      </div>
    </div>
  </header>
  <!-- END HEADER -->
</body>
</html>
