<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

/* ==========================================
   0) BẢO MẬT, KẾT NỐI DB, CẤU HÌNH LINH HOẠT
========================================== */
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login'); exit;
}
$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ma_gv = $_SESSION['user']['id'] ?? '';

// Cấu hình mềm (đổi theo DB thực tế nếu cần)
$APPROVAL_COLS_TRY = ['trang_thai','duyet_lop','duyet_gv','trang_thai_duyet'];
$SCORE_COLS_TRY    = ['diem_sv','diem_lop','diem_cvht','diem_khoa','tong_diem','xep_loai'];

// CSRF token đơn giản cho POST action
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$CSRF = $_SESSION['csrf_token'];

/* ==========================================
   1) TIỆN ÍCH
========================================== */
function sanitize_ident($s) {
  return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $s ?? ''));
}
function table_exists(PDO $conn, string $table): bool {
  $stmt = $conn->query("SHOW TABLES LIKE " . $conn->quote($table));
  return $stmt && $stmt->rowCount() > 0;
}
function column_exists(PDO $conn, string $table, string $col): bool {
  $st = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
  $st->execute([$col]);
  return $st->rowCount() > 0;
}
function detect_first_existing_col(PDO $conn, string $table, array $candidates) {
  foreach ($candidates as $c) if (column_exists($conn, $table, $c)) return $c;
  return null;
}

/* ==========================================
   2) TẢI DANH SÁCH LỚP PHỤ TRÁCH & GIẢNG VIÊN
========================================== */
$stmtGV = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ?");
$stmtGV->execute([$ma_gv]);
$giangvien = $stmtGV->fetch(PDO::FETCH_ASSOC);

$stmtLop = $conn->prepare("SELECT * FROM tb_lop WHERE ma_gv = ? ORDER BY nien_khoa, ma_lop");
$stmtLop->execute([$ma_gv]);
$ds_lop = $stmtLop->fetchAll(PDO::FETCH_ASSOC);

/* ==========================================
   3) NHẬN FILTER: KỲ, NĂM, LỚP
========================================== */
$HOC_KY_ALLOWED = ['I','II','Hè'];
$hoc_ky = $_GET['hoc_ky'] ?? '';
$nam_hoc = $_GET['nam_hoc'] ?? '';
$lop_chon = $_GET['lop'] ?? '';

if ($hoc_ky && !in_array($hoc_ky, $HOC_KY_ALLOWED, true)) $hoc_ky = '';
// Năm học trong DB tách làm nam_bd và nam_kt => không cần regex

$lop_chon = sanitize_ident($lop_chon);

// Xác định lớp đầu tiên nếu chưa chọn
if (!$lop_chon && !empty($ds_lop)) $lop_chon = sanitize_ident($ds_lop[0]['ma_lop'] ?? '');

/* ==========================================
   4) XỬ LÝ ACTION DUYỆT (POST)
========================================== */
$flash_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($CSRF, $_POST['csrf'] ?? '')) {
    $flash_msg = 'Yêu cầu không hợp lệ (CSRF).';
  } else {
    $act = $_POST['act'] ?? '';
    $ma_lop_post = sanitize_ident($_POST['lop'] ?? '');
    $ma_khoa_post = '';
    // tìm ma_khoa tương ứng lớp
    foreach ($ds_lop as $l) if (sanitize_ident($l['ma_lop']) === $ma_lop_post) { $ma_khoa_post = sanitize_ident($l['ma_khoa']); break; }
    if ($ma_khoa_post) {
      $tbl_drl = "phieu_ren_luyen_{$ma_khoa_post}_{$ma_lop_post}";
      if (table_exists($conn, $tbl_drl)) {
        $approval_col = detect_first_existing_col($conn, $tbl_drl, $APPROVAL_COLS_TRY) ?: null;
        if ($approval_col) {
          // where filter
          $where = []; $args = [];
          if (!empty($_POST['hoc_ky'])) { $where[] = "hoc_ky = ?"; $args[] = $_POST['hoc_ky']; }
          if (!empty($_POST['nam_hoc'])){ $where[] = "nam_hoc = ?"; $args[] = $_POST['nam_hoc']; }
          if (!empty($_POST['mssv']))   { $where[] = "mssv = ?";   $args[] = $_POST['mssv']; }
          $where_sql = $where ? ("WHERE ".implode(" AND ", $where)) : "";

          if ($act === 'approve_all' || $act === 'unapprove_all' || $act === 'approve_one' || $act === 'unapprove_one') {
            $val = ($act === 'approve_all' || $act === 'approve_one') ? 1 : 0;
            $sql = "UPDATE `$tbl_drl` SET `$approval_col` = :val $where_sql";
            $st = $conn->prepare($sql);
            $st->bindValue(':val', $val, PDO::PARAM_INT);
            foreach ($args as $k=>$v) $st->bindValue($k+1, $v);
            $st->execute();
            $flash_msg = ($val===1) ? 'Đã duyệt thành công.' : 'Đã hoàn tác duyệt.';
          }
        } else {
          $flash_msg = 'Không tìm thấy cột duyệt trong bảng DRL.';
        }
      } else {
        $flash_msg = 'Chưa có bảng DRL cho lớp này.';
      }
    } else {
      $flash_msg = 'Không xác định được lớp.';
    }
  }
}

