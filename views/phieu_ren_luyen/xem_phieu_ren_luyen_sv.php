<?php

require_once __DIR__ . '/../../views/headers/header_login.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_sinhvien') {
  header('Location: ' . BASE_URL . 'bodys_login');
  exit;
}

$conn = Database::connect();
$mssv = $_SESSION['user']['id'] ?? '0022410322';

// 🔍 Lấy phiếu mới nhất của sinh viên
$stmt = $conn->prepare("SELECT * FROM phieu_ren_luyen WHERE mssv = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$mssv]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$r) {
  echo '<div class="container mt-5 text-center"><h5>⚠️ Chưa có phiếu rèn luyện nào được lưu!</h5></div>';
  exit;
}

// 🎓 Xếp loại
$tong = floatval($r['tong_diem']);
$xl = 'Kém'; $color = '#dc3545';
if ($tong >= 90) { $xl = 'Xuất sắc'; $color = '#007bff'; }
elseif ($tong >= 80) { $xl = 'Tốt'; $color = '#28a745'; }
elseif ($tong >= 65) { $xl = 'Khá'; $color = '#ffc107'; }
elseif ($tong >= 50) { $xl = 'Trung bình'; $color = '#adb5bd'; }
elseif ($tong >= 35) { $xl = 'Yếu'; $color = '#fd7e14'; }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Xem phiếu rèn luyện</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/phieu_ren_luyen_sv.css">

<style>
.value-badge {
  display: inline-block;
  min-width: 34px;
  padding: 4px 0;
  font-weight: 600;
  text-align: center;
  border-radius: 6px;
  color: #fff;
  background-color: #0d6efd;
}
.table th {
  background: linear-gradient(180deg, #eaf1ff 0%, #dbe8ff 100%);
  color: #002b80;
  text-align: center;
}
.section-header {
  background: #f4f6f9;
  font-weight: 600;
  color: #222;
  border-top: 2px solid #002b80 !important;
}
</style>
</head>

<body>
<div class="container-box">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= BASE_URL ?>views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
      ⬅ Quay lại
    </a>
    <h3 class="flex-grow-1 text-center m-0">PHIẾU RÈN LUYỆN SINH VIÊN</h3>
    <div style="width:80px;"></div>
  </div>

  <!-- Thông tin sinh viên -->
  <div class="info-box mb-3">
    <div class="row">
      <div class="col-md-6">
        <div class="info-line"><strong>Họ và tên:</strong> <?= htmlspecialchars($r['ho_ten']) ?></div>
        <div class="info-line"><strong>MSSV:</strong> <?= htmlspecialchars($r['mssv']) ?></div>
        <div class="info-line"><strong>Lớp:</strong> <?= htmlspecialchars($r['lop']) ?></div>
        <div class="info-line"><strong>Khoa:</strong> <?= htmlspecialchars($r['khoa']) ?></div>
      </div>
      <div class="col-md-6">
        <div class="info-line"><strong>Học kỳ:</strong> <?= htmlspecialchars($r['hoc_ky']) ?></div>
        <div class="info-line"><strong>Năm học:</strong> <?= $r['nam_bd'] . ' - ' . $r['nam_kt'] ?></div>
        <div class="info-line"><strong>Tổng điểm:</strong> <span class="value-box"><?= $tong ?></span></div>
        <div class="info-line"><strong>Xếp loại:</strong> <span class="fw-bold" style="color:<?= $color ?>"><?= $xl ?></span></div>
      </div>
    </div>
  </div>

  <!-- Bảng hiển thị -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="width:50px;">TT</th>
          <th>Nội dung đánh giá</th>
          <th style="width:90px;">Điểm tối đa</th>
          <th style="width:130px;">Sinh viên<br>tự đánh giá</th>
        </tr>
      </thead>
      <tbody>

<!-- I -->
<tr class="section-header">
  <td colspan="4">
    <strong>I. Đánh giá về ý thức tham gia học tập</strong>
    <span style="font-style: italic;">(Điểm tối đa 20 điểm)</span>
  </td>
</tr>

<!-- I.1 -->
<tr>
  <td>1</td>
  <td class="text-start">
    Đi học đầy đủ, đúng giờ, chuẩn bị bài đầy đủ, tích cực phát biểu trong giờ học<br>
    <i>(đi học muộn, nghỉ học không lý do, bỏ giờ: trừ 1 điểm/1 lần)</i>
  </td>
  <td>05</td>
  <td class="text-center">
    <?= $r['diem_i1_hoc_tap'] == 0 ? '' : '<span class="value-badge">'.$r['diem_i1_hoc_tap'].'</span>' ?>
  </td>
</tr>

<!-- I.2 -->
<tr>
  <td>2</td>
  <td colspan="3" class="text-start">Ý thức, thái độ tham gia</td>
</tr>

<?php
$subI2 = [
  'diem_i2_hoc_thuat' => 'Các hoạt động học thuật (Câu lạc bộ)',
  'diem_i2_ngoai_khoa' => 'Hoạt động ngoại khóa',
  'diem_i2_ky_nang_mem' => 'Rèn luyện kỹ năng mềm',
  'diem_i2_nc_khoa_hoc' => 'Hoạt động nghiên cứu khoa học',
  'diem_i2_cuoc_thi' => 'Các cuộc thi do Nhà trường, Đoàn Thanh niên, Hội Sinh viên phát động'
];
foreach ($subI2 as $key => $label): ?>
<tr>
  <td></td>
  <td class="text-start"><i>– <?= $label ?></i></td>
  <td>01</td>
  <td class="text-center">
    <?= $r[$key] == 0 ? '' : '<span class="value-badge">'.$r[$key].'</span>' ?>
  </td>
</tr>
<?php endforeach; ?>

<!-- I.3 -->
<tr>
  <td>3</td>
  <td class="text-start">Tinh thần vượt khó, phấn đấu vươn lên trong học tập</td>
  <td>02</td>
  <td class="text-center">
    <?= $r['diem_i3_vuot_kho'] == 0 ? '' : '<span class="value-badge">'.$r['diem_i3_vuot_kho'].'</span>' ?>
  </td>
</tr>

<!-- I.4 -->
<tr>
  <td>4</td>
  <td class="text-start">Tích cực đánh giá hoạt động giảng dạy của giảng viên, khóa đào tạo</td>
  <td>02</td>
  <td class="text-center">
    <?= $r['diem_i4_danh_gia_gv'] == 0 ? '' : '<span class="value-badge">'.$r['diem_i4_danh_gia_gv'].'</span>' ?>
  </td>
</tr>

<!-- I.5 -->
<tr>
  <td>5</td>
  <td colspan="3" class="text-start">Kết quả học tập</td>
</tr>

<?php
$gpaOptions = [
  ['Điểm TBCHT: 2,00 đến 2,49', 3],
  ['Điểm TBCHT: 2,50 đến 3,19', 4],
  ['Điểm TBCHT: 3,20 đến 3,59', 5],
  ['Điểm TBCHT: 3,60 đến 4,00', 6]
];
foreach ($gpaOptions as $g): ?>
<tr>
  <td></td>
  <td class="text-start"><i>– <?= $g[0] ?></i></td>
  <td><?= $g[1] ?></td>
  <td class="text-center">
    <?= ($r['diem_i5_tbc'] == $g[1]) ? '<span class="value-badge">'.$g[1].'</span>' : '' ?>
  </td>
</tr>
<?php endforeach; ?>

      </tbody>
    </table>
  </div>
</div>

</body>
</html>
