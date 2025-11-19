<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

// ================= Lấy màu chủ đạo =================
$color1 = $SCHOOL_INFO['mau1'] ?? '#0056B3';
function darkenColor($hex, $p = 15) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    [$r,$g,$b] = [hexdec(substr($hex,0,2)),hexdec(substr($hex,2,2)),hexdec(substr($hex,4,2))];
    $r = max(0, min(255, $r - $r*$p/100));
    $g = max(0, min(255, $g - $g*$p/100));
    $b = max(0, min(255, $b - $b*$p/100));
    return sprintf("#%02x%02x%02x",$r,$g,$b);
}
$color1_hover = darkenColor($color1,15);
function h($v){return htmlspecialchars($v ?? '',ENT_QUOTES,'UTF-8');}

// ================= Lấy dữ liệu lớp =================
$sql = "SELECT l.*, g.ho_ten AS ten_gv, g.email AS email_gv, g.sdt AS sdt_gv, k.ten_khoa 
        FROM tb_lop l
        LEFT JOIN tb_giangvien g ON l.ma_gv = g.ma_gv
        LEFT JOIN tb_khoa k ON l.ma_khoa = k.ma_khoa
        ORDER BY l.ma_lop ASC";
$lops = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$total = count($lops);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quản lý Lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{
  --brand: <?= h($color1) ?>;
  --brand-hover: <?= h($color1_hover) ?>;
  --muted: #6c757d;
  --bg-light: #f8f9fa;
}
body{background:var(--bg-light);font-family:system-ui,Segoe UI,Roboto,sans-serif;}
.wrap{max-width:1200px;margin:auto;padding:24px;}
.main-card{background:#fff;border:none;border-radius:1rem;box-shadow:0 8px 25px rgba(0,0,0,.05);overflow:hidden;}
.card-header{
  border-bottom:1px solid #e5e7eb;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:1rem;
  background:#fff;
  padding:1rem 1.5rem;
}
.card-title{color:var(--brand);font-weight:700;font-size:1.25rem;}
.btn-brand{background:var(--brand);color:#fff;border-radius:.5rem;}
.btn-brand:hover{background:var(--brand-hover);color:#fff;}
.table{border-collapse:separate;border-spacing:0 .4rem;}
.table tbody tr{background:#fff;border-radius:.6rem;transition:.2s;}
.table tbody tr:hover{transform:translateY(-2px);box-shadow:0 3px 12px rgba(0,0,0,.05);}
.badge-code{
  background:var(--brand);
  color:#fff;
  padding:.35em .6em;
  border-radius:.4rem;
  font-weight:600;
  min-width:65px;
  text-align:center;
  display:inline-block;
}
.action-group{
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:.3rem;
}
.action-btn{
  display:inline-flex;
  align-items:center;
  gap:.3rem;
  font-size:.8rem;
  font-weight:500;
  padding:.35rem .7rem;
  border-radius:50rem;
  border:1px solid transparent;
  text-decoration:none!important;
  background:#f8f9fa;
  transition:.2s;
}
.action-btn.detail{color:var(--brand);border-color:rgba(0,74,173,.25);}
.action-btn.detail:hover{background:var(--brand);color:#fff;}
.action-btn.edit{color:#0d6efd;border-color:rgba(13,110,253,.25);}
.action-btn.edit:hover{background:#0d6efd;color:#fff;}
.action-btn.delete{color:#dc3545;border-color:rgba(220,53,69,.25);}
.action-btn.delete:hover{background:#dc3545;color:#fff;}
.action-btn.student{color:#198754;border-color:rgba(25,135,84,.25);}
.action-btn.student:hover{background:#198754;color:#fff;}
.action-btn.add{color:#ff9800;border-color:rgba(255,152,0,.25);}
.action-btn.add:hover{background:#ff9800;color:#fff;}
.details-row{background:#f9fafb;}
.details-content{padding:1rem 1.5rem;}
.details-content .item{margin-bottom:.4rem;font-size:.9rem;}
.details-content strong{min-width:130px;display:inline-block;color:var(--muted);}
</style>
</head>
<body>

<div class="wrap">
  
  <a href="index.php?route=dashboard" class="btn btn-outline-primary d-flex align-items-center gap-2 px-3">
  <i class="bi bi-house-door-fill"></i>
  <span>Về trang Dashboard</span>
</a>
 <br>

  <div class="main-card">
    <div class="card-header">
      <h4 class="card-title mb-0"><i class="bi bi-collection me-2"></i>Danh sách Lớp <span class="text-muted fs-6">(<?= $total ?>)</span>


    </h4>
      <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width:600px">
        <div class="input-group flex-grow-1">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
          <input id="tableSearch" type="search" class="form-control border-start-0" placeholder="Tìm kiếm lớp...">
        </div>
  <a href="index.php?route=them_lop" class="btn btn-brand d-flex align-items-center px-3 py-1" style="white-space:nowrap;">
    <i class="bi bi-plus-lg me-1"></i> Thêm Lớp
  </a>      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle" id="lopTable">
        <thead>
          <tr class="table-light text-center">
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Cố vấn học tập</th>
            <th>Khoa</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php if(empty($lops)): ?>
          <tr><td colspan="5" class="text-center text-muted py-5"><i class="bi bi-diagram-3 fs-2"></i><br>Chưa có dữ liệu lớp.</td></tr>
        <?php else: foreach($lops as $i=>$l): ?>
          <tr class="data-row">
            <td><span class="badge-code"><?= h($l['ma_lop']) ?></span></td>
            <td class="fw-semibold"><?= h($l['ten_lop']) ?></td>
            <td><?= h($l['ten_gv']) ?: '—' ?></td>
            <td><?= h($l['ten_khoa']) ?: '—' ?></td>
            <td>
              <div class="action-group">
                <button class="action-btn detail" data-bs-toggle="collapse" data-bs-target="#details<?= $i ?>" aria-expanded="false">
                  <i class="bi bi-chevron-down"></i> Chi tiết
                </button>
                <a href="index.php?route=sua_lop&ma=<?= urlencode($l['ma_lop']) ?>" class="action-btn edit">
                  <i class="bi bi-pencil-square"></i> Sửa
                </a>
                <a href="index.php?route=xoa_lop&ma=<?= urlencode($l['ma_lop']) ?>" class="action-btn delete" onclick="return confirm('Bạn có chắc muốn xóa lớp này?')">
                  <i class="bi bi-trash3"></i> Xóa
                </a>
                <a href="index.php?route=danhsach_sv&lop=<?= urlencode($l['ma_lop']) ?>" class="action-btn student">
                  <i class="bi bi-people-fill"></i> SV
                </a>
                <a href="index.php?route=them_sv&lop=<?= urlencode($l['ma_lop']) ?>" class="action-btn add">
                  <i class="bi bi-person-plus-fill"></i> Thêm SV
                </a>
              </div>
            </td>
          </tr>
          <tr class="collapse details-row" id="details<?= $i ?>">
            <td colspan="5">
              <div class="details-content row">
                <div class="col-md-6 item"><strong>Niên khóa:</strong> <?= h($l['nien_khoa']) ?></div>
                <div class="col-md-6 item"><strong>Khóa học:</strong> <?= h($l['khoa_hoc']) ?></div>
                <div class="col-md-6 item"><strong>Hệ đào tạo:</strong> <?= h($l['he_dao_tao']) ?></div>
                <div class="col-md-6 item"><strong>Bậc đào tạo:</strong> <?= h($l['bac_dao_tao']) ?></div>
                <div class="col-md-6 item"><strong>Ngành học:</strong> <?= h($l['nganh_hoc']) ?></div>
                <div class="col-md-6 item"><strong>Sĩ số:</strong> <?= h($l['si_so']) ?></div>
                <div class="col-12 item"><strong>Cố vấn:</strong> <?= h($l['ten_gv']) ?><?= $l['email_gv'] ? ' | <a href="mailto:'.h($l['email_gv']).'">'.h($l['email_gv']).'</a>' : '' ?><?= $l['sdt_gv'] ? ' | '.h($l['sdt_gv']) : '' ?></div>
                <div class="col-12 item"><strong>Ghi chú:</strong> <?= h($l['ghi_chu']) ?: '—' ?></div>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tìm kiếm nhanh
document.getElementById('tableSearch').addEventListener('input',function(e){
  const q=e.target.value.toLowerCase().trim();
  document.querySelectorAll('#lopTable tbody tr.data-row').forEach(r=>{
    const d=r.nextElementSibling;
    const show=(r.innerText+(d?d.innerText:'')).toLowerCase().includes(q);
    r.style.display=show?'':'none';
    if(d) d.style.display=show?'':'none';
  });
});
</script>
</body>
</html>
