<?php
// index.php — Hệ thống Quản lý Điểm Rèn Luyện (đa khoa, có tìm kiếm)
header('Content-Type: text/html; charset=utf-8');

// ======= DỮ LIỆU ẢO =======
$departments = [
  ['id'=>1, 'ten_khoa'=>'Khoa Sư phạm Toán – Tin', 'viet_tat'=>'SPTT', 'logo'=>'https://cdn-icons-png.flaticon.com/128/4727/4727470.png'],
  ['id'=>2, 'ten_khoa'=>'Khoa Sư phạm Sinh học', 'viet_tat'=>'SPSH', 'logo'=>'https://cdn-icons-png.flaticon.com/128/4324/4324781.png'],
  ['id'=>3, 'ten_khoa'=>'Khoa Kinh tế', 'viet_tat'=>'KT', 'logo'=>'https://cdn-icons-png.flaticon.com/128/3119/3119198.png'],
  ['id'=>4, 'ten_khoa'=>'Khoa Ngữ văn', 'viet_tat'=>'SPNV', 'logo'=>'https://cdn-icons-png.flaticon.com/128/4119/4119302.png'],
  ['id'=>5, 'ten_khoa'=>'Khoa Giáo dục Tiểu học', 'viet_tat'=>'SPTH', 'logo'=>'https://cdn-icons-png.flaticon.com/128/893/893292.png'],
];

$students = [
  ['ma_sv'=>'DH22TIN001','ho_ten'=>'Nguyễn Văn A','lop'=>'ĐHSP Tin 22A','khoa_id'=>1,'tong_diem'=>95,'xep_loai'=>'Xuất sắc'],
  ['ma_sv'=>'DH22TIN002','ho_ten'=>'Trần Thị B','lop'=>'ĐHSP Tin 22A','khoa_id'=>1,'tong_diem'=>84,'xep_loai'=>'Tốt'],
  ['ma_sv'=>'DH22SH001','ho_ten'=>'Phạm Thị H','lop'=>'ĐHSP Sinh 22A','khoa_id'=>2,'tong_diem'=>78,'xep_loai'=>'Tốt'],
  ['ma_sv'=>'DH22KT001','ho_ten'=>'Lê Hoàng K','lop'=>'ĐH Kinh tế 22A','khoa_id'=>3,'tong_diem'=>82,'xep_loai'=>'Tốt'],
  ['ma_sv'=>'DH22NV001','ho_ten'=>'Ngô Thu P','lop'=>'ĐH Ngữ văn 22A','khoa_id'=>4,'tong_diem'=>68,'xep_loai'=>'Khá'],
  ['ma_sv'=>'DH22TH001','ho_ten'=>'Trương Quốc Q','lop'=>'ĐH Tiểu học 22A','khoa_id'=>5,'tong_diem'=>60,'xep_loai'=>'Trung bình'],
];

