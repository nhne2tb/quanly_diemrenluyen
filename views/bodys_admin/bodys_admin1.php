<?php
// =======================================================================
// views/bodys/bodys_admin/bodys_admin.php  —  GIAO DIỆN ADMIN (1 FILE)
// - Quản lý Khoa
// - Quản lý Lớp (theo Khoa)
// - Quản lý Giảng viên (phân công cố vấn)
// - Dữ liệu ảo, chưa cần CSDL
// - Thuần Bootstrap 5 + Icons, JS thuần để lọc/tìm/giả lập phân công
// =======================================================================

// ================== DỮ LIỆU ẢO ==================
$khoas = [
  ['ma_khoa'=>'SPTT', 'ten_khoa'=>'Khoa Sư phạm Toán – Tin', 'email'=>'toantin@dthu.edu.vn', 'sdt'=>'02773 888 001', 'truong_khoa'=>'TS. Võ Minh Toàn'],
  ['ma_khoa'=>'SBIO', 'ten_khoa'=>'Khoa Sinh học',            'email'=>'sinhhoc@dthu.edu.vn',  'sdt'=>'02773 888 002', 'truong_khoa'=>'TS. Phạm Thị Bích Liên'],
  ['ma_khoa'=>'KTE',  'ten_khoa'=>'Khoa Kinh tế',             'email'=>'kinhte@dthu.edu.vn',   'sdt'=>'02773 888 003', 'truong_khoa'=>'TS. Nguyễn Ngọc Minh'],
  ['ma_khoa'=>'NVAN', 'ten_khoa'=>'Khoa Ngữ văn',             'email'=>'nguvan@dthu.edu.vn',   'sdt'=>'02773 888 004', 'truong_khoa'=>'TS. Trần Hải Linh'],
  ['ma_khoa'=>'TIEU', 'ten_khoa'=>'Khoa Giáo dục Tiểu học',   'email'=>'tieuhoc@dthu.edu.vn',  'sdt'=>'02773 888 005', 'truong_khoa'=>'TS. Lê Quốc Bảo'],
];

$giang_viens = [
  ['magv'=>'GV001','hoten'=>'ThS. Nguyễn Văn Giảng','ma_khoa'=>'SPTT','chuyenmon'=>'Sư phạm Tin học','email'=>'giangnv@dthu.edu.vn','sdt'=>'0912345678','trang_thai'=>'Rảnh','so_lop'=>1],
  ['magv'=>'GV002','hoten'=>'TS. Võ Minh Toàn',     'ma_khoa'=>'SPTT','chuyenmon'=>'Toán ứng dụng',  'email'=>'toanvm@dthu.edu.vn', 'sdt'=>'0912345002','trang_thai'=>'Bận','so_lop'=>2],
  ['magv'=>'GV101','hoten'=>'ThS. Phạm Thị Hồng',   'ma_khoa'=>'SBIO','chuyenmon'=>'Di truyền học',  'email'=>'hongpth@dthu.edu.vn','sdt'=>'0911111111','trang_thai'=>'Rảnh','so_lop'=>0],
  ['magv'=>'GV201','hoten'=>'ThS. Trần Văn Kinh',   'ma_khoa'=>'KTE', 'chuyenmon'=>'Tài chính',      'email'=>'kinhtv@dthu.edu.vn', 'sdt'=>'0912222222','trang_thai'=>'Rảnh','so_lop'=>1],
  ['magv'=>'GV301','hoten'=>'ThS. Lê Thị Mai',      'ma_khoa'=>'NVAN','chuyenmon'=>'Ngôn ngữ học',   'email'=>'mailt@dthu.edu.vn',  'sdt'=>'0913333333','trang_thai'=>'Rảnh','so_lop'=>0],
  ['magv'=>'GV401','hoten'=>'ThS. Bùi Quốc An',     'ma_khoa'=>'TIEU','chuyenmon'=>'Giáo dục tiểu học','email'=>'anbq@dthu.edu.vn','sdt'=>'0914444444','trang_thai'=>'Bận','so_lop'=>3],
];

