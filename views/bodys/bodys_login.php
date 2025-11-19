<?php
// ===============================================
// views/bodys/bodys_login.php
// Giao diện trang chủ + Đăng nhập hệ thống QLDRL (phiên bản không dùng .htaccess)
// ===============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// Kết nối CSDL
$conn = Database::connect();

// ================= HÀM LẤY THÔNG BÁO THEO TAB =================
function layThongBao(PDO $conn, string $tab) {
    $sql = "SELECT * FROM thong_bao WHERE tab = :tab ORDER BY ngay DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['tab' => $tab]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy thông báo tab1 & tab2
$thong_bao_tab1 = layThongBao($conn, 'tab1');
$thong_bao_tab2 = layThongBao($conn, 'tab2');

// Hiển thị thông báo lỗi đăng nhập (nếu có)
$login_error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($SITE_INFO['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root { --c1:#0056B3; --c2:#D62B28; --c3:#F5F9FF; --radius:10px; }
body { background-color:var(--c3); font-family:'Segoe UI',Roboto,sans-serif; color:#333; overflow-x:hidden; }
.container-main{margin-top:3rem;}
.noti-card{border:1px solid #e8e8e8;border-left:5px solid var(--c1);border-radius:var(--radius);padding:1.1rem 1.4rem;margin-bottom:1.2rem;background:#fff;box-shadow:0 2px 5px rgba(0,0,0,.04);}
.noti-card:hover{transform:translateY(-4px);box-shadow:0 6px 14px rgba(0,0,0,.08);border-left-color:var(--c2);}
.noti-card small{color:#888;}
.tab-header{display:flex;border-bottom:3px solid #dee2e6;margin-bottom:1rem;}
.tab-header button{border:none;background:transparent;padding:.6rem 1.5rem;font-weight:600;color:#666;transition:.25s;border-bottom:3px solid transparent;}
.tab-header button.active{color:var(--c1);border-bottom:3px solid var(--c1);}
.card-login{background:#fff;border-radius:var(--radius);box-shadow:0 8px 18px rgba(0,0,0,.08);padding:2rem;}
.card-login h6{color:var(--c1);font-weight:700;text-transform:uppercase;margin-bottom:1rem;}
.btn-primary{background:linear-gradient(135deg,var(--c1),#00408a);border:none;font-weight:600;}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 10px rgba(0,86,179,.3);}
.fade.show{display:block!important;opacity:1;transition:opacity .3s;}
.fade{opacity:0;display:none;transition:opacity .3s;}
</style>
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
<script>
function showTab(tabId) {
  const tabs = ['tab1', 'tab2'];
  tabs.forEach(id => {
    const el = document.getElementById(id);
    const btn = document.getElementById('btn' + id.charAt(0).toUpperCase() + id.slice(1));
    const active = (id === tabId);
    el.style.display = active ? 'block' : 'none';
    el.classList.toggle('show', active);
    btn.classList.toggle('active', active);
  });
}
</script>

</body>
</html>