// ======= LOGIC =======
$khoa_id = isset($_GET['khoa_id']) ? intval($_GET['khoa_id']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$filtered_departments = $departments;
if ($search !== '') {
  $filtered_departments = array_filter($departments, function($k) use ($search) {
    return stripos($k['ten_khoa'], $search) !== false || stripos($k['viet_tat'], $search) !== false;
  });
}

$students_view = $khoa_id ? array_filter($students, fn($s) => $s['khoa_id'] == $khoa_id) : [];
$ten_khoa_hien_tai = 'Tất cả khoa';
if ($khoa_id) {
  $ten_khoa_hien_tai = array_values(array_filter($departments, fn($d)=>$d['id']==$khoa_id))[0]['ten_khoa'] ?? 'Không xác định';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Hệ thống Quản lý Điểm rèn luyện - DThU</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --c1: #0056b3;
      --c2: #20c997;
      --c3: #f39c12;
    }
    body { background-color: #f5f9ff; font-family: 'Segoe UI', Roboto, sans-serif; }
    .brand-top { background: #f5f9ff; border-bottom: 1px solid #dee2e6; padding: .5rem 0; }
    .navbar { background: var(--c1); }
    .navbar .nav-link { color: #fff !important; font-weight: 500; }
    .navbar .nav-link.active, .navbar .nav-link:hover { color: var(--c3) !important; }
    .btn-login { border-color: var(--c1); color: var(--c1); }
    .btn-login:hover { background: var(--c1); color: #fff; }
    .badge-xl { font-size: .9rem; padding: .5rem .75rem; }
    .xl-blue { background: rgba(13,110,253,.12); color: var(--c1); }
    .xl-green { background: rgba(32,201,151,.12); color: var(--c2); }
    .xl-orange { background: rgba(243,156,18,.12); color: var(--c3); }
  </style>
</head>
<body>
  <!-- HEADER -->
  <header class="brand-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <img src="https://dthu.edu.vn/Portals/0/logo-dthu.png" alt="Logo DThU" height="55">
        <div>
          <h5 class="mb-0 fw-bold" style="color:var(--c1);">Trường Đại học Đồng Tháp</h5>
          <small class="text-muted">Hệ thống Quản lý Điểm rèn luyện</small>
        </div>
      </div>
      <a href="#" class="btn btn-login">
        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
      </a>
    </div>
  </header>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand fw-bold" href="?">QLDRL</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a href="?" class="nav-link <?= !$khoa_id?'active':'' ?>">Trang chủ</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Giảng viên</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Thống kê</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Liên hệ</a></li>
        </ul>
        <span class="navbar-text text-light small"><i class="bi bi-person-circle"></i> Khách truy cập</span>
      </div>
    </div>
  </nav>

  <!-- NỘI DUNG -->
  <main class="container py-4">

    <?php if(!$khoa_id): ?>
      <!-- TRANG CHỦ -->
      <section class="text-center mb-4">
        <h3 class="text-primary fw-bold mb-2">HỆ THỐNG QUẢN LÝ ĐIỂM RÈN LUYỆN</h3>
        <p class="text-muted mb-4">Dành cho sinh viên các khoa Trường Đại học Đồng Tháp</p>

        <!-- THANH TÌM KIẾM -->
        <form method="get" class="d-flex justify-content-center mb-4" style="max-width:600px;margin:auto;">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Nhập tên khoa hoặc mã (VD: SPTT, Sinh học)">
          <button class="btn btn-primary"><i class="bi bi-search"></i> Tìm</button>
          <?php if($search): ?>
            <a href="?" class="btn btn-outline-secondary ms-2">Xóa</a>
          <?php endif; ?>
        </form>

        <!-- DANH SÁCH KHOA -->
        <div class="row g-4 justify-content-center">
          <?php if(empty($filtered_departments)): ?>
            <p class="text-muted">Không tìm thấy khoa nào phù hợp.</p>
          <?php else: ?>
            <?php foreach($filtered_departments as $khoa): ?>
              <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                  <div class="text-center">
                    <img src="<?= $khoa['logo'] ?>" width="70" class="mb-3">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($khoa['ten_khoa']) ?></h5>
                    <p class="text-muted small mb-3">Mã khoa: <?= $khoa['viet_tat'] ?></p>
                    <a href="?khoa_id=<?= $khoa['id'] ?>" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i> Xem điểm rèn luyện
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

    <?php else: ?>
      <!-- BẢNG SINH VIÊN THEO KHOA -->
      <h4 class="text-primary mb-3">Bảng điểm rèn luyện – <?= htmlspecialchars($ten_khoa_hien_tai) ?></h4>
      <div class="table-responsive shadow-sm bg-white rounded p-3">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Mã SV</th>
              <th>Họ tên</th>
              <th>Lớp</th>
              <th class="text-center">Tổng điểm</th>
              <th>Xếp loại</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($students_view)): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Không có sinh viên trong khoa này.</td></tr>
            <?php else: ?>
              <?php foreach($students_view as $i=>$sv): ?>
                <?php
                  $class = 'xl-blue';
                  if (in_array($sv['xep_loai'], ['Tốt','Xuất sắc'])) $class = 'xl-green';
                  if (in_array($sv['xep_loai'], ['Yếu','Kém'])) $class = 'xl-orange';
                ?>
                <tr>
                  <td><?= $i+1 ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($sv['ma_sv']) ?></td>
                  <td><?= htmlspecialchars($sv['ho_ten']) ?></td>
                  <td><?= htmlspecialchars($sv['lop']) ?></td>
                  <td class="text-center"><span class="badge badge-xl <?= $class ?>"><?= $sv['tong_diem'] ?></span></td>
                  <td><?= htmlspecialchars($sv['xep_loai']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="text-end mt-3">
        <a href="?" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại trang chủ</a>
      </div>
    <?php endif; ?>
  </main>

  <footer class="text-center py-3 mt-4" style="background:var(--c1); color:#fff;">
    <small>© 2025 Trường Đại học Đồng Tháp (SPD) – Website: dthu.edu.vn</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