$lops = [
  ['ma_lop'=>'SPTIN22A','ten_lop'=>'Sư phạm Tin học K22A','ma_khoa'=>'SPTT','nien_khoa'=>'2022–2026','he'=>'Chính quy','si_so'=>42,'cvht_magv'=>'GV001','tinh_trang'=>'Đang học','ghi_chu'=>'Lớp chính quy'],
  ['ma_lop'=>'SPTIN22B','ten_lop'=>'Sư phạm Tin học K22B','ma_khoa'=>'SPTT','nien_khoa'=>'2022–2026','he'=>'Chính quy','si_so'=>39,'cvht_magv'=>null,  'tinh_trang'=>'Đang học','ghi_chu'=>'Buổi chiều'],
  ['ma_lop'=>'SPTIN21A','ten_lop'=>'Sư phạm Tin học K21A','ma_khoa'=>'SPTT','nien_khoa'=>'2021–2025','he'=>'Chính quy','si_so'=>44,'cvht_magv'=>'GV002','tinh_trang'=>'Chuẩn bị tốt nghiệp','ghi_chu'=>''],
  ['ma_lop'=>'SH22A',   'ten_lop'=>'Sinh học K22A',        'ma_khoa'=>'SBIO','nien_khoa'=>'2022–2026','he'=>'Chính quy','si_so'=>41,'cvht_magv'=>null,  'tinh_trang'=>'Đang học','ghi_chu'=>''],
  ['ma_lop'=>'KT22A',   'ten_lop'=>'Kinh tế K22A',         'ma_khoa'=>'KTE', 'nien_khoa'=>'2022–2026','he'=>'CQ',      'si_so'=>48,'cvht_magv'=>'GV201','tinh_trang'=>'Đang học','ghi_chu'=>''],
  ['ma_lop'=>'NV21B',   'ten_lop'=>'Ngữ văn K21B',         'ma_khoa'=>'NVAN','nien_khoa'=>'2021–2025','he'=>'CQ',      'si_so'=>36,'cvht_magv'=>null,  'tinh_trang'=>'Đang học','ghi_chu'=>''],
  ['ma_lop'=>'TH22C',   'ten_lop'=>'Tiểu học K22C',        'ma_khoa'=>'TIEU','nien_khoa'=>'2022–2026','he'=>'CQ',      'si_so'=>52,'cvht_magv'=>'GV401','tinh_trang'=>'Đang học','ghi_chu'=>'Lớp đông'],
];