/* ==========================================
   5) LOAD DỮ LIỆU THEO LỚP + KỲ + NĂM
========================================== */
$info_lop = null;
foreach ($ds_lop as $l) if (sanitize_ident($l['ma_lop']) === $lop_chon) { $info_lop = $l; break; }

$tbl_sv  = null; $tbl_drl = null;
$approval_col = null;
$score_cols   = [];
$rows = [];
$summary = ['total'=>0,'approved'=>0,'avg'=>null,'class_approved'=>null];

if ($info_lop) {
  $ma_khoa = sanitize_ident($info_lop['ma_khoa']);
  $ma_lop  = sanitize_ident($info_lop['ma_lop']);
  $tbl_sv  = "sv_{$ma_khoa}_{$ma_lop}";
  $tbl_drl = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";

  $has_sv  = table_exists($conn, $tbl_sv);
  $has_drl = table_exists($conn, $tbl_drl);

  if ($has_drl) {
    // phát hiện cột duyệt và cột điểm
    $approval_col = detect_first_existing_col($conn, $tbl_drl, $APPROVAL_COLS_TRY);
    foreach ($SCORE_COLS_TRY as $c) if (column_exists($conn, $tbl_drl, $c)) $score_cols[] = $c;

    // where filter
// where filter
$where = []; 
$args = [];

// Lọc theo học kỳ
if ($hoc_ky)  { 
  $where[] = "d.hoc_ky = ?";  
  $args[] = $hoc_ky; 
}

// Lọc theo năm học (ghép từ nam_bd và nam_kt)
if ($nam_hoc) { 
  // Nếu năm học có dạng 2020-2021, tách ra 2 phần
  if (strpos($nam_hoc, '-') !== false) {
    [$nam_bd, $nam_kt] = explode('-', $nam_hoc);
    $where[] = "(d.nam_bd = ? AND d.nam_kt = ?)";
    $args[] = trim($nam_bd);
    $args[] = trim($nam_kt);
  } else {
    // Nếu chỉ chọn 1 năm (phòng trường hợp lỗi)
    $where[] = "(d.nam_bd = ? OR d.nam_kt = ?)";
    $args[] = $nam_hoc;
    $args[] = $nam_hoc;
  }
}

// Tạo câu WHERE cuối cùng
$where_sql = $where ? ("WHERE ".implode(" AND ", $where)) : "";


    // lấy dữ liệu, join bảng SV nếu có để hiện họ tên
    if ($has_sv && column_exists($conn, $tbl_sv, 'mssv') && column_exists($conn, $tbl_sv, 'ho_ten')) {
      $sql = "SELECT d.*, s.ho_ten
              FROM `$tbl_drl` d
              LEFT JOIN `$tbl_sv` s ON s.mssv = d.mssv
              $where_sql
              ORDER BY d.mssv";
    } else {
      $sql = "SELECT d.* FROM `$tbl_drl` d $where_sql ORDER BY d.mssv";
    }
    $st = $conn->prepare($sql); $st->execute($args);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    // Thống kê
    $summary['total'] = count($rows);
    if ($approval_col) {
      $appr = 0;
      foreach ($rows as $r) if ((int)($r[$approval_col] ?? 0) === 1) $appr++;
      $summary['approved'] = $appr;
      // trạng thái tập thể lớp: nếu có cột duyet_lop => lấy MAX; nếu cột duyệt theo SV => tất cả = 1
      if ($approval_col === 'duyet_lop') {
        $st2 = $conn->prepare("SELECT MAX(CASE WHEN `$approval_col`=1 THEN 1 ELSE 0 END) FROM `$tbl_drl` $where_sql");
        $st2->execute($args);
        $summary['class_approved'] = ((int)$st2->fetchColumn() === 1);
      } else {
        $summary['class_approved'] = ($summary['total']>0 && $appr === $summary['total']);
      }
    }

    // trung bình (ưu tiên tong_diem nếu có; không thì trung bình các cột điểm số hiện hữu dạng float)
    if (in_array('tong_diem', $score_cols, true)) {
      $st3 = $conn->prepare("SELECT AVG(COALESCE(tong_diem,0)) FROM `$tbl_drl` d $where_sql");
      $st3->execute($args);
      $summary['avg'] = round((float)$st3->fetchColumn(), 2);
    } else {
      // gom các cột điểm số hiện hữu (loại xep_loai)
      $score_num_cols = array_values(array_filter($score_cols, fn($c)=>$c !== 'xep_loai'));
      if ($score_num_cols) {
        $avg_expr = [];
        foreach ($score_num_cols as $c) $avg_expr[] = "COALESCE(`$c`,0)";
        // AVG của tổng các cột / số cột
        $expr = "AVG((" . implode('+',$avg_expr) . ")/" . count($score_num_cols) . ")";
        $st4 = $conn->prepare("SELECT ROUND($expr, 2) FROM `$tbl_drl` d $where_sql");
        $st4->execute($args);
        $summary['avg'] = (float)$st4->fetchColumn();
      }
    }
  }
}

