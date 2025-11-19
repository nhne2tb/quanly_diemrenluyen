<?php
// =============================
// DANH SÁCH SINH VIÊN THEO LỚP
// =============================
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

// Lấy mã lớp
$ma_lop = $_GET['lop'] ?? '';
if (!$ma_lop) die("Thiếu tham số lớp!");

// Thông tin lớp
$stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_lop = ?");
$stmt->execute([$ma_lop]);
$lop = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$lop) die("Không tìm thấy lớp!");

$ma_khoa = strtolower($lop['ma_khoa']);
$table_sv = "sv_" . $ma_khoa . "_" . strtolower($ma_lop);

// Kiểm tra bảng tồn tại
$check = $conn->query("SHOW TABLES LIKE '$table_sv'");
if ($check->rowCount() === 0) die("⚠ Bảng $table_sv chưa tồn tại!");

// Tìm kiếm
$keyword = $_GET['search'] ?? '';
if ($keyword) {
  $stmt = $conn->prepare("SELECT * FROM `$table_sv` 
                          WHERE ho_ten LIKE ? OR mssv LIKE ? OR email LIKE ? 
                          ORDER BY ho_ten ASC");
  $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
} else {
  $stmt = $conn->query("SELECT * FROM `$table_sv` ORDER BY ho_ten ASC");
}
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Danh sách sinh viên <?= htmlspecialchars($lop['ten_lop']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
.table th { text-align:center; vertical-align:middle; }
.table td { vertical-align:middle; }
.table-hover tbody tr:hover { background:#f1f3f5; }
td[contenteditable="true"]:focus { background:#fff3cd !important; outline:none; }
.hidden-col { display:none; }
</style>
</head>
<body>
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">
      <i class="bi bi-people-fill me-2 text-primary"></i>
      Danh sách sinh viên - <?= htmlspecialchars($lop['ten_lop']) ?>
    </h4>
<a href="index.php?route=dashboard" class="btn btn-outline-primary d-flex align-items-center gap-2 px-3">
  <i class="bi bi-house-door-fill"></i>
  <span>Về trang Dashboard</span>
</a>

  </div>

  <!-- Bộ lọc -->
  <form method="get" class="row g-2 mb-3">
    <input type="hidden" name="route" value="danhsach_sv">
    <input type="hidden" name="lop" value="<?= htmlspecialchars($ma_lop) ?>">
    <div class="col-md-8">
      <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, MSSV hoặc email..." value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-md-4 d-flex gap-2">
      <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tìm</button>
      <button type="button" onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> In</button>
      <button type="button" id="btnDelete" class="btn btn-danger"><i class="bi bi-trash3"></i> Xóa đã chọn</button>
    </div>
  </form>

  <!-- Tùy chọn hiển thị cột -->
  <div class="card mb-3">
    <div class="card-header bg-light">
      <strong><i class="bi bi-eye me-2"></i>Chọn cột hiển thị:</strong>
    </div>
    <div class="card-body d-flex flex-wrap gap-3 small">
      <?php 
      $cols = [
        'mssv'=>'MSSV','ho_ten'=>'Họ và tên','gioi_tinh'=>'Giới tính',
        'ngay_sinh'=>'Ngày sinh','email'=>'Email','sdt'=>'SĐT',
        'dia_chi'=>'Địa chỉ','trang_thai'=>'Trạng thái','ghi_chu'=>'Ghi chú'
      ];
      foreach ($cols as $key=>$label): ?>
        <div class="form-check">
          <input class="form-check-input col-toggle" type="checkbox" id="col_<?= $key ?>" data-col="<?= $key ?>" checked>
          <label class="form-check-label" for="col_<?= $key ?>"><?= $label ?></label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table id="svTable" class="table table-bordered table-hover align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <?php foreach ($cols as $key => $label): ?>
                <th data-col="<?= $key ?>"><?= $label ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
          <?php if ($students): foreach ($students as $i=>$sv): ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="selectRow" value="<?= htmlspecialchars($sv['mssv']) ?>">
              </td>
              <td data-col="mssv"><?= htmlspecialchars($sv['mssv']) ?></td>
              <td data-col="ho_ten"><?= htmlspecialchars($sv['ho_ten']) ?></td>
              <td data-col="gioi_tinh" class="text-center"><?= htmlspecialchars($sv['gioi_tinh']) ?></td>
              <td data-col="ngay_sinh" class="text-center"><?= $sv['ngay_sinh'] ? date('d/m/Y', strtotime($sv['ngay_sinh'])) : '' ?></td>
              <td data-col="email"><?= htmlspecialchars($sv['email']) ?></td>
              <td data-col="sdt" class="text-center"><?= htmlspecialchars($sv['sdt']) ?></td>
              <td data-col="dia_chi"><?= htmlspecialchars($sv['dia_chi']) ?></td>
              <td data-col="trang_thai" class="text-center"><?= htmlspecialchars($sv['trang_thai']) ?></td>
              <td data-col="ghi_chu" contenteditable="true" class="bg-light" data-mssv="<?= $sv['mssv'] ?>">
                <?= htmlspecialchars($sv['ghi_chu'] ?? '') ?>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="11" class="text-center text-muted">Không có sinh viên nào.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// === Tự động lưu ghi chú ===
document.querySelectorAll('td[contenteditable="true"]').forEach(cell=>{
  cell.addEventListener('blur', async e=>{
    const mssv = e.target.dataset.mssv;
    const note = e.target.textContent.trim();
    const res = await fetch('save_note.php', {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:new URLSearchParams({mssv, note, table:'<?= $table_sv ?>'})
    });
    const text = await res.text();
    if (text.includes('OK')) {
      Swal.fire({icon:'success',title:'Đã lưu ghi chú',timer:800,showConfirmButton:false});
    }
  });
});

// === Chọn tất cả ===
const selectAll = document.getElementById('selectAll');
selectAll?.addEventListener('change',()=>{
  document.querySelectorAll('.selectRow').forEach(ch=>ch.checked = selectAll.checked);
});

// === Xóa đã chọn ===
document.getElementById('btnDelete').addEventListener('click', async ()=>{
  const selected = [...document.querySelectorAll('.selectRow:checked')].map(x=>x.value);
  if (selected.length===0) {
    Swal.fire({icon:'warning',title:'Chưa chọn sinh viên nào!'});
    return;
  }
  const confirm = await Swal.fire({
    icon:'warning',
    title:`Xóa ${selected.length} sinh viên?`,
    text:'Hành động này không thể khôi phục!',
    showCancelButton:true,
    confirmButtonText:'Xóa',
    cancelButtonText:'Hủy',
    confirmButtonColor:'#d33'
  });
  if (!confirm.isConfirmed) return;

  const res = await fetch('xoa_sv.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:new URLSearchParams({list: selected.join(','), table:'<?= $table_sv ?>'})
  });
  const text = await res.text();
  if (text.includes('OK')) location.reload();
  else Swal.fire({icon:'error',title:'Lỗi',text:text});
});

// === Ẩn/hiện cột ===
document.querySelectorAll('.col-toggle').forEach(ch=>{
  ch.addEventListener('change',()=>{
    const col = ch.dataset.col;
    document.querySelectorAll(`[data-col="${col}"]`).forEach(td=>{
      td.classList.toggle('hidden-col', !ch.checked);
    });
  });
});
</script>
</body>
</html>
