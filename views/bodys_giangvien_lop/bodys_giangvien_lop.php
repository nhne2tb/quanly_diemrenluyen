<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

/* ===========================
   0. BẢO MẬT & KẾT NỐI DB
=========================== */
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login'); exit;
}
$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$ma_gv = $_SESSION['user']['id'] ?? '';

/* ===========================
   2. LẤY THÔNG TIN GIẢNG VIÊN
=========================== */
$stmtGV = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ?");
$stmtGV->execute([$ma_gv]);
$giangvien = $stmtGV->fetch(PDO::FETCH_ASSOC);
if (!$giangvien) { session_destroy(); header('Location: ' . BASE_URL . 'index.php?route=bodys_login'); exit; }

/* ===========================
   3. LẤY DANH SÁCH LỚP PHỤ TRÁCH
=========================== */
$stmtLop = $conn->prepare("SELECT * FROM tb_lop WHERE ma_gv = ? ORDER BY nien_khoa, ma_lop");
$stmtLop->execute([$ma_gv]);
$ds_lop = $stmtLop->fetchAll(PDO::FETCH_ASSOC);

/* ===========================
   4. TÍNH SĨ SỐ (KHÔNG LỌC, KHÔNG DUYỆT)
=========================== */
function sanitize_ident($s) {
  return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $s ?? ''));
}
function table_exists(PDO $conn, string $table): bool {
  $stmt = $conn->query("SHOW TABLES LIKE " . $conn->quote($table));
  return $stmt && $stmt->rowCount() > 0;
}