// ================== HÀM TRỢ GIÚP ==================
function tênKhoa($ma_khoa, $khoas){
  foreach($khoas as $k){ if($k['ma_khoa']===$ma_khoa) return $k['ten_khoa']; } return $ma_khoa;
}
function tênGV($magv, $giang_viens){
  if(!$magv) return 'Chưa phân công';
  foreach($giang_viens as $g){ if($g['magv']===$magv) return $g['hoten']; } return $magv;
}
$totalKhoa = count($khoas);
$totalGV   = count($giang_viens);
$totalLop  = count($lops);
$chuaPC    = count(array_filter($lops, fn($l)=> empty($l['cvht_magv'])));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin — Quản trị khoa, lớp, giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root{ --brand:#004aad; --bg:#f4f6fb; }
body{ background:var(--bg); font-family:'Segoe UI',sans-serif; color:#333; }
.navbar{ background:#fff; border-bottom:1px solid #eaeaea; box-shadow:0 2px 6px rgba(0,0,0,.05); }
.logo{ height:44px; }
.card{ border:none; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.page-title{ color:var(--brand); font-weight:800; letter-spacing:.2px; }
.stat .card{ transition:.2s; }
.stat .card:hover{ transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.08);}
.badge-pill{ border-radius:999px; padding:.35rem .7rem; }
.table thead{ background:#e9f2ff; color:#0c3c8c; }
.table-hover tbody tr:hover{ background:#f6f9ff; }
.section-actions .input-group{ width:260px; }
.btn-icon{ width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; }
.toasts{ position:fixed; right:16px; bottom:16px; z-index:1080; }
</style>
</head>
<body>

<!-- NAV -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-xxl">
    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <img class="logo" src="https://upload.wikimedia.org/wikipedia/vi/1/1a/Logo_truong_Dai_hoc_Dong_Thap.png" alt="">
      <span class="fw-bold text-primary">Hệ thống Quản trị</span>
    </a>
    <ul class="navbar-nav ms-auto align-items-lg-center">
      <li class="nav-item me-3"><a class="nav-link" href="#"><i class="bi bi-speedometer2 me-1"></i>Tổng quan</a></li>
      <li class="nav-item me-3"><a class="nav-link" href="#"><i class="bi bi-gear me-1"></i>Cấu hình</a></li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-person-circle me-1"></i> Admin
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Tài khoản</a></li>
          <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="container-xxl py-4">
  <h3 class="page-title mb-3"><i class="bi bi-shield-lock me-2"></i>TRANG QUẢN TRỊ</h3>

  <!-- STATS -->
  <div class="row g-3 stat mb-4">
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <div>
            <div class="text-secondary small">Tổng số khoa</div>
            <div class="h4 mb-0"><?= $totalKhoa ?></div>
          </div>
          <span class="badge bg-primary badge-pill"><i class="bi bi-buildings"></i></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <div>
            <div class="text-secondary small">Tổng số lớp</div>
            <div class="h4 mb-0"><?= $totalLop ?></div>
          </div>
          <span class="badge bg-info text-dark badge-pill"><i class="bi bi-people"></i></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <div>
            <div class="text-secondary small">Tổng giảng viên</div>
            <div class="h4 mb-0"><?= $totalGV ?></div>
          </div>
          <span class="badge bg-success badge-pill"><i class="bi bi-person-badge"></i></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <div class="d-flex justify-content-between">
          <div>
            <div class="text-secondary small">Lớp chưa phân công</div>
            <div class="h4 mb-0" id="statChuaPC"><?= $chuaPC ?></div>
          </div>
          <span class="badge bg-warning text-dark badge-pill"><i class="bi bi-exclamation-circle"></i></span>
        </div>
      </div>
    </div>
  </div>

  <!-- TABS -->
  <ul class="nav nav-pills mb-3" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab-khoa" data-bs-toggle="tab" data-bs-target="#pane-khoa" type="button" role="tab">
        <i class="bi bi-buildings me-1"></i> Khoa
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-lop" data-bs-toggle="tab" data-bs-target="#pane-lop" type="button" role="tab">
        <i class="bi bi-people me-1"></i> Lớp
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-gv" data-bs-toggle="tab" data-bs-target="#pane-gv" type="button" role="tab">
        <i class="bi bi-person-workspace me-1"></i> Giảng viên
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-phancong" data-bs-toggle="tab" data-bs-target="#pane-phancong" type="button" role="tab">
        <i class="bi bi-diagram-3 me-1"></i> Phân công
      </button>
    </li>
  </ul>

  <div class="tab-content">
    <!-- KHOA -->
    <div class="tab-pane fade show active" id="pane-khoa" role="tabpanel">
      <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center section-actions mb-3 gap-2">
          <h5 class="mb-0"><i class="bi bi-building me-2"></i>Quản lý khoa</h5>
          <div class="d-flex gap-2">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
              <input class="form-control border-start-0" id="searchKhoa" placeholder="Tìm khoa...">
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalKhoa"><i class="bi bi-plus-lg me-1"></i>Thêm khoa</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tblKhoa">
            <thead>
              <tr>
                <th>#</th>
                <th>Mã khoa</th>
                <th>Tên khoa</th>
                <th>Trưởng khoa</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($khoas as $i=>$k): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($k['ma_khoa']) ?></td>
                <td><?= htmlspecialchars($k['ten_khoa']) ?></td>
                <td><?= htmlspecialchars($k['truong_khoa']) ?></td>
                <td><?= htmlspecialchars($k['email']) ?></td>
                <td><?= htmlspecialchars($k['sdt']) ?></td>
                <td class="text-center">
                  <button class="btn btn-outline-success btn-sm btn-icon" title="Sửa"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-outline-danger btn-sm btn-icon" title="Xóa"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- LỚP -->
    <div class="tab-pane fade" id="pane-lop" role="tabpanel">
      <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center section-actions mb-3 gap-2">
          <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Quản lý lớp</h5>
          <div class="d-flex gap-2">
            <select id="filterLopKhoa" class="form-select form-select-sm">
              <option value="">— Lọc theo khoa —</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>"><?= $k['ten_khoa'] ?></option>
              <?php endforeach; ?>
            </select>
            <select id="filterLopTinhTrang" class="form-select form-select-sm">
              <option value="">— Tình trạng —</option>
              <option>Đang học</option>
              <option>Chuẩn bị tốt nghiệp</option>
              <option>Đã tốt nghiệp</option>
            </select>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
              <input class="form-control border-start-0" id="searchLop" placeholder="Tìm lớp...">
            </div>
            <button class="btn btn-outline-secondary btn-sm" id="btnExportCSV"><i class="bi bi-download me-1"></i>CSV</button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalLop"><i class="bi bi-plus-lg me-1"></i>Thêm lớp</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tblLop">
            <thead>
              <tr>
                <th>#</th>
                <th>Mã lớp</th>
                <th>Tên lớp</th>
                <th>Khoa</th>
                <th>Niên khóa</th>
                <th>Hệ</th>
                <th>Sĩ số</th>
                <th>Cố vấn HT</th>
                <th>Tình trạng</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lops as $i=>$l): ?>
              <tr data-ma-khoa="<?= htmlspecialchars($l['ma_khoa']) ?>" data-magv="<?= htmlspecialchars($l['cvht_magv'] ?? '') ?>">
                <td><?= $i+1 ?></td>
                <td class="td-malop"><?= htmlspecialchars($l['ma_lop']) ?></td>
                <td><?= htmlspecialchars($l['ten_lop']) ?></td>
                <td class="td-tenkhoa"><?= htmlspecialchars(tênKhoa($l['ma_khoa'],$khoas)) ?></td>
                <td><?= htmlspecialchars($l['nien_khoa']) ?></td>
                <td><?= htmlspecialchars($l['he']) ?></td>
                <td class="text-center"><span class="badge bg-secondary badge-pill px-3"><?= (int)$l['si_so'] ?></span></td>
                <td class="td-cvht">
                  <?php if($l['cvht_magv']): ?>
                    <span class="badge bg-success"><i class="bi bi-person-check me-1"></i><?= htmlspecialchars(tênGV($l['cvht_magv'],$giang_viens)) ?></span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Chưa phân công</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($l['tinh_trang']==='Đang học'): ?>
                    <span class="badge bg-primary">Đang học</span>
                  <?php elseif($l['tinh_trang']==='Chuẩn bị tốt nghiệp'): ?>
                    <span class="badge bg-info text-dark">Chuẩn bị tốt nghiệp</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Đã tốt nghiệp</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm btn-icon btn-assign" title="Phân công" data-malop="<?= $l['ma_lop'] ?>" data-makhoa="<?= $l['ma_khoa'] ?>">
                    <i class="bi bi-diagram-3"></i>
                  </button>
                  <button class="btn btn-outline-success btn-sm btn-icon" title="Sửa"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-outline-danger btn-sm btn-icon" title="Xóa"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- GIẢNG VIÊN -->
    <div class="tab-pane fade" id="pane-gv" role="tabpanel">
      <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center section-actions mb-3 gap-2">
          <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Quản lý giảng viên</h5>
          <div class="d-flex gap-2">
            <select id="filterGVKhoa" class="form-select form-select-sm">
              <option value="">— Lọc theo khoa —</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>"><?= $k['ten_khoa'] ?></option>
              <?php endforeach; ?>
            </select>
            <select id="filterGVTrangThai" class="form-select form-select-sm">
              <option value="">— Trạng thái —</option>
              <option>Rảnh</option>
              <option>Bận</option>
            </select>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
              <input class="form-control border-start-0" id="searchGV" placeholder="Tìm giảng viên...">
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalGV"><i class="bi bi-plus-lg me-1"></i>Thêm giảng viên</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tblGV">
            <thead>
              <tr>
                <th>#</th>
                <th>Mã GV</th>
                <th>Họ tên</th>
                <th>Khoa</th>
                <th>Chuyên môn</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Số lớp</th>
                <th>Trạng thái</th>
                <th class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($giang_viens as $i=>$g): ?>
              <tr data-ma-khoa="<?= $g['ma_khoa'] ?>" data-trangthai="<?= $g['trang_thai'] ?>">
                <td><?= $i+1 ?></td>
                <td class="td-magv"><?= htmlspecialchars($g['magv']) ?></td>
                <td class="td-hoten"><?= htmlspecialchars($g['hoten']) ?></td>
                <td class="td-tenkhoa"><?= htmlspecialchars(tênKhoa($g['ma_khoa'],$khoas)) ?></td>
                <td><?= htmlspecialchars($g['chuyenmon']) ?></td>
                <td><?= htmlspecialchars($g['email']) ?></td>
                <td><?= htmlspecialchars($g['sdt']) ?></td>
                <td class="td-solop text-center"><span class="badge bg-secondary"><?= (int)$g['so_lop'] ?></span></td>
                <td>
                  <?php if($g['trang_thai']==='Rảnh'): ?>
                    <span class="badge bg-success">Rảnh</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Bận</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm btn-icon btn-quick-assign" data-magv="<?= $g['magv'] ?>" title="Phân công nhanh"><i class="bi bi-diagram-3"></i></button>
                  <button class="btn btn-outline-success btn-sm btn-icon" title="Sửa"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-outline-danger btn-sm btn-icon" title="Xóa"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- PHÂN CÔNG (NHANH LỚP CHƯA PC) -->
    <div class="tab-pane fade" id="pane-phancong" role="tabpanel">
      <div class="card p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center section-actions mb-3 gap-2">
          <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Phân công cố vấn học tập</h5>
          <div class="d-flex gap-2">
            <select id="pcKhoa" class="form-select form-select-sm">
              <option value="">— Lọc theo khoa —</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>"><?= $k['ten_khoa'] ?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn btn-outline-primary btn-sm" id="btnPCAll"><i class="bi bi-magic me-1"></i>Gợi ý tự động</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle" id="tblPC">
            <thead>
              <tr>
                <th>#</th>
                <th>Mã lớp</th>
                <th>Tên lớp</th>
                <th>Khoa</th>
                <th>Gợi ý GV (rảnh)</th>
                <th class="text-center">Phân công</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $rows=0;
              foreach($lops as $i=>$l):
                if(!$l['cvht_magv']) {
                  $rows++;
                  // gợi ý: gv rảnh cùng khoa (nếu có)
                  $goiY = '';
                  foreach($giang_viens as $g){ if($g['ma_khoa']===$l['ma_khoa'] && $g['trang_thai']==='Rảnh') { $goiY = $g['hoten'].' ('.$g['magv'].')'; break; } }
              ?>
              <tr data-malop="<?= $l['ma_lop'] ?>" data-makhoa="<?= $l['ma_khoa'] ?>">
                <td><?= $rows ?></td>
                <td><?= htmlspecialchars($l['ma_lop']) ?></td>
                <td><?= htmlspecialchars($l['ten_lop']) ?></td>
                <td><?= htmlspecialchars(tênKhoa($l['ma_khoa'],$khoas)) ?></td>
                <td><?= $goiY ? '<span class="badge bg-success">'.$goiY.'</span>' : '<span class="badge bg-secondary">—</span>' ?></td>
                <td class="text-center">
                  <button class="btn btn-primary btn-sm btn-assign" data-malop="<?= $l['ma_lop'] ?>" data-makhoa="<?= $l['ma_khoa'] ?>"><i class="bi bi-check2-circle me-1"></i>Phân công</button>
                </td>
              </tr>
              <?php } endforeach; if($rows===0): ?>
                <tr><td colspan="6" class="text-center text-muted">Tất cả lớp đã có cố vấn.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: THÊM KHOA -->
<div class="modal fade" id="modalKhoa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formKhoa">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-building me-2"></i>Thêm khoa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Mã khoa</label><input class="form-control" name="ma_khoa" required></div>
          <div class="col-md-8"><label class="form-label">Tên khoa</label><input class="form-control" name="ten_khoa" required></div>
          <div class="col-md-6"><label class="form-label">Trưởng khoa</label><input class="form-control" name="truong_khoa"></div>
          <div class="col-md-6"><label class="form-label">SĐT</label><input class="form-control" name="sdt"></div>
          <div class="col-12"><label class="form-label">Email</label><input class="form-control" type="email" name="email"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: THÊM LỚP -->
<div class="modal fade" id="modalLop" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formLop">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-mortarboard me-2"></i>Thêm lớp</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Mã lớp</label><input class="form-control" name="ma_lop" required></div>
          <div class="col-md-8"><label class="form-label">Tên lớp</label><input class="form-control" name="ten_lop" required></div>
          <div class="col-md-6">
            <label class="form-label">Khoa</label>
            <select class="form-select" name="ma_khoa" required>
              <option value="">— Chọn khoa —</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>"><?= $k['ten_khoa'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6"><label class="form-label">Niên khóa</label><input class="form-control" name="nien_khoa" placeholder="VD: 2022–2026" required></div>
          <div class="col-md-4"><label class="form-label">Hệ</label><input class="form-control" name="he" value="Chính quy"></div>
          <div class="col-md-4"><label class="form-label">Sĩ số</label><input class="form-control" type="number" name="si_so" min="1" value="40"></div>
          <div class="col-md-4">
            <label class="form-label">Tình trạng</label>
            <select class="form-select" name="tinh_trang"><option>Đang học</option><option>Chuẩn bị tốt nghiệp</option><option>Đã tốt nghiệp</option></select>
          </div>
          <div class="col-12"><label class="form-label">Ghi chú</label><textarea class="form-control" rows="2" name="ghi_chu"></textarea></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: THÊM GIẢNG VIÊN -->
<div class="modal fade" id="modalGV" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formGV">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Thêm giảng viên</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Mã GV</label><input class="form-control" name="magv" required></div>
          <div class="col-md-8"><label class="form-label">Họ tên</label><input class="form-control" name="hoten" required></div>
          <div class="col-md-6">
            <label class="form-label">Khoa</label>
            <select class="form-select" name="ma_khoa" required>
              <option value="">— Chọn khoa —</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>"><?= $k['ten_khoa'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6"><label class="form-label">Chuyên môn</label><input class="form-control" name="chuyenmon"></div>
          <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email"></div>
          <div class="col-md-6"><label class="form-label">SĐT</label><input class="form-control" name="sdt"></div>
          <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="trang_thai"><option>Rảnh</option><option>Bận</option></select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: PHÂN CÔNG -->
<div class="modal fade" id="modalAssign" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="formAssign">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-diagram-3 me-2"></i>Phân công Cố vấn học tập</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2"><b>Lớp:</b> <span id="assignLop"></span></div>
        <input type="hidden" name="ma_lop">
        <input type="hidden" name="ma_khoa">
        <label class="form-label">Chọn giảng viên</label>
        <select class="form-select" name="magv" id="assignGV" required></select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-primary">Lưu phân công</button>
      </div>
    </form>
  </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toasts"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ======= DATA JS (đẩy từ PHP) =======
const KHOAS = <?= json_encode($khoas, JSON_UNESCAPED_UNICODE) ?>;
const GVS   = <?= json_encode($giang_viens, JSON_UNESCAPED_UNICODE) ?>;
const LOPS  = <?= json_encode($lops, JSON_UNESCAPED_UNICODE) ?>;

// ======= TIỆN ÍCH GIAO DIỆN =======
function showToast(msg, type='success'){
  const id = 't'+Date.now();
  const html = `
  <div id="${id}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">${msg}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>`;
  const box = document.querySelector('.toasts'); box.insertAdjacentHTML('beforeend', html);
  new bootstrap.Toast(document.getElementById(id), {delay:2200}).show();
}

// ======= TÌM KIẾM / LỌC KHOA =======
document.getElementById('searchKhoa').addEventListener('keyup', function(){
  const q = this.value.toLowerCase();
  document.querySelectorAll('#tblKhoa tbody tr').forEach(tr=>{
    tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
});

// ======= TÌM KIẾM / LỌC LỚP =======
function filterLop(){
  const q = document.getElementById('searchLop').value.toLowerCase();
  const mk = document.getElementById('filterLopKhoa').value;
  const tt = document.getElementById('filterLopTinhTrang').value;
  document.querySelectorAll('#tblLop tbody tr').forEach(tr=>{
    const text = tr.innerText.toLowerCase();
    const passQ  = text.includes(q);
    const passK  = !mk || tr.dataset.maKhoa===mk;
    const passTT = !tt || text.includes(tt.toLowerCase());
    tr.style.display = (passQ && passK && passTT) ? '' : 'none';
  });
}
['searchLop','filterLopKhoa','filterLopTinhTrang'].forEach(id=>{
  document.getElementById(id).addEventListener('input', filterLop);
});

// ======= EXPORT CSV LỚP =======
document.getElementById('btnExportCSV').addEventListener('click', ()=>{
  const rows = [...document.querySelectorAll('#tblLop thead tr, #tblLop tbody tr')].filter(r=>r.style.display!=='none');
  const csv = rows.map(r=>[...r.querySelectorAll('th,td')].map(c=>('"'+c.innerText.replaceAll('"','""')+'"')).join(',')).join('\n');
  const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href=url; a.download='danh_sach_lop.csv'; a.click();
  URL.revokeObjectURL(url);
});

// ======= TÌM KIẾM / LỌC GIẢNG VIÊN =======
function filterGV(){
  const q  = document.getElementById('searchGV').value.toLowerCase();
  const mk = document.getElementById('filterGVKhoa').value;
  const st = document.getElementById('filterGVTrangThai').value;
  document.querySelectorAll('#tblGV tbody tr').forEach(tr=>{
    const passQ = tr.innerText.toLowerCase().includes(q);
    const passK = !mk || tr.dataset.maKhoa===mk;
    const passS = !st || tr.dataset.trangthai===st;
    tr.style.display = (passQ && passK && passS) ? '' : 'none';
  })
}
['searchGV','filterGVKhoa','filterGVTrangThai'].forEach(id=>{
  document.getElementById(id).addEventListener('input', filterGV);
});

// ======= MỞ MODAL PHÂN CÔNG TỪ LỚP =======
const assignModal = new bootstrap.Modal('#modalAssign');
document.querySelectorAll('.btn-assign').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const ma_lop  = btn.dataset.malop;
    const ma_khoa = btn.dataset.makhoa;
    const row = btn.closest('tr');
    document.querySelector('#formAssign [name=ma_lop]').value = ma_lop;
    document.querySelector('#formAssign [name=ma_khoa]').value = ma_khoa;
    document.getElementById('assignLop').innerText = row.querySelector('.td-malop').innerText+' — '+row.children[2].innerText;

    // fill GV theo khoa + ưu tiên trạng thái rảnh
    const sel = document.getElementById('assignGV'); sel.innerHTML='';
    const list = GVS.filter(g=>g.ma_khoa===ma_khoa).sort((a,b)=>{
      return (a.trang_thai==='Rảnh'?0:1)-(b.trang_thai==='Rảnh'?0:1);
    });
    if(list.length===0){ sel.innerHTML='<option value="">(Không có GV thuộc khoa này)</option>'; }
    else{
      list.forEach(g=>{
        const opt = document.createElement('option');
        opt.value = g.magv;
        opt.textContent = `${g.hoten} (${g.magv}) — ${g.trang_thai}`;
        sel.appendChild(opt);
      });
    }
    assignModal.show();
  })
});

// ======= LƯU PHÂN CÔNG =======
document.getElementById('formAssign').addEventListener('submit', (e)=>{
  e.preventDefault();
  const f = e.target;
  const ma_lop  = f.ma_lop.value;
  const ma_khoa = f.ma_khoa.value;
  const magv    = f.magv.value;
  if(!magv) return;

  // cập nhật bảng Lớp (client-side)
  const row = [...document.querySelectorAll('#tblLop tbody tr')].find(r=>r.querySelector('.td-malop').innerText===ma_lop);
  if(row){
    row.dataset.magv = magv;
    row.querySelector('.td-cvht').innerHTML = `<span class="badge bg-success"><i class="bi bi-person-check me-1"></i>${(GVS.find(g=>g.magv===magv)||{}).hoten||magv}</span>`;
  }

  // cập nhật thống kê "chưa PC"
  let notAssigned = 0;
  document.querySelectorAll('#tblLop tbody tr').forEach(r=>{ if(!r.dataset.magv) notAssigned++; });
  document.getElementById('statChuaPC').innerText = notAssigned;

  // cập nhật bảng “Phân công”
  const trPC = [...document.querySelectorAll('#tblPC tbody tr')].find(r=>r.dataset.malop===ma_lop);
  if(trPC){ trPC.remove(); if(!document.querySelector('#tblPC tbody tr')) {
    document.querySelector('#tblPC tbody').innerHTML='<tr><td colspan="6" class="text-center text-muted">Tất cả lớp đã có cố vấn.</td></tr>';
  }}

  // cập nhật bảng Giảng viên: +1 số lớp
  const gvRow = [...document.querySelectorAll('#tblGV tbody tr')].find(r=>r.querySelector('.td-magv').innerText===magv);
  if(gvRow){
    const badge = gvRow.querySelector('.td-solop .badge');
    badge.innerText = parseInt(badge.innerText||'0') + 1;
  }

  assignModal.hide();
  showToast('Đã phân công cố vấn cho lớp '+ma_lop, 'success');
});

// ======= GỢI Ý TỰ ĐỘNG (demo) =======
document.getElementById('btnPCAll').addEventListener('click', ()=>{
  const rows = document.querySelectorAll('#tblPC tbody tr');
  if(!rows.length) return;
  rows.forEach(r=>{
    const btn = r.querySelector('.btn-assign'); if(btn) btn.click();
    const sel = document.getElementById('assignGV'); // chọn option đầu tiên
    if(sel && sel.options.length){ document.getElementById('formAssign').dispatchEvent(new Event('submit', {cancelable:true})); }
  });
  showToast('Đã gợi ý phân công tự động (demo).', 'info');
});

// ======= THÊM KHOA (demo client) =======
document.getElementById('formKhoa').addEventListener('submit', (e)=>{
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = Object.fromEntries(fd.entries());
  const tbody = document.querySelector('#tblKhoa tbody');
  const idx = tbody.children.length+1;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${idx}</td>
    <td>${data.ma_khoa}</td>
    <td>${data.ten_khoa}</td>
    <td>${data.truong_khoa||''}</td>
    <td>${data.email||''}</td>
    <td>${data.sdt||''}</td>
    <td class="text-center">
      <button class="btn btn-outline-success btn-sm btn-icon"><i class="bi bi-pencil"></i></button>
      <button class="btn btn-outline-danger btn-sm btn-icon"><i class="bi bi-trash"></i></button>
    </td>`;
  tbody.appendChild(tr);
  bootstrap.Modal.getInstance(document.getElementById('modalKhoa')).hide();
  e.target.reset();
  showToast('Đã thêm khoa mới.', 'success');
});

// ======= THÊM LỚP (demo client) =======
document.getElementById('formLop').addEventListener('submit', (e)=>{
  e.preventDefault();
  const fd = new FormData(e.target); const d = Object.fromEntries(fd.entries());
  const tbody = document.querySelector('#tblLop tbody');
  const idx = tbody.children.length+1;
  const tenKhoa = (KHOAS.find(k=>k.ma_khoa===d.ma_khoa)||{}).ten_khoa || d.ma_khoa;
  const tr = document.createElement('tr');
  tr.dataset.maKhoa = d.ma_khoa; tr.dataset.magv = '';
  tr.innerHTML = `
    <td>${idx}</td>
    <td class="td-malop">${d.ma_lop}</td>
    <td>${d.ten_lop}</td>
    <td class="td-tenkhoa">${tenKhoa}</td>
    <td>${d.nien_khoa}</td>
    <td>${d.he||'Chính quy'}</td>
    <td class="text-center"><span class="badge bg-secondary badge-pill px-3">${d.si_so||'0'}</span></td>
    <td class="td-cvht"><span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Chưa phân công</span></td>
    <td><span class="badge bg-primary">${d.tinh_trang||'Đang học'}</span></td>
    <td class="text-center">
      <button class="btn btn-outline-primary btn-sm btn-icon btn-assign" data-malop="${d.ma_lop}" data-makhoa="${d.ma_khoa}"><i class="bi bi-diagram-3"></i></button>
      <button class="btn btn-outline-success btn-sm btn-icon"><i class="bi bi-pencil"></i></button>
      <button class="btn btn-outline-danger btn-sm btn-icon"><i class="bi bi-trash"></i></button>
    </td>`;
  tbody.appendChild(tr);
  // gắn lại handler assign cho nút mới
  tr.querySelector('.btn-assign').addEventListener('click', (btnEvt)=> {
    const b = btnEvt.currentTarget;
    document.querySelector('#formAssign [name=ma_lop]').value = d.ma_lop;
    document.querySelector('#formAssign [name=ma_khoa]').value = d.ma_khoa;
    document.getElementById('assignLop').innerText = d.ma_lop+' — '+d.ten_lop;
    const sel = document.getElementById('assignGV'); sel.innerHTML='';
    const list = GVS.filter(g=>g.ma_khoa===d.ma_khoa);
    if(list.length===0) sel.innerHTML='<option value="">(Không có GV thuộc khoa này)</option>';
    else list.forEach(g=> sel.insertAdjacentHTML('beforeend', `<option value="${g.magv}">${g.hoten} (${g.magv}) — ${g.trang_thai}</option>`));
    assignModal.show();
  });

  // cập nhật thống kê lớp chưa phân công
  document.getElementById('statChuaPC').innerText = parseInt(document.getElementById('statChuaPC').innerText)+1;

  bootstrap.Modal.getInstance(document.getElementById('modalLop')).hide();
  e.target.reset();
  showToast('Đã thêm lớp mới.', 'success');
});

// ======= THÊM GIẢNG VIÊN (demo client) =======
document.getElementById('formGV').addEventListener('submit', (e)=>{
  e.preventDefault();
  const d = Object.fromEntries(new FormData(e.target).entries());
  const tbody = document.querySelector('#tblGV tbody');
  const idx = tbody.children.length+1;
  const tenKhoa = (KHOAS.find(k=>k.ma_khoa===d.ma_khoa)||{}).ten_khoa || d.ma_khoa;
  const tr = document.createElement('tr');
  tr.dataset.maKhoa = d.ma_khoa; tr.dataset.trangthai = d.trang_thai||'Rảnh';
  tr.innerHTML = `
    <td>${idx}</td>
    <td class="td-magv">${d.magv}</td>
    <td class="td-hoten">${d.hoten}</td>
    <td class="td-tenkhoa">${tenKhoa}</td>
    <td>${d.chuyenmon||''}</td>
    <td>${d.email||''}</td>
    <td>${d.sdt||''}</td>
    <td class="td-solop text-center"><span class="badge bg-secondary">0</span></td>
    <td>${(d.trang_thai||'Rảnh')==='Rảnh'?'<span class="badge bg-success">Rảnh</span>':'<span class="badge bg-warning text-dark">Bận</span>'}</td>
    <td class="text-center">
      <button class="btn btn-outline-primary btn-sm btn-icon btn-quick-assign" data-magv="${d.magv}"><i class="bi bi-diagram-3"></i></button>
      <button class="btn btn-outline-success btn-sm btn-icon"><i class="bi bi-pencil"></i></button>
      <button class="btn btn-outline-danger btn-sm btn-icon"><i class="bi bi-trash"></i></button>
    </td>`;
  tbody.appendChild(tr);
  bootstrap.Modal.getInstance(document.getElementById('modalGV')).hide();
  e.target.reset();
  showToast('Đã thêm giảng viên mới.', 'success');
});

// ======= PHÂN CÔNG NHANH TỪ BẢNG GV =======
document.querySelectorAll('.btn-quick-assign').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const magv = btn.dataset.magv;
    // mở tab lớp
    document.querySelector('#tab-lop').click();
    // lọc theo khoa của GV
    const row = btn.closest('tr');
    const mk = row.dataset.maKhoa;
    document.getElementById('filterLopKhoa').value = mk;
    document.getElementById('filterLopTinhTrang').value = '';
    document.getElementById('searchLop').value = '';
    filterLop();
    showToast('Đã lọc lớp theo khoa giảng viên để phân công.', 'info');
  });
});

// ======= LỌC BẢNG PHÂN CÔNG THEO KHOA =======
document.getElementById('pcKhoa').addEventListener('change', function(){
  const mk = this.value;
  document.querySelectorAll('#tblPC tbody tr').forEach(tr=>{
    tr.style.display = !mk || tr.dataset.makhoa===mk ? '' : 'none';
  });
});
</script>
</body>
</html>
