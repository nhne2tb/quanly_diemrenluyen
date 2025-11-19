<?php
// views/layouts/giang_vien/gd_giang_vien.php — Giao diện giảng viên
// ================== DỮ LIỆU ẢO - GIẢNG VIÊN ==================
$giangvien = [
  'magv'        => 'GV001',
  'hoten'       => 'ThS. Nguyễn Văn Giảng',
  'gioitinh'    => 'Nam',
  'ngaysinh'    => '10/08/1988',
  'bomon'       => 'Sư phạm Tin học',
  'khoa'        => 'Khoa Sư phạm Toán – Tin',
  'hocham'      => 'Thạc sĩ',
  'chucdanh'    => 'Giảng viên chính',
  'nhiemvu'     => 'Giảng dạy, Cố vấn học tập lớp SPTIN22A, quản lý điểm rèn luyện',
  'namcongtac'  => 12,
  'email'       => 'giangnv@dthu.edu.vn',
  'sdt'         => '0912345678',
  'chuyenmon'   => 'Công nghệ phần mềm, Sư phạm Tin học',
  'avatar'      => 'https://cdn-icons-png.flaticon.com/512/2202/2202112.png'
];

$ds_lop = [
  ['ma_lop'=>'SPTIN22A','ten_lop'=>'Sư phạm Tin học K22A','nien_khoa'=>'2022–2026','si_so'=>42,'ghi_chu'=>'Lớp chính quy'],
  ['ma_lop'=>'SPTIN22B','ten_lop'=>'Sư phạm Tin học K22B','nien_khoa'=>'2022–2026','si_so'=>39,'ghi_chu'=>'Buổi chiều'],
  ['ma_lop'=>'SPTIN21A','ten_lop'=>'Sư phạm Tin học K21A','nien_khoa'=>'2021–2025','si_so'=>44,'ghi_chu'=>'']
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Giảng viên - Quản lý lớp học</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
  --brand:#004aad;
  --accent:#2e8bff;
  --bg:#f4f6fb;
}
body {
  background: var(--bg);
  font-family: 'Segoe UI', sans-serif;
  color: #333;
}
.navbar {
  background: #fff;
  border-bottom: 1px solid #e0e0e0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.navbar .logo {
  height: 48px;
}
.brand-text b { color: var(--brand); }
.brand-text span { color: #e53935; font-weight: 700; font-size: 14px; }
.page-title { font-weight: 700; color: var(--brand); }

.card {
  border: none;
  border-radius: 14px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  background: #fff;
}

.table thead {
  background: #e9f2ff;
  color: var(--brand);
}
.table-hover tbody tr:hover {
  background-color: #f4f8ff;
}
.input-group .form-control:focus {
  box-shadow: 0 0 0 .2rem rgba(0,74,173,.15);
  border-color: #b6d4fe;
}
.btn-outline-warning i { color: #ff9800; }
footer {
  text-align: center;
  font-size: 13px;
  color: #777;
  margin-top: 40px;
}
</style>
</head>
<body>

<!-- =============== HEADER =============== -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-xxl d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
      <img src="https://upload.wikimedia.org/wikipedia/vi/1/1a/Logo_truong_Dai_hoc_Dong_Thap.png" class="logo" alt="Logo">
      <div class="brand-text lh-sm">
        <b>BỘ GIÁO DỤC VÀ ĐÀO TẠO</b><br><span>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</span>
      </div>
    </div>

    <div class="d-flex align-items-center gap-3 ms-3">
      <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-house-door me-1"></i>Trang chủ</a>
      <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-book me-1"></i>Quản lý lớp</a>
      <div class="dropdown">
        <a href="#" class="text-secondary text-decoration-none dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
          <img src="<?= $giangvien['avatar'] ?>" width="28" height="28" class="rounded-circle">
          <?= $giangvien['hoten'] ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Thông tin cá nhân</a></li>
          <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- =============== BODY =============== -->
<div class="container-xxl my-4">
  <div class="mb-3 text-center">
    <h4 class="page-title"><i class="bi bi-person-workspace me-2"></i>HỒ SƠ GIẢNG VIÊN & QUẢN LÝ LỚP</h4>
  </div>

  <!-- THÔNG TIN GIẢNG VIÊN -->
  <div class="card p-4 mb-4">
    <div class="row">
      <div class="col-md-3 text-center">
        <img src="<?= $giangvien['avatar'] ?>" class="rounded-circle mb-3" width="120" height="120">
        <h5><?= $giangvien['hoten'] ?></h5>
        <span class="badge bg-primary"><?= $giangvien['chucdanh'] ?></span>
        <div class="mt-2">
          <button class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil"></i> Cập nhật thông tin
          </button>
        </div>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <p><b>Mã GV:</b> <?= $giangvien['magv'] ?></p>
            <p><b>Giới tính:</b> <?= $giangvien['gioitinh'] ?></p>
            <p><b>Ngày sinh:</b> <?= $giangvien['ngaysinh'] ?></p>
            <p><b>Học hàm/Học vị:</b> <?= $giangvien['hocham'] ?></p>
            <p><b>Số năm công tác:</b> <?= $giangvien['namcongtac'] ?> năm</p>
          </div>
          <div class="col-md-6">
            <p><b>Bộ môn:</b> <?= $giangvien['bomon'] ?></p>
            <p><b>Khoa:</b> <?= $giangvien['khoa'] ?></p>
            <p><b>Chuyên môn:</b> <?= $giangvien['chuyenmon'] ?></p>
            <p><b>Email:</b> <?= $giangvien['email'] ?></p>
            <p><b>Điện thoại:</b> <?= $giangvien['sdt'] ?></p>
          </div>
        </div>
        <div class="mt-2"><b>Nhiệm vụ:</b> <?= $giangvien['nhiemvu'] ?></div>
      </div>
    </div>
  </div>

  <!-- DANH SÁCH LỚP -->
  <div class="card p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
      <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Danh sách lớp phụ trách</h5>

      <div class="d-flex gap-2">
        <div class="input-group input-group-sm" style="width: 220px;">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
          <input type="text" id="searchLop" class="form-control border-start-0" placeholder="Tìm kiếm lớp...">
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalThemLop">
          <i class="bi bi-plus-lg me-1"></i>Thêm lớp
        </button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle" id="lopTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Sĩ số</th>
            <th>Ghi chú</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ds_lop as $i => $lop): ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($lop['ma_lop']) ?></td>
            <td><?= htmlspecialchars($lop['ten_lop']) ?></td>
            <td><?= htmlspecialchars($lop['nien_khoa']) ?></td>
            <td class="text-center"><span class="badge bg-secondary rounded-pill px-3"><?= (int)$lop['si_so'] ?></span></td>
            <td><?= htmlspecialchars($lop['ghi_chu']) ?></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-primary me-1" title="Xem SV"><i class="bi bi-person-lines-fill"></i></button>
              <button class="btn btn-sm btn-outline-warning me-1" title="Sửa"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Thêm Lớp -->
<div class="modal fade" id="modalThemLop" tabindex="-1" aria-labelledby="modalThemLopLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalThemLopLabel"><i class="bi bi-plus-circle me-2"></i>Thêm lớp mới</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Mã lớp</label>
            <input type="text" name="ma_lop" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tên lớp</label>
            <input type="text" name="ten_lop" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Niên khóa</label>
            <input type="text" name="nien_khoa" class="form-control" placeholder="VD: 2022–2026" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu lớp</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer>© 2025 Trường Đại học Đồng Tháp — Giao diện Giảng viên</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Lọc lớp (client-side)
document.getElementById('searchLop').addEventListener('keyup', function() {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll('#lopTable tbody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
  });
});
</script>
</body>
</html>