foreach ($ds_lop as &$lop) {
  $tbl_sv  = "sv_" . sanitize_ident($lop['ma_khoa']) . "_" . sanitize_ident($lop['ma_lop']);

  // Sĩ số
  if (table_exists($conn, $tbl_sv)) {
    $lop['si_so'] = (int)$conn->query("SELECT COUNT(*) FROM `$tbl_sv`")->fetchColumn();
  } else {
    $lop['si_so'] = 0;
  }
}
unset($lop);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bảng điều khiển Giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root { --brand:#004aad; --bg:#f6f8fc; }
body { background:var(--bg); font-family:'Segoe UI',system-ui,-apple-system,sans-serif; color:#333; }
.card { border:none; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,.06); background:#fff; }
.page-title { font-weight:700; color:var(--brand); }
.quick-card{display:block; background:#fff; border-radius:12px; padding:18px; text-align:center; color:#333; text-decoration:none; box-shadow:0 2px 6px rgba(0,0,0,.08); transition:all .25s;}
.quick-card:hover{transform:translateY(-3px); box-shadow:0 6px 18px rgba(0,0,0,.12);}
.quick-card i{font-size:1.8rem; margin-bottom:.5rem;}
.table-hover tbody tr:hover { background:#f4f8ff; }

.logout-top-left, 
.card a[href*="logout"] {
    position: relative !important;
    z-index: 99999 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
}

</style>
</head>
<body>
<div class="container-xxl py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
<h4 class="page-title mb-0">BẢNG ĐIỀU KHIỂN GIẢNG VIÊN</h4>


  </div>

<!-- THÔNG TIN GIẢNG VIÊN -->
<div class="card p-4 mb-4">
<a href="<?= BASE_URL ?>index.php?route=logout"
   class="d-inline-flex align-items-center"
   style="color:#004aad; font-weight:600; text-decoration:none; gap:4px;">
    <i class="bi bi-box-arrow-right" style="font-size:1rem;"></i>
    Đăng xuất
</a>
    <div class="row g-4 align-items-center">

        <!-- BÊN TRÁI: Avatar + Tên + Chức danh -->
        <div class="col-md-3 text-center">

            <img src="<?= htmlspecialchars($giangvien['avatar'] ?? BASE_URL.'assets/images/avatar_default.png') ?>"
                class="rounded-circle mb-2"
                width="120" height="120">

            <h5 class="fw-bold mb-1"><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>

            <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem;">
                <?= htmlspecialchars($giangvien['chuc_danh'] ?? 'Giảng viên chính') ?>
            </span>

        </div>

        <!-- BÊN PHẢI: BIỂU ĐỒ -->
        <div class="col-md-9">
            <h6 class="fw-bold mb-3" style="color:#004aad;">Thống kê sinh viên đã đánh giá DRL</h6>
            <canvas id="chartDRL_GV" height="130"></canvas>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ====== TẠO DỮ LIỆU BIỂU ĐỒ THEO LỚP ======
<?php
$chart_labels = [];
$chart_values = [];

foreach ($ds_lop as $lop) {
    $tbl_drl = "phieu_ren_luyen_" . sanitize_ident($lop['ma_khoa']) . "_" . sanitize_ident($lop['ma_lop']);

    $dem = 0;
    if (table_exists($conn, $tbl_drl)) {
        $dem = (int)$conn->query("SELECT COUNT(*) FROM `$tbl_drl`")->fetchColumn();
    }

    $chart_labels[] = $lop['ma_lop'];
    $chart_values[] = $dem;
}
?>

const gv_labels = <?= json_encode($chart_labels) ?>;
const gv_values = <?= json_encode($chart_values) ?>;

const ctxGV = document.getElementById('chartDRL_GV').getContext('2d');

new Chart(ctxGV, {
    type: 'bar',
    data: {
        labels: gv_labels,
        datasets: [{
            label: "Số SV đã đánh giá",
            data: gv_values,
            backgroundColor: '#004aad',
            borderRadius: 10,
        }]
    },
options: {
    plugins: { legend: { display: false } },
    scales: {
        y: { 
            beginAtZero: true,
            ticks: {
                stepSize: 1,
                precision: 0
            }
        },
        x: { ticks: { font: { weight: 'bold' } } }
    }
}

});
</script>


  <!-- CHỨC NĂNG NHANH -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_dsl" class="quick-card">
        <i class="bi bi-people-fill text-primary"></i>
        <h6 class="mb-1">Danh sách lớp phụ trách</h6>
        <p class="text-muted mb-0">Xem thông tin lớp và sinh viên</p>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" class="quick-card">
        <i class="bi bi-clipboard-check-fill text-success"></i>
        <h6 class="mb-1">Quản lý điểm rèn luyện</h6>
        <p class="text-muted mb-0">Thống kê DRL sinh viên</p>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=bodys_thongbao" class="quick-card">
        <i class="bi bi-megaphone text-warning"></i>
        <h6 class="mb-1">Thông báo từ Khoa</h6>
        <p class="text-muted mb-0">Cập nhật thông tin mới nhất</p>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=hoso_giangvien" class="quick-card">
        <i class="bi bi-person-badge-fill text-info"></i>
        <h6 class="mb-1">Hồ sơ cá nhân</h6>
        <p class="text-muted mb-0">Cập nhật và chỉnh sửa thông tin</p>
      </a>
    </div>
  </div>

  <!-- DANH SÁCH LỚP (KHÔNG DUYỆT – KHÔNG LỌC) -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
<h5 class="page-title mb-0">Lớp phụ trách</h5>
      <div class="input-group input-group-sm" style="width:240px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchLop" class="form-control border-start-0 shadow-none" placeholder="Tìm lớp...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center" id="lopTable">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Sĩ số</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($ds_lop)): $i=0; foreach ($ds_lop as $lop): ?>
          <tr>
            <td><?= ++$i ?></td>
            <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
            <td><?= htmlspecialchars($lop['ten_lop']) ?></td>
            <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
            <td><span class="badge bg-secondary"><?= (int)$lop['si_so'] ?></span></td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="5" class="text-muted py-3">Chưa có lớp phụ trách.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
document.getElementById('searchLop')?.addEventListener('keyup', e=>{
  const kw = e.target.value.toLowerCase();
  document.querySelectorAll('#lopTable tbody tr').forEach(r=>{
    r.style.display = r.innerText.toLowerCase().includes(kw) ? '' : 'none';
  });
});



</script>

</body>
</html>
