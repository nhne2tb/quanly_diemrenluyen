<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// ✅ Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_giangvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$ma_gv = $_SESSION['user']['id'] ?? '';

// ======================
// 1️⃣ LẤY THÔNG TIN GIẢNG VIÊN
// ======================
$sqlGV = "SELECT * FROM tb_giangvien WHERE ma_gv = ?";
$stmtGV = $conn->prepare($sqlGV);
$stmtGV->execute([$ma_gv]);
$giangvien = $stmtGV->fetch(PDO::FETCH_ASSOC);

if (!$giangvien) {
  session_destroy();
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

// ======================
// 2️⃣ LẤY DANH SÁCH LỚP PHỤ TRÁCH
// ======================
$sqlLop = "SELECT * FROM tb_lop WHERE ma_gv = ?";
$stmtLop = $conn->prepare($sqlLop);
$stmtLop->execute([$ma_gv]);
$ds_lop = $stmtLop->fetchAll(PDO::FETCH_ASSOC);

// ======================
// 3️⃣ ĐẾM SĨ SỐ SINH VIÊN MỖI LỚP
// ======================
foreach ($ds_lop as &$lop) {
  $table_sv = "sv_" . strtolower($lop['ma_khoa']) . "_" . strtolower($lop['ma_lop']);
  $check = $conn->query("SHOW TABLES LIKE '$table_sv'");
  if ($check->rowCount() > 0) {
    $lop['si_so'] = $conn->query("SELECT COUNT(*) FROM `$table_sv`")->fetchColumn();
  } else {
    $lop['si_so'] = 0;
  }
}

// ======================
// 4️⃣ LẤY DỮ LIỆU BIỂU ĐỒ DRL
// ======================
$chartData = [];
foreach ($ds_lop as $lop) {
  $table_drl = "phieu_ren_luyen_" . strtolower($lop['ma_khoa']) . "_" . strtolower($lop['ma_lop']);
  $check = $conn->query("SHOW TABLES LIKE '$table_drl'");
  if ($check->rowCount() === 0) continue;

  $stmt = $conn->query("
    SELECT hoc_ky, COUNT(*) AS so_luong
    FROM `$table_drl`
    GROUP BY hoc_ky
    ORDER BY FIELD(hoc_ky, 'I','II','Hè')
  ");
  while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $chartData[$r['hoc_ky']] = ($chartData[$r['hoc_ky']] ?? 0) + $r['so_luong'];
  }
}
foreach (['I','II','Hè'] as $ky) if (!isset($chartData[$ky])) $chartData[$ky] = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bảng điều khiển Giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root { --brand:#004aad; --accent:#2e8bff; --bg:#f4f6fb; }
body { background:var(--bg); font-family:'Segoe UI',sans-serif; color:#333; }
.card { border:none; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,0.08); background:#fff; }
.page-title { font-weight:700; color:var(--brand); }
.table-hover tbody tr:hover { background-color:#f4f8ff; }
.quick-card {
  display:block; background:#fff; border-radius:12px; padding:20px;
  text-align:center; color:#333; text-decoration:none;
  box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:all .25s;
}
.quick-card:hover { transform:translateY(-4px); box-shadow:0 4px 12px rgba(0,74,173,.25); }
.quick-card i { font-size:2rem; margin-bottom:10px; }
</style>
</head>
<body>

<div class="container-xxl py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h4 class="page-title mb-0"><i class="bi bi-person-workspace me-2"></i>BẢNG ĐIỀU KHIỂN GIẢNG VIÊN</h4>
    <a href="<?= BASE_URL ?>index.php?route=logout" class="btn btn-danger btn-sm">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- THÔNG TIN GIẢNG VIÊN -->
  <div class="card p-4 mb-4">
    <div class="row">
      <div class="col-md-3 text-center">
        <img src="<?= htmlspecialchars($giangvien['avatar'] ?? BASE_URL.'assets/images/avatar_default.png') ?>" class="rounded-circle mb-3" width="120" height="120">
        <h5><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>
        <span class="badge bg-primary"><?= htmlspecialchars($giangvien['chuc_danh'] ?? 'Giảng viên') ?></span>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <p><b>Mã GV:</b> <?= htmlspecialchars($giangvien['ma_gv']) ?></p>
            <p><b>Giới tính:</b> <?= htmlspecialchars($giangvien['gioi_tinh']) ?></p>
            <p><b>Ngày sinh:</b> <?= date('d/m/Y', strtotime($giangvien['ngay_sinh'])) ?></p>
            <p><b>Học hàm/Học vị:</b> <?= htmlspecialchars($giangvien['hoc_ham'].' - '.$giangvien['hoc_vi']) ?></p>
            <p><b>Năm công tác:</b> <?= htmlspecialchars($giangvien['nam_cong_tac']) ?> năm</p>
          </div>
          <div class="col-md-6">
            <p><b>Bộ môn:</b> <?= htmlspecialchars($giangvien['bo_mon']) ?></p>
            <p><b>Khoa:</b> <?= htmlspecialchars($giangvien['ma_khoa']) ?></p>
            <p><b>Chuyên môn:</b> <?= htmlspecialchars($giangvien['chuyen_mon']) ?></p>
            <p><b>Email:</b> <?= htmlspecialchars($giangvien['email']) ?></p>
            <p><b>SĐT:</b> <?= htmlspecialchars($giangvien['sdt']) ?></p>
          </div>
        </div>
        <div class="mt-2"><b>Nhiệm vụ:</b> <?= htmlspecialchars($giangvien['nhiem_vu']) ?></div>
      </div>
    </div>
  </div>

  <!-- CHỨC NĂNG NHANH -->
  <div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_lop" class="quick-card">
        <i class="bi bi-people-fill text-primary"></i>
        <h6>Danh sách lớp phụ trách</h6>
        <p>Xem thông tin lớp và sinh viên</p>
      </a>
    </div>
    <div class="col-md-3 col-sm-6">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" class="quick-card">
        <i class="bi bi-clipboard-check-fill text-success"></i>
        <h6>Quản lý điểm rèn luyện</h6>
        <p>Duyệt và thống kê DRL sinh viên</p>
      </a>
    </div>
    <div class="col-md-3 col-sm-6">
      <a href="<?= BASE_URL ?>index.php?route=bodys_thongbao" class="quick-card">
        <i class="bi bi-megaphone text-warning"></i>
        <h6>Thông báo từ Khoa</h6>
        <p>Cập nhật thông tin mới nhất</p>
      </a>
    </div>
    <div class="col-md-3 col-sm-6">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_hoso" class="quick-card">
        <i class="bi bi-person-badge-fill text-info"></i>
        <h6>Hồ sơ cá nhân</h6>
        <p>Cập nhật và chỉnh sửa thông tin</p>
      </a>
    </div>
  </div>

  <!-- DANH SÁCH LỚP PHỤ TRÁCH -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h5 class="fw-bold text-primary mb-0"><i class="bi bi-mortarboard-fill me-2"></i>Lớp phụ trách</h5>
      <div class="input-group input-group-sm" style="width:220px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchLop" class="form-control border-start-0 shadow-none" placeholder="Tìm lớp...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center" id="lopTable">
        <thead class="table-light">
          <tr><th>#</th><th>Mã lớp</th><th>Tên lớp</th><th>Niên khóa</th><th>Sĩ số</th><th>Thao tác</th></tr>
        </thead>
        <tbody>
        <?php if ($ds_lop): foreach ($ds_lop as $i=>$lop): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
            <td><?= htmlspecialchars($lop['ten_lop']) ?></td>
            <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
            <td><span class="badge bg-secondary"><?= $lop['si_so'] ?></span></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_lop_chitiet&lop=<?= urlencode($lop['ma_lop']) ?>" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-eye"></i> Xem</a>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-muted py-3">Chưa có lớp phụ trách.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BIỂU ĐỒ DRL -->
  <div class="card p-4 shadow-sm border-0">
    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-bar-chart-line-fill me-2"></i>Thống kê sinh viên đã đánh giá DRL theo học kỳ</h5>
    <canvas id="chartDRL" height="120"></canvas>
  </div>
</div>

<script>
document.getElementById('searchLop')?.addEventListener('keyup', e=>{
  const kw=e.target.value.toLowerCase();
  document.querySelectorAll('#lopTable tbody tr').forEach(r=>{
    r.style.display=r.innerText.toLowerCase().includes(kw)?'':'none';
  });
});

const chartData = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
const ctx = document.getElementById('chartDRL').getContext('2d');
if(Object.keys(chartData).length){
  new Chart(ctx,{
    type:'bar',
    data:{
      labels:Object.keys(chartData),
      datasets:[{label:'Số sinh viên đã đánh giá',data:Object.values(chartData),
        borderRadius:8,backgroundColor:['#0d6efd','#198754','#ffc107']}]
    },
    options:{
      plugins:{legend:{display:false}},
      scales:{y:{beginAtZero:true,ticks:{stepSize:5},title:{display:true,text:'Số sinh viên'}}}
    }
  });
}else{
  document.getElementById('chartDRL').outerHTML="<div class='text-center text-muted'>Chưa có dữ liệu rèn luyện.</div>";
}
</script>
</body>
</html>
