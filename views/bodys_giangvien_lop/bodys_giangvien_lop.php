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
   1. TIỆN ÍCH
=========================== */
function sanitize_ident($s) {
  // chỉ cho chữ, số, gạch dưới để chống tiêm tên bảng
  return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $s ?? ''));
}
function table_exists(PDO $conn, string $table): bool {
  $stmt = $conn->query("SHOW TABLES LIKE " . $conn->quote($table));
  return $stmt && $stmt->rowCount() > 0;
}
function column_exists(PDO $conn, string $table, string $col): bool {
  $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
  $stmt->execute([$col]);
  return $stmt->rowCount() > 0;
}

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
   4. BỘ LỌC HỌC KỲ + NĂM HỌC
=========================== */
$HOC_KY_ALLOWED = ['I','II','Hè'];
$hoc_ky = $_GET['hoc_ky'] ?? '';
$nam_hoc = $_GET['nam_hoc'] ?? ''; // dạng "2024-2025"
if ($hoc_ky && !in_array($hoc_ky, $HOC_KY_ALLOWED, true)) $hoc_ky = '';
if ($nam_hoc && !preg_match('/^\d{4}\-\d{4}$/', $nam_hoc)) $nam_hoc = '';

/* ===========================
   5. TÍNH SĨ SỐ & TRẠNG THÁI DRL / DUYỆT LỚP
=========================== */
$APPROVAL_COLS = ['duyet_lop','duyet_gv','trang_thai_duyet']; // các tên cột có thể có
$chartData = ['I'=>0, 'II'=>0, 'Hè'=>0];

foreach ($ds_lop as &$lop) {
  // --- Tên bảng sinh viên & DRL (theo khoa/lớp)
  $tbl_sv  = "sv_" . sanitize_ident($lop['ma_khoa']) . "_" . sanitize_ident($lop['ma_lop']);
  $tbl_drl = "phieu_ren_luyen_" . sanitize_ident($lop['ma_khoa']) . "_" . sanitize_ident($lop['ma_lop']);

  // --- Sĩ số
  if (table_exists($conn, $tbl_sv)) {
    $lop['si_so'] = (int)$conn->query("SELECT COUNT(*) FROM `$tbl_sv`")->fetchColumn();
  } else {
    $lop['si_so'] = 0;
  }

  // --- Mặc định trạng thái
  $lop['trang_thai_drl'] = '<span class="text-muted">Chưa có bảng DRL</span>';
  $lop['duyet_lop']      = '<span class="text-muted">—</span>';
  $lop['co_the_duyet']   = false; // hiển thị nút duyệt / chi tiết

  if (!table_exists($conn, $tbl_drl)) continue;

  // Lọc theo kỳ/năm nếu người dùng đã chọn; nếu chưa chọn thì tổng hợp tất cả để hiển thị trạng thái khái quát
  $where = [];
  $args  = [];
  if ($hoc_ky) { $where[] = "hoc_ky = ?"; $args[] = $hoc_ky; }
  if ($nam_hoc){ $where[] = "nam_hoc = ?"; $args[] = $nam_hoc; }
  $where_sql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

  // Số lượng bản ghi DRL (đã tự đánh giá)
  $stmt = $conn->prepare("SELECT COUNT(*) FROM `$tbl_drl` $where_sql");
  $stmt->execute($args);
  $so_danhgia = (int)$stmt->fetchColumn();

  if ($so_danhgia > 0) {
    $lop['trang_thai_drl'] = "<span class='text-success fw-semibold'>Đã có $so_danhgia SV đánh giá</span>";
    $lop['co_the_duyet'] = true;

    // Gộp vào dữ liệu biểu đồ theo học kỳ (chỉ cộng khi có filter rõ ràng hoặc lấy tất cả)
    if (!$hoc_ky) {
      // đếm theo từng kỳ cho lớp này
      $st = $conn->query("SELECT hoc_ky, COUNT(*) so_luong FROM `$tbl_drl` GROUP BY hoc_ky");
      while ($r = $st->fetch(PDO::FETCH_ASSOC)) {
        $ky = $r['hoc_ky'];
        if (isset($chartData[$ky])) $chartData[$ky] += (int)$r['so_luong'];
      }
    } else {
      // đã chọn 1 kỳ, cộng theo kỳ đó
      if (isset($chartData[$hoc_ky])) $chartData[$hoc_ky] += $so_danhgia;
    }

    // --- Xác định trạng thái "tập thể lớp đã duyệt chưa"
    // Ưu tiên: có cột duyet_lop (một cờ lớp) -> kiểm tra >=1 bản ghi = 1
    $approvalFlagFound = false;
    foreach ($APPROVAL_COLS as $col) {
      if (column_exists($conn, $tbl_drl, $col)) {
        $approvalFlagFound = true;

        // Nếu là cờ cấp từng SV (duyet_gv / trang_thai_duyet), xem tất cả SV của kỳ-năm đều đã 1 chưa
        // Nếu là cờ lớp (duyet_lop) – thường đồng nhất – chỉ cần có 1 bản ghi =1 là xem như lớp đã “bấm duyệt”
        $isClassFlag = ($col === 'duyet_lop');

        if ($isClassFlag) {
          $sql = "SELECT MAX(CASE WHEN `$col`=1 THEN 1 ELSE 0 END) AS approved FROM `$tbl_drl` $where_sql";
          $st  = $conn->prepare($sql); $st->execute($args);
          $approved = (int)$st->fetchColumn() === 1;
        } else {
          // kiểm tra % duyệt
          $sql = "SELECT COUNT(*) total,
                         SUM(CASE WHEN `$col`=1 THEN 1 ELSE 0 END) approved
                  FROM `$tbl_drl` $where_sql";
          $st  = $conn->prepare($sql); $st->execute($args);
          $row = $st->fetch(PDO::FETCH_ASSOC);
          $total = (int)$row['total']; $appr = (int)$row['approved'];
          $approved = ($total > 0 && $appr === $total); // 100% SV đã duyệt
          // Nếu muốn chỉ cần >=1 SV duyệt coi là “đang duyệt”, bạn có thể thay điều kiện theo nhu cầu.
        }

        $lop['duyet_lop'] = $approved
          ? "<span class='badge bg-success'>✅ Đã duyệt tập thể</span>"
          : "<span class='badge bg-warning text-dark'>⏳ Chưa duyệt</span>";
        break;
      }
    }
    if (!$approvalFlagFound) {
      // không có cột duyệt nào — hiển thị trung tính
      $lop['duyet_lop'] = "<span class='text-muted'>Không có cột duyệt</span>";
    }
  } else {
    $lop['trang_thai_drl'] = "<span class='text-danger'>Chưa có sinh viên đánh giá</span>";
    $lop['duyet_lop']      = "<span class='text-muted'>—</span>";
    $lop['co_the_duyet']   = false;
  }
}
unset($lop); // break reference

