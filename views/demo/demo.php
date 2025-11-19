<?php
// ====================== CẤU HÌNH & DỮ LIỆU ẢO ======================
header('Content-Type: text/html; charset=utf-8');

// Danh mục lớp (ảo)
$classes = [
  ['id'=>1,'ma_lop'=>'DHSTIN22A','ten_lop'=>'Sư phạm Tin A K22','cvht'=>'ThS. Nguyễn Văn T'],
  ['id'=>2,'ma_lop'=>'DHSTIN22B','ten_lop'=>'Sư phạm Tin B K22','cvht'=>'ThS. Trần Thị M'],
  ['id'=>3,'ma_lop'=>'DHSTOA22A','ten_lop'=>'Sư phạm Toán A K22','cvht'=>'ThS. Lê Hữu K'],
];

// Sinh viên + điểm rèn luyện (ảo)
$students = [
  // class 1
  ['ma_sv'=>'DH22TIN001','ho_ten'=>'Nguyễn Văn A','lop_id'=>1,'tong_diem'=>92,'ghi_chu'=>'Tham gia tình nguyện'],
  ['ma_sv'=>'DH22TIN002','ho_ten'=>'Trần Thị B','lop_id'=>1,'tong_diem'=>78,'ghi_chu'=>'Đủ điều kiện'],
  ['ma_sv'=>'DH22TIN003','ho_ten'=>'Phạm Minh C','lop_id'=>1,'tong_diem'=>66,'ghi_chu'=>'Đi muộn 2 lần'],
  ['ma_sv'=>'DH22TIN004','ho_ten'=>'Lê Quốc D','lop_id'=>1,'tong_diem'=>49,'ghi_chu'=>'Cần cải thiện ý thức'],
  // class 2
  ['ma_sv'=>'DH22TIN101','ho_ten'=>'Võ Ngọc E','lop_id'=>2,'tong_diem'=>88,'ghi_chu'=>'Sinh hoạt đều'],
  ['ma_sv'=>'DH22TIN102','ho_ten'=>'Đặng Hải F','lop_id'=>2,'tong_diem'=>81,'ghi_chu'=>'Hoàn thành tốt'],
  ['ma_sv'=>'DH22TIN103','ho_ten'=>'Ngô Thúy G','lop_id'=>2,'tong_diem'=>58,'ghi_chu'=>'Thiếu minh chứng'],
  // class 3
  ['ma_sv'=>'DH22TOA001','ho_ten'=>'Huỳnh Tấn H','lop_id'=>3,'tong_diem'=>96,'ghi_chu'=>'Xuất sắc'],
  ['ma_sv'=>'DH22TOA002','ho_ten'=>'Bùi Thanh I','lop_id'=>3,'tong_diem'=>72,'ghi_chu'=>'Khá'],
  ['ma_sv'=>'DH22TOA003','ho_ten'=>'Đỗ Mỹ K','lop_id'=>3,'tong_diem'=>34,'ghi_chu'=>'Kém'],
];

// Hàm xếp loại theo tổng điểm
function xep_loai($total) {
  if ($total >= 90) return 'Xuất sắc';
  if ($total >= 80) return 'Tốt';
  if ($total >= 65) return 'Khá';
  if ($total >= 50) return 'Trung bình';
  if ($total >= 35) return 'Yếu';
  return 'Kém';
}

// Filter đầu vào
$lop_id = isset($_GET['lop_id']) ? intval($_GET['lop_id']) : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Xuất CSV nếu yêu cầu
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="thong_ke_drl.csv"');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['STT','Mã SV','Họ tên','Lớp','Tổng điểm','Xếp loại','Ghi chú']);
  $stt = 1;
  foreach ($students as $sv) {
    if ($lop_id && $sv['lop_id'] !== $lop_id) continue;
    if ($q && stripos($sv['ma_sv'].$sv['ho_ten'], $q) === false) continue;
    $lop = array_values(array_filter($classes, fn($c)=>$c['id']===$sv['lop_id']))[0] ?? null;
    fputcsv($out, [
      $stt++,
      $sv['ma_sv'],
      $sv['ho_ten'],
      $lop ? $lop['ma_lop'] : '',
      $sv['tong_diem'],
      xep_loai($sv['tong_diem']),
      $sv['ghi_chu']
    ]);
  }
  fclose($out);
  exit;
}

