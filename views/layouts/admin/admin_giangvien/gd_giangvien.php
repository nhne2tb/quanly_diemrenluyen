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

// ================= Lấy dữ liệu =================
$sql = "SELECT g.*, k.ten_khoa 
        FROM tb_giangvien g 
        LEFT JOIN tb_khoa k ON g.ma_khoa = k.ma_khoa
        ORDER BY g.ma_gv ASC";
$giangviens = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$total = count($giangviens);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quản lý Giảng viên</title>
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
.wrap{max-width:1200px;margin:auto;padding:24px}
.main-card{background:#fff;border:none;border-radius:1rem;box-shadow:0 8px 30px rgba(0,0,0,.05);overflow:hidden;}

/* HEADER */
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
.card-header .input-group{flex:1;min-width:200px;}
.btn-brand{background:var(--brand);color:#fff;border-radius:.5rem;}
.btn-brand:hover{background:var(--brand-hover);color:#fff;}

/* BẢNG */
.table{border-collapse:separate;border-spacing:0 .5rem;}
.table thead th{
  background-color:#f1f3f5;
  font-weight:600;
  color:#495057;
}
.badge-code{
  background:var(--brand);
  color:#fff;
  padding:.35em .6em;
  border-radius:.4rem;
  font-weight:600;
  display:inline-block;
  min-width:70px;
  text-align:center;
}

/* HÀNH ĐỘNG */
.action-group{
  display:flex;
  justify-content:center;
  align-items:center;
  gap:.4rem;
}
.action-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:.25rem;
  font-size:.85rem;
  font-weight:500;
  padding:.35rem .6rem;
  border-radius:50rem;
  border:1px solid transparent;
  background:#f8f9fa;
  transition:all .2s ease-in-out;
  text-decoration:none !important;
  white-space:nowrap;
}
.action-btn.detail{color:var(--brand);border-color:rgba(13,110,253,0.2);}
.action-btn.detail:hover{background:var(--brand);color:#fff;}
.action-btn.edit{color:#0d6efd;border-color:rgba(13,110,253,0.2);}
.action-btn.edit:hover{background:#0d6efd;color:#fff;}
.action-btn.delete{color:#dc3545;border-color:rgba(220,53,69,0.2);}
.action-btn.delete:hover{background:#dc3545;color:#fff;}
.action-btn.detail i{transition:transform .2s}
.action-btn.detail[aria-expanded="true"] i{transform:rotate(180deg)}

/* CHI TIẾT */
.details-row{background:#f9fafb;}
.details-content{padding:1rem 1.5rem;}
.details-content .item{margin-bottom:.5rem;font-size:.9rem;}
.details-content strong{min-width:130px;display:inline-block;color:var(--muted);}
.avatar-sm{width:40px;height:40px;border-radius:50%;object-fit:cover;margin-right:.5rem}
</style>
</head>
<body>
<div class="wrap">
  <div class="d-flex justify-content-end mb-3">
    <a href="<?= BASE_URL ?>index.php?route=dashboard"
       class="btn btn-sm rounded-pill"
       style="border:1px solid #004aad; color:#004aad; font-weight:600;">
        <i class="bi bi-arrow-left-circle"></i> Quay lại
    </a>
</div>
  <div class="main-card">
    <!-- HEADER -->
    <div class="card-header">
      <h4 class="card-title mb-0">
        <i class="bi bi-person-badge me-1"></i> Danh sách Giảng viên 
        <span class="text-muted fs-6">(<?= $total ?>)</span>
      </h4>
      <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width:600px">
        <div class="input-group flex-grow-1">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
          <input id="tableSearch" type="search" class="form-control border-start-0" placeholder="Tìm kiếm giảng viên...">
        </div>
        <a href="them_giangvien.php" class="btn btn-brand">
          <i class="bi bi-plus-lg me-1"></i> Thêm GV
        </a>
      </div>
    </div>

    <!-- BẢNG -->
    <div class="table-responsive">
      <table class="table align-middle" id="gvTable">
        <thead>
          <tr>
            <th style="width:100px;">Mã GV</th>
            <th style="min-width:250px;">Họ tên</th>
            <th style="min-width:200px;">Email</th>
            <th style="min-width:180px;">Khoa</th>
            <th class="text-center" style="width:200px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php if(empty($giangviens)): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-5">
              <i class="bi bi-diagram-3 fs-2"></i><br>Chưa có dữ liệu giảng viên.
            </td>
          </tr>
        <?php else: foreach($giangviens as $i=>$g): ?>
          <tr class="data-row">
            <td><span class="badge-code"><?= h($g['ma_gv']) ?></span></td>
            <td>
              <div class="d-flex align-items-center">
                <img src="<?= h($g['avatar'] ?: 'https://cdn-icons-png.flaticon.com/512/2202/2202112.png') ?>" class="avatar-sm" alt="">
                <div>
                  <div class="fw-semibold"><?= h($g['ho_ten']) ?></div>
                  <small class="text-muted"><?= h($g['chuc_danh']) ?></small>
                </div>
              </div>
            </td>
            <td><?= h($g['email']) ?: '—' ?></td>
            <td><?= h($g['ten_khoa']) ?: '—' ?></td>
            <td>
              <div class="action-group">
                <button class="action-btn detail" data-bs-toggle="collapse" data-bs-target="#details<?= $i ?>" aria-expanded="false">
                  Chi tiết <i class="bi bi-chevron-down"></i>
                </button>
                <a href="sua_giangvien.php?ma=<?= urlencode($g['ma_gv']) ?>" class="action-btn edit">Sửa</a>
                <a href="xoa_giangvien.php?ma=<?= urlencode($g['ma_gv']) ?>" class="action-btn delete" onclick="return confirm('Bạn có chắc muốn xóa giảng viên này?')">Xóa</a>
              </div>
            </td>
          </tr>
          <tr class="collapse details-row" id="details<?= $i ?>">
            <td colspan="5">
              <div class="details-content row">
                <div class="col-md-6 item"><strong>Giới tính:</strong> <?= h($g['gioi_tinh']) ?></div>
                <div class="col-md-6 item"><strong>Ngày sinh:</strong> <?= h($g['ngay_sinh']) ?></div>
                <div class="col-md-6 item"><strong>Học hàm:</strong> <?= h($g['hoc_ham']) ?></div>
                <div class="col-md-6 item"><strong>Học vị:</strong> <?= h($g['hoc_vi']) ?></div>
                <div class="col-md-6 item"><strong>Bộ môn:</strong> <?= h($g['bo_mon']) ?></div>
                <div class="col-md-6 item"><strong>SĐT:</strong> <?= h($g['sdt']) ?></div>
                <div class="col-12 item"><strong>Chuyên môn:</strong> <?= h($g['chuyen_mon']) ?></div>
                <div class="col-12 item"><strong>Nhiệm vụ:</strong> <?= h($g['nhiem_vu']) ?></div>
                <div class="col-md-6 item"><strong>Năm công tác:</strong> <?= h($g['nam_cong_tac']) ?></div>
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
// Tìm kiếm trong bảng
document.getElementById('tableSearch').addEventListener('input',function(e){
  const q=e.target.value.toLowerCase().trim();
  document.querySelectorAll('#gvTable tbody tr.data-row').forEach(r=>{
    const d=r.nextElementSibling;
    const t=(r.innerText+(d?d.innerText:'')).toLowerCase();
    const show=t.includes(q);
    r.style.display=show?'':'none';
    if(d) d.style.display=show?'':'none';
  });
});
</script>
</body>
</html>