/* ==========================================
   6) GIAO DIỆN
========================================== */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Quản lý điểm rèn luyện</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root { --brand:#004aad; --bg:#f6f8fc; }
body { background:var(--bg); font-family:'Segoe UI',system-ui,-apple-system,sans-serif; color:#333; }
.card { border:none; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,.06); background:#fff; }
.badge { font-weight:600; }
.table-hover tbody tr:hover { background:#f4f8ff; }
</style>
</head>
<body>
<div class="container-xxl py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0 text-primary"><i class="bi bi-clipboard-check-fill me-2"></i>Quản lý điểm rèn luyện</h4>
    <div>
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>index.php?route=dashboard">
        <i class="bi bi-arrow-left"></i> Về Dashboard
      </a>
    </div>
  </div>

  <?php if (!empty($flash_msg)): ?>
    <div class="alert alert-info py-2"><?= htmlspecialchars($flash_msg) ?></div>
  <?php endif; ?>

  <!-- BỘ LỌC -->
  <div class="card p-3 mb-3">
    <form method="get" class="row g-2 align-items-end">
      <input type="hidden" name="route" value="bodys_giangvien_drl">
      <div class="col-12 col-md-3">
        <label class="form-label mb-1">Lớp phụ trách</label>
        <select name="lop" class="form-select form-select-sm">
          <?php foreach ($ds_lop as $l): ?>
            <option value="<?= htmlspecialchars($l['ma_lop']) ?>" <?= sanitize_ident($l['ma_lop'])===$lop_chon?'selected':'' ?>>
              <?= htmlspecialchars($l['ma_lop'].' - '.$l['ten_lop']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label mb-1">Học kỳ</label>
        <select name="hoc_ky" class="form-select form-select-sm">
          <option value="">—</option>
          <?php foreach ($HOC_KY_ALLOWED as $k): ?>
            <option value="<?= $k ?>" <?= $hoc_ky===$k?'selected':'' ?>><?= $k ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label mb-1">Năm học</label>
        <select name="nam_hoc" class="form-select form-select-sm">
          <option value="">—</option>
          <?php
            for($y=2020;$y<=date('Y')+2;$y++):
              $val = $y.'-'.($y+1);
          ?>
            <option value="<?= $val ?>" <?= $nam_hoc===$val?'selected':'' ?>><?= $val ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-12 col-md-4">
        <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Lọc</button>
        <?php if ($hoc_ky || $nam_hoc): ?>
          <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl&lop=<?= urlencode($lop_chon) ?>">Xóa lọc</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <?php if (!$info_lop): ?>
    <div class="alert alert-warning">Chưa chọn lớp hợp lệ.</div>
  <?php else: ?>

  <!-- THỐNG KÊ NHANH -->
  <div class="row g-3 mb-3">
    <div class="col-sm-6 col-md-3">
      <div class="card p-3">
        <div class="text-muted small">Tổng SV trong DRL</div>
        <div class="fs-4 fw-bold"><?= (int)$summary['total'] ?></div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card p-3">
        <div class="text-muted small">Đã duyệt</div>
        <div class="fs-4 fw-bold text-success"><?= (int)$summary['approved'] ?></div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card p-3">
        <div class="text-muted small">Trạng thái tập thể</div>
        <div class="fs-6 fw-semibold">
          <?php
            if ($approval_col === null) echo "<span class='text-muted'>Không có cột duyệt</span>";
            else echo ($summary['class_approved'] ? "<span class='badge bg-success'>✅ Đã duyệt</span>" : "<span class='badge bg-warning text-dark'>⏳ Chưa duyệt</span>");
          ?>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card p-3">
        <div class="text-muted small">Điểm trung bình</div>
        <div class="fs-4 fw-bold"><?= $summary['avg'] !== null ? $summary['avg'] : '<span class="text-muted">—</span>' ?></div>
      </div>
    </div>
  </div>

  <!-- NÚT DUYỆT NHANH -->
  <div class="card p-3 mb-3">
    <form method="post" class="d-flex flex-wrap gap-2 align-items-center">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($CSRF) ?>">
      <input type="hidden" name="lop" value="<?= htmlspecialchars($info_lop['ma_lop']) ?>">
      <input type="hidden" name="hoc_ky" value="<?= htmlspecialchars($hoc_ky) ?>">
      <input type="hidden" name="nam_hoc" value="<?= htmlspecialchars($nam_hoc) ?>">
      <button name="act" value="approve_all" class="btn btn-success btn-sm" <?= !$approval_col?'disabled':'' ?>>
        <i class="bi bi-check-circle"></i> Duyệt tất cả
      </button>
      <button name="act" value="unapprove_all" class="btn btn-outline-danger btn-sm" <?= !$approval_col?'disabled':'' ?>>
        <i class="bi bi-x-circle"></i> Hoàn tác tất cả
      </button>
      <span class="ms-auto small text-muted">
        Bảng DRL: <code><?= htmlspecialchars($tbl_drl) ?></code>
        <?= $approval_col ? " • Cột duyệt: <code>".htmlspecialchars($approval_col)."</code>" : " • <span class='text-danger'>Chưa tìm thấy cột duyệt</span>" ?>
      </span>
    </form>
  </div>

  <!-- BẢNG SV -->
  <div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h5 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Danh sách sinh viên</h5>
      <div class="input-group input-group-sm" style="width:260px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchSV" class="form-control border-start-0 shadow-none" placeholder="Tìm MSSV, họ tên...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle" id="svTable">
        <thead class="table-light">
          <tr class="text-center">
            <th>#</th>
            <th>MSSV</th>
            <th>Họ tên</th>
            <th>Học kỳ</th>
            <th>Năm học</th>
            <?php foreach ($SCORE_COLS_TRY as $c): if (in_array($c, $score_cols, true)): ?>
              <th><?= htmlspecialchars(strtoupper($c)) ?></th>
            <?php endif; endforeach; ?>
            <?php if ($approval_col): ?>
              <th>Duyệt</th>
              <th>Thao tác</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
        <?php if ($rows): $i=0; foreach ($rows as $r): ?>
          <tr>
            <td class="text-center"><?= ++$i ?></td>
            <td class="text-center"><code><?= htmlspecialchars($r['mssv'] ?? '') ?></code></td>
            <td><?= htmlspecialchars($r['ho_ten'] ?? '') ?></td>
            <td class="text-center"><?= htmlspecialchars($r['hoc_ky'] ?? '') ?></td>
<td class="text-center">
  <?= htmlspecialchars(($r['nam_bd'] ?? '') . '-' . ($r['nam_kt'] ?? '')) ?>
</td>
            <?php foreach ($SCORE_COLS_TRY as $c): if (in_array($c, $score_cols, true)): ?>
              <td class="text-center">
                <?php
                  if ($c === 'xep_loai') {
                    echo htmlspecialchars($r['xep_loai'] ?? '');
                  } else {
                    $v = isset($r[$c]) ? (float)$r[$c] : null;
                    echo ($v !== null) ? number_format($v, 2) : '<span class="text-muted">—</span>';
                  }
                ?>
              </td>
            <?php endif; endforeach; ?>
            <?php if ($approval_col): ?>
              <td class="text-center">
                <?php
                  $ok = (int)($r[$approval_col] ?? 0) === 1;
                  echo $ok ? "<span class='badge bg-success'>Đã duyệt</span>" : "<span class='badge bg-warning text-dark'>Chưa</span>";
                ?>
              </td>
              <td class="text-center text-nowrap">
                <form method="post" class="d-inline">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars($CSRF) ?>">
                  <input type="hidden" name="lop" value="<?= htmlspecialchars($info_lop['ma_lop']) ?>">
                  <input type="hidden" name="hoc_ky" value="<?= htmlspecialchars($r['hoc_ky'] ?? '') ?>">
                  <input type="hidden" name="nam_hoc" value="<?= htmlspecialchars($r['nam_hoc'] ?? '') ?>">
                  <input type="hidden" name="mssv"   value="<?= htmlspecialchars($r['mssv'] ?? '') ?>">
                  <?php if ($ok): ?>
                    <button name="act" value="unapprove_one" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                  <?php else: ?>
                    <button name="act" value="approve_one" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></button>
                  <?php endif; ?>
                </form>
                <!-- Ví dụ nút chi tiết (route tùy bạn) -->
                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl_chitiet&lop=<?= urlencode($info_lop['ma_lop']) ?>&mssv=<?= urlencode($r['mssv'] ?? '') ?>&hoc_ky=<?= urlencode($r['hoc_ky'] ?? '') ?>&nam_hoc=<?= urlencode($r['nam_hoc'] ?? '') ?>">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="<?= 6 + count($score_cols) + ($approval_col?2:0) ?>" class="text-center text-muted py-3">Không có dữ liệu DRL phù hợp.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BIỂU ĐỒ NHỎ -->
  <div class="card p-4">
    <h6 class="fw-bold text-primary mb-3">
      <i class="bi bi-bar-chart-line me-2"></i>Phân bố trạng thái duyệt
    </h6>
    <canvas id="chartAppr" height="110"></canvas>
  </div>

  <?php endif; // end if info_lop ?>
</div>

<script>
document.getElementById('searchSV')?.addEventListener('input', (e)=>{
  const kw = e.target.value.toLowerCase();
  document.querySelectorAll('#svTable tbody tr').forEach(tr=>{
    tr.style.display = tr.innerText.toLowerCase().includes(kw) ? '' : 'none';
  });
});

// Chart nhỏ: đã duyệt vs chưa duyệt
<?php
$appr = (int)$summary['approved'];
$not  = max(0, (int)$summary['total'] - $appr);
?>
const apprData = { approved: <?= $appr ?>, pending: <?= $not ?> };
const ctx = document.getElementById('chartAppr').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Đã duyệt', 'Chưa duyệt'],
    datasets: [{ data: [apprData.approved, apprData.pending], borderRadius: 8, backgroundColor: ['#198754','#ffc107'] }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
  }
});
</script>
</body>
</html>