// Lọc dữ liệu để hiển thị
$view = [];
foreach ($students as $sv) {
  if ($lop_id && $sv['lop_id'] !== $lop_id) continue;
  if ($q && stripos($sv['ma_sv'].$sv['ho_ten'], $q) === false) continue;
  $lop = array_values(array_filter($classes, fn($c)=>$c['id']===$sv['lop_id']))[0] ?? null;
  $view[] = [
    'ma_sv'=>$sv['ma_sv'],
    'ho_ten'=>$sv['ho_ten'],
    'ma_lop'=>$lop ? $lop['ma_lop'] : '',
    'ten_lop'=>$lop ? $lop['ten_lop'] : '',
    'cvht'=>$lop ? $lop['cvht'] : '',
    'tong_diem'=>$sv['tong_diem'],
    'xep_loai'=>xep_loai($sv['tong_diem']),
    'ghi_chu'=>$sv['ghi_chu'],
  ];
}

// Tính nhanh thống kê theo xếp loại
$counts = ['Xuất sắc'=>0,'Tốt'=>0,'Khá'=>0,'Trung bình'=>0,'Yếu'=>0,'Kém'=>0];
foreach ($view as $row) { $counts[$row['xep_loai']]++; }

// ====================== HTML (Bootstrap 5) ======================
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Quản lý điểm rèn luyện – Demo MVC (1 file)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root{
      /* 3 màu chủ đạo: KHÔNG vượt quá 3 */
      --c1:#0d6efd; /* xanh dương (primary) */
      --c2:#20c997; /* xanh ngọc (accent) */
      --c3:#f39c12; /* cam (highlight) */
    }
    body{ background: #f7f8fb; }
    .brand-gradient{
      background: linear-gradient(135deg, var(--c1), var(--c2));
      color:#fff;
    }
    .nav-brand{
      font-weight:700; letter-spacing:.3px;
    }
    .chip{
      display:inline-block; padding:.25rem .5rem; border-radius:1rem; font-size:.85rem; font-weight:600;
      background: rgba(13,110,253,.1); color:var(--c1);
    }
    .chip.orange{ background: rgba(243,156,18,.1); color:var(--c3); }
    .chip.green{ background: rgba(32,201,151,.1); color:var(--c2); }
    .card.kpi{ border:0; box-shadow:0 10px 20px rgba(0,0,0,.05); }
    .kpi .icon-wrap{
      width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;
      background: rgba(13,110,253,.1); color:var(--c1);
    }
    .kpi.orange .icon-wrap{ background: rgba(243,156,18,.1); color:var(--c3); }
    .kpi.green .icon-wrap{ background: rgba(32,201,151,.1); color:var(--c2); }
    .table thead th{ background:#fff; position:sticky; top:0; z-index:2; }
    .btn-primary{ background:var(--c1); border-color:var(--c1); }
    .btn-outline-primary{ color:var(--c1); border-color:var(--c1); }
    .btn-outline-primary:hover{ background:var(--c1); color:#fff; }
    /* Nhãn xếp loại (chỉ 3 màu biến thể) */
    .badge-xl{ font-size:.9rem; padding:.5rem .75rem; }
    .xl-blue{ background:rgba(13,110,253,.12); color:var(--c1); }
    .xl-green{ background:rgba(32,201,151,.12); color:var(--c2); }
    .xl-orange{ background:rgba(243,156,18,.12); color:var(--c3); }
  </style>
</head>
<body>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg brand-gradient">
    <div class="container">
      <a class="navbar-brand nav-brand d-flex align-items-center gap-2" href="#">
        <i class="bi bi-stars"></i> QL Điểm Rèn Luyện
      </a>
      <div class="d-flex align-items-center gap-2">
        <span class="chip">Demo PHP + Bootstrap (1 file)</span>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <!-- BỘ LỌC -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <form class="row g-2 align-items-end" method="get">
          <div class="col-md-3">
            <label class="form-label">Chọn lớp</label>
            <select name="lop_id" class="form-select">
              <option value="0">-- Tất cả lớp --</option>
              <?php foreach($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $lop_id===$c['id']?'selected':'' ?>>
                  <?= htmlspecialchars($c['ma_lop'].' - '.$c['ten_lop']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tìm (mã SV / họ tên)</label>
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" class="form-control" placeholder="VD: DH22TIN001, Nguyễn Văn A">
          </div>
          <div class="col-md-5 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Lọc dữ liệu</button>
            <a class="btn btn-outline-primary" href="?">Xóa lọc</a>
            <a class="btn btn-outline-primary" href="?<?= http_build_query(['lop_id'=>$lop_id,'q'=>$q,'export'=>'csv']) ?>">
              <i class="bi bi-filetype-csv"></i> Xuất CSV
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="card kpi p-3 d-flex flex-row align-items-center gap-3">
          <div class="icon-wrap"><i class="bi bi-mortarboard"></i></div>
          <div>
            <div class="fw-bold">Tổng sinh viên hiển thị</div>
            <div class="fs-4"><?= count($view) ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card kpi green p-3 d-flex flex-row align-items-center gap-3">
          <div class="icon-wrap"><i class="bi bi-emoji-smile"></i></div>
          <div>
            <div class="fw-bold">Tốt & Xuất sắc</div>
            <div class="fs-4"><?= ($counts['Tốt']+$counts['Xuất sắc']) ?></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card kpi orange p-3 d-flex flex-row align-items-center gap-3">
          <div class="icon-wrap"><i class="bi bi-activity"></i></div>
          <div>
            <div class="fw-bold">Cần chú ý (Yếu/Kém)</div>
            <div class="fs-4"><?= ($counts['Yếu']+$counts['Kém']) ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- BẢNG DỮ LIỆU -->
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">Bảng điểm rèn luyện</h5>
          <span class="chip orange">3 màu chủ đạo: xanh dương, xanh ngọc, cam</span>
        </div>
        <div class="table-responsive" style="max-height: 60vh;">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>STT</th>
                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>Lớp</th>
                <th class="text-center">Tổng điểm</th>
                <th>Xếp loại</th>
                <th>Ghi chú</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($view)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Không có dữ liệu phù hợp.</td></tr>
              <?php else: ?>
                <?php foreach($view as $i => $r): ?>
                  <?php
                    // Chọn nhãn 3 màu tùy xếp loại
                    $badgeClass = 'xl-blue';
                    if (in_array($r['xep_loai'], ['Tốt','Xuất sắc'])) $badgeClass = 'xl-green';
                    if (in_array($r['xep_loai'], ['Yếu','Kém'])) $badgeClass = 'xl-orange';
                  ?>
                  <tr>
                    <td><?= $i+1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['ma_sv']) ?></td>
                    <td><?= htmlspecialchars($r['ho_ten']) ?></td>
                    <td>
                      <div class="fw-semibold"><?= htmlspecialchars($r['ma_lop']) ?></div>
                      <div class="text-muted small"><?= htmlspecialchars($r['ten_lop']) ?></div>
                    </td>
                    <td class="text-center">
                      <span class="badge badge-xl <?= $badgeClass ?>">
                        <?= (int)$r['tong_diem'] ?>
                      </span>
                    </td>
                    <td><span class="fw-semibold"><?= htmlspecialchars($r['xep_loai']) ?></span></td>
                    <td class="text-muted"><?= htmlspecialchars($r['ghi_chu']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FOOT NOTE -->
    <div class="text-center text-muted small mt-3">
      Demo 1 file PHP – Bootstrap 5 • Màu thương hiệu: <span class="fw-semibold" style="color:var(--c1)">Xanh dương</span> •
      <span class="fw-semibold" style="color:var(--c2)">Xanh ngọc</span> •
      <span class="fw-semibold" style="color:var(--c3)">Cam</span>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