// đảm bảo đủ key cho biểu đồ
foreach (['I','II','Hè'] as $k) if (!isset($chartData[$k])) $chartData[$k] = 0;
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
:root { --brand:#004aad; --bg:#f6f8fc; }
body { background:var(--bg); font-family:'Segoe UI',system-ui,-apple-system,sans-serif; color:#333; }
.card { border:none; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,.06); background:#fff; }
.page-title { font-weight:700; color:var(--brand); }
.quick-card{display:block; background:#fff; border-radius:12px; padding:18px; text-align:center; color:#333; text-decoration:none; box-shadow:0 2px 6px rgba(0,0,0,.08); transition:all .25s;}
.quick-card:hover{transform:translateY(-3px); box-shadow:0 6px 18px rgba(0,0,0,.12);}
.quick-card i{font-size:1.8rem; margin-bottom:.5rem;}
.table-hover tbody tr:hover { background:#f4f8ff; }
.badge { font-weight:600; }
</style>
</head>
<body>
<div class="container-xxl py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="page-title mb-0"><i class="bi bi-person-workspace me-2"></i>BẢNG ĐIỀU KHIỂN GIẢNG VIÊN</h4>
    <a href="<?= BASE_URL ?>index.php?route=logout" class="btn btn-danger btn-sm">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <!-- THÔNG TIN GIẢNG VIÊN -->
  <div class="card p-4 mb-4">
    <div class="row g-3 align-items-center">
      <div class="col-md-3 text-center">
        <img src="<?= htmlspecialchars($giangvien['avatar'] ?? BASE_URL.'assets/images/avatar_default.png') ?>" class="rounded-circle mb-2" width="120" height="120" alt="avatar">
        <h5 class="mb-1"><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>
        <span class="badge bg-primary"><?= htmlspecialchars($giangvien['chuc_danh'] ?? 'Giảng viên') ?></span>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1"><b>Mã GV:</b> <?= htmlspecialchars($giangvien['ma_gv']) ?></p>
            <p class="mb-1"><b>Giới tính:</b> <?= htmlspecialchars($giangvien['gioi_tinh'] ?? '') ?></p>
            <p class="mb-1"><b>Ngày sinh:</b> <?= !empty($giangvien['ngay_sinh']) ? date('d/m/Y', strtotime($giangvien['ngay_sinh'])) : '' ?></p>
            <p class="mb-1"><b>Học hàm/Học vị:</b> <?= htmlspecialchars(trim(($giangvien['hoc_ham'] ?? '').' - '.($giangvien['hoc_vi'] ?? '')), ENT_QUOTES) ?></p>
          </div>
          <div class="col-md-6">
            <p class="mb-1"><b>Bộ môn:</b> <?= htmlspecialchars($giangvien['bo_mon'] ?? '') ?></p>
            <p class="mb-1"><b>Khoa:</b> <?= htmlspecialchars($giangvien['ma_khoa'] ?? '') ?></p>
            <p class="mb-1"><b>Email:</b> <?= htmlspecialchars($giangvien['email'] ?? '') ?></p>
            <p class="mb-1"><b>SĐT:</b> <?= htmlspecialchars($giangvien['sdt'] ?? '') ?></p>
          </div>
        </div>
        <div class="mt-2"><b>Nhiệm vụ:</b> <?= htmlspecialchars($giangvien['nhiem_vu'] ?? '') ?></div>
      </div>
    </div>
  </div>

  <!-- CHỨC NĂNG NHANH -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_lop" class="quick-card">
        <i class="bi bi-people-fill text-primary"></i>
        <h6 class="mb-1">Danh sách lớp phụ trách</h6>
        <p class="text-muted mb-0">Xem thông tin lớp và sinh viên</p>
      </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" class="quick-card">
        <i class="bi bi-clipboard-check-fill text-success"></i>
        <h6 class="mb-1">Quản lý điểm rèn luyện</h6>
        <p class="text-muted mb-0">Duyệt và thống kê DRL sinh viên</p>
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
      <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_hoso" class="quick-card">
        <i class="bi bi-person-badge-fill text-info"></i>
        <h6 class="mb-1">Hồ sơ cá nhân</h6>
        <p class="text-muted mb-0">Cập nhật và chỉnh sửa thông tin</p>
      </a>
    </div>
  </div>

  <!-- BỘ LỌC KỲ & NĂM -->
  <div class="card p-3 mb-3">
    <form method="get" class="d-flex flex-wrap align-items-center gap-2">
      <input type="hidden" name="route" value="bodys_giangvien_dashboard">
      <select name="hoc_ky" class="form-select form-select-sm" style="width:130px;">
        <option value="">Học kỳ</option>
        <?php foreach ($HOC_KY_ALLOWED as $k): ?>
          <option value="<?= $k ?>" <?= $hoc_ky===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
      <select name="nam_hoc" class="form-select form-select-sm" style="width:170px;">
        <option value="">Năm học</option>
        <?php
          $startY = 2020; $endY = date('Y')+1;
          for($y=$startY;$y<=$endY;$y++):
            $val = $y . '-' . ($y+1);
        ?>
          <option value="<?= $val ?>" <?= $nam_hoc===$val?'selected':'' ?>><?= $val ?></option>
        <?php endfor; ?>
      </select>
      <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Lọc</button>
      <?php if ($hoc_ky || $nam_hoc): ?>
        <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>index.php?route=bodys_giangvien_dashboard">
          Xóa lọc
        </a>
      <?php endif; ?>
      <div class="ms-auto small text-muted">
        <?= $hoc_ky?("Học kỳ: <b>$hoc_ky</b> &nbsp;"):"" ?>
        <?= $nam_hoc?("Năm học: <b>$nam_hoc</b>"):"" ?>
      </div>
    </form>
  </div>

  <!-- DANH SÁCH LỚP -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h5 class="fw-bold text-primary mb-0"><i class="bi bi-mortarboard-fill me-2"></i>Lớp phụ trách</h5>
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
            <th>Trạng thái DRL</th>
            <th>Duyệt tập thể lớp</th>
            <th>Thao tác</th>
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
            <td><?= $lop['trang_thai_drl'] ?></td>
            <td><?= $lop['duyet_lop'] ?></td>
            <td class="text-nowrap">
              <?php if (!empty($lop['co_the_duyet'])): ?>
                <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl_duyet&lop=<?= urlencode($lop['ma_lop']) ?>&hoc_ky=<?= urlencode($hoc_ky) ?>&nam_hoc=<?= urlencode($nam_hoc) ?>"
                   class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle"></i> Duyệt</a>
                <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl_chitiet&lop=<?= urlencode($lop['ma_lop']) ?>&hoc_ky=<?= urlencode($hoc_ky) ?>&nam_hoc=<?= urlencode($nam_hoc) ?>"
                   class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Chi tiết</a>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="8" class="text-muted py-3">Chưa có lớp phụ trách.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BIỂU ĐỒ DRL -->
  <div class="card p-4">
    <h5 class="fw-bold text-primary mb-3">
      <i class="bi bi-bar-chart-line-fill me-2"></i>Thống kê số lượt đánh giá DRL theo học kỳ
    </h5>
    <canvas id="chartDRL" height="120"></canvas>
  </div>

</div>

<script>
document.getElementById('searchLop')?.addEventListener('keyup', e=>{
  const kw = e.target.value.toLowerCase();
  document.querySelectorAll('#lopTable tbody tr').forEach(r=>{
    r.style.display = r.innerText.toLowerCase().includes(kw) ? '' : 'none';
  });
});

const chartData = <?= json_encode($chartData, JSON_UNESCAPED_UNICODE) ?>;
const ctx = document.getElementById('chartDRL').getContext('2d');
if (Object.keys(chartData).length) {
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: Object.keys(chartData),
      datasets: [{
        label: 'Số lượt đánh giá',
        data: Object.values(chartData),
        borderRadius: 8,
        backgroundColor: ['#0d6efd','#198754','#ffc107']
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 5 }, title: { display: true, text: 'Số lượt' } }
      }
    }
  });
} else {
  document.getElementById('chartDRL').outerHTML =
    "<div class='text-center text-muted'>Chưa có dữ liệu rèn luyện.</div>";
}
</script>
</body>
</html>
