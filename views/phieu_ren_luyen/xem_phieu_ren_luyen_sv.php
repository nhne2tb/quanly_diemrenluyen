<?php
// views/phieu_ren_luyen/xem_phieu_ren_luyen_sv.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// ==================== KIỂM TRA ====================
if (!isset($_GET['id']) || !isset($_GET['table'])) {
    die("<div class='alert alert-danger m-5'>Thiếu tham số!</div>");
}

$id    = intval($_GET['id']);
$table = $_GET['table'];

$conn = Database::connect();

// ==================== LẤY DỮ LIỆU PHIẾU ====================
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
$stmt->execute([$id]);
$phieu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$phieu) {
    die("<div class='alert alert-danger m-5'>Không tìm thấy phiếu!</div>");
}

// ==================== LẤY DỮ LIỆU SINH VIÊN ====================
$lopTable = "sv_" . strtolower($phieu['khoa']) . "_" . strtolower($phieu['lop']);

$stmt2 = $conn->prepare("SELECT * FROM `$lopTable` WHERE mssv = ?");
$stmt2->execute([$phieu['mssv']]);

$sv = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("<div class='alert alert-danger m-5'>Không tìm thấy sinh viên trong bảng lớp!</div>");
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Xem phiếu rèn luyện</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background: #f0f4ff;
  font-family: "Segoe UI", sans-serif;
}
.container-box {
  background: #fff;
  max-width: 1100px;
  margin: 35px auto;
  padding: 35px 45px;
  border-radius: 18px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.07);
  border: 1px solid #e4eaff;
}

.info-line {
  display: flex;
  justify-content: space-between;
  margin-bottom: 6px;
}
.info-line div {
  font-weight: 600;
  color: #004aad;
}
.section-header {
  background: #e6edff;
  color: #004aad;
  font-weight: bold;
}
.value-box {
  font-weight: bold;
  color: #004aad;
}
.table thead th {
  background: #f2f6ff;
  font-weight: bold;
  text-align: center;
  vertical-align: middle !important;
}
.table-bordered td, 
.table-bordered th {
  vertical-align: middle;
}
</style>
</head>

<body>

<div class="container-box">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center">
    <a href="<?= BASE_URL ?>index.php?route=ket_qua_ren_luyen_sv" 
       class="btn btn-outline-primary btn-sm rounded-pill">
        ← Quay lại
    </a>

    <h3 class="text-center flex-grow-1 m-0" style="color:#004aad; font-weight:800;">
        PHIẾU ĐÁNH GIÁ RÈN LUYỆN – CHI TIẾT
    </h3>

    <div style="width:90px;"></div>
</div>

<hr>

<!-- THÔNG TIN SINH VIÊN -->
<div class="info-box">
  <div class="row">

    <div class="col-md-6">
      <div class="info-line"><strong>Họ và tên:</strong><div><?= htmlspecialchars($sv['ho_ten']) ?></div></div>
      <div class="info-line"><strong>Ngày sinh:</strong><div><?= !empty($sv['ngay_sinh']) ? date('d/m/Y', strtotime($sv['ngay_sinh'])) : '-' ?></div></div>
      <div class="info-line"><strong>Lớp:</strong><div><?= htmlspecialchars($sv['lop']) ?></div></div>
      <div class="info-line"><strong>Khóa học:</strong><div><?= htmlspecialchars($sv['nien_khoa']) ?></div></div>
      <div class="info-line"><strong>Học kỳ:</strong><div><?= htmlspecialchars($phieu['hoc_ky']) ?></div></div>
    </div>

    <div class="col-md-6">
      <div class="info-line"><strong>MSSV:</strong><div><?= htmlspecialchars($sv['mssv']) ?></div></div>
      <div class="info-line"><strong>Giới tính:</strong><div><?= htmlspecialchars($sv['gioi_tinh']) ?></div></div>
      <div class="info-line"><strong>Khoa:</strong><div><?= htmlspecialchars($sv['ma_khoa']) ?></div></div>
      <div class="info-line"><strong>Năm học:</strong><div><?= htmlspecialchars($phieu['nam_bd']) ?> – <?= htmlspecialchars($phieu['nam_kt']) ?></div></div>
    </div>

  </div>
</div>

<!-- BẢNG CHI TIẾT -->
<div class="table-responsive mt-4">
<table class="table table-bordered">

<thead>
<tr>
  <th style="width:55px;">TT</th>
  <th class="text-start">Nội dung đánh giá</th>
  <th style="width:110px;">Điểm tối đa</th>
  <th style="width:150px;">Điểm đã đánh giá</th>
</tr>
</thead>

<tbody>

<!-- I -->
<tr class="section-header">
  <td colspan="4">
    I. Đánh giá về ý thức tham gia học tập 
    <span style="font-style:italic;">(Điểm tối đa 20 điểm)</span>
  </td>
</tr>

<!-- I.1 -->
<tr>
  <td class="text-center">1</td>
  <td class="text-start">
    Đi học đầy đủ, đúng giờ, chuẩn bị bài đầy đủ, tích cực phát biểu<br>
    <i>(đi học muộn, nghỉ học không lý do, bỏ giờ: trừ 1 điểm/1 lần)</i>
  </td>
  <td class="text-center">05</td>
  <td class="text-center value-box"><?= $phieu['diem_i1_hoc_tap'] ?></td>
</tr>

<!-- I.2 -->
<tr><td class="text-center">2</td><td colspan="3" class="text-start">Ý thức, thái độ tham gia</td></tr>

<tr><td></td><td><i>– Các hoạt động học thuật</i></td><td class="text-center">01</td><td class="text-center value-box"><?= $phieu['diem_i2_hoc_thuat'] ?></td></tr>
<tr><td></td><td><i>– Hoạt động ngoại khóa</i></td><td class="text-center">01</td><td class="text-center value-box"><?= $phieu['diem_i2_ngoai_khoa'] ?></td></tr>
<tr><td></td><td><i>– Rèn luyện kỹ năng mềm</i></td><td class="text-center">01</td><td class="text-center value-box"><?= $phieu['diem_i2_ky_nang_mem'] ?></td></tr>
<tr><td></td><td><i>– Nghiên cứu khoa học</i></td><td class="text-center">01</td><td class="text-center value-box"><?= $phieu['diem_i2_nc_khoa_hoc'] ?></td></tr>
<tr><td></td><td><i>– Các cuộc thi của Trường/Đoàn/Hội</i></td><td class="text-center">01</td><td class="text-center value-box"><?= $phieu['diem_i2_cuoc_thi'] ?></td></tr>

<!-- I.3 -->
<tr>
  <td class="text-center">3</td>
  <td>Tinh thần vượt khó</td>
  <td class="text-center">02</td>
  <td class="text-center value-box"><?= $phieu['diem_i3_vuot_kho'] ?></td>
</tr>

<!-- I.4 -->
<tr>
  <td class="text-center">4</td>
  <td>Tích cực đánh giá hoạt động giảng dạy</td>
  <td class="text-center">02</td>
  <td class="text-center value-box"><?= $phieu['diem_i4_danh_gia_gv'] ?></td>
</tr>

<!-- I.5 -->
<tr>
  <td class="text-center">5</td>
  <td colspan="3">Kết quả học tập <i>(chọn 1 trong 4)</i></td>
</tr>

<?php
$gpaList = [
  3 => 'Điểm TBCHT: 2,00 – 2,49',
  4 => 'Điểm TBCHT: 2,50 – 3,19',
  5 => 'Điểm TBCHT: 3,20 – 3,59',
  6 => 'Điểm TBCHT: 3,60 – 4,00'
];
foreach ($gpaList as $val => $label):
?>
<tr>
  <td></td>
  <td><i>– <?= $label ?></i></td>
  <td class="text-center"><?= $val ?></td>
  <td class="text-center value-box"><?= ($phieu['diem_i5_tbc'] == $val) ? $val : '' ?></td>
</tr>
<?php endforeach; ?>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4">
    <strong>* Điểm thưởng</strong> 
    <span style="font-style:italic;">(tối đa 20 điểm)</span>
    <div style="margin-left:20px; margin-top:5px;">
      – Cấp khoa: 01 điểm<br>
      – Cấp trường: 02 điểm<br>
      – Cấp tỉnh: 03 điểm
    </div>

    <div style="margin-top:8px;">
      <strong>Điểm đã cộng:</strong>
      <span class="value-box"><?= $phieu['diem_i_thuong'] ?></span>
    </div>
  </td>
</tr>

<!-- II -->
<tr class="section-header">
  <td colspan="4">
    II. Đánh giá về ý thức chấp hành nội quy, quy chế, quy định trong Nhà trường 
    <span style="font-style: italic;">(Điểm tối đa 25 điểm)</span>
  </td>
</tr>

<?php
$rows = [
  ['name' => 'diem_ii1_noi_quy', 'label' => 'Thực hiện tốt nội quy lớp học, quy định của Nhà trường', 'max' => 5],
  ['name' => 'diem_ii2_quy_che_sv', 'label' => 'Thực hiện tốt Quy chế học sinh, sinh viên; Quy định của Nhà trường đối với sinh viên ngoại trú', 'max' => 10],
  ['name' => 'diem_ii3_bao_hiem', 'label' => 'Tham gia đầy đủ bảo hiểm y tế, bảo hiểm tai nạn <i>(05 điểm/1 loại hình bảo hiểm)</i>', 'max' => 10]
];
?>

<?php foreach ($rows as $i => $r): ?>
<tr>
  <td class="text-center"><?= $i + 1 ?></td>
  <td class="text-start"><?= $r['label'] ?></td>
  <td class="text-center"><?= $r['max'] ?></td>
  <td class="value-box text-center"><?= $phieu[$r['name']] ?></td>
</tr>
<?php endforeach; ?>

<!-- III -->
<tr class="section-header">
  <td colspan="4">
    III. Đánh giá về ý thức và kết quả tham gia các hoạt động chính trị, xã hội, 
    văn hoá, văn nghệ, thể thao, phòng chống tội phạm và các tệ nạn xã hội 
    <span style="font-style: italic;">(Điểm tối đa 20 điểm)</span>
  </td>
</tr>

<!-- III.1 -->
<tr>
  <td class="text-center">1</td>
  <td class="text-start">
    Tham gia nhiệt tình, đầy đủ và có ý thức các hoạt động tập trung do Nhà trường, Khoa, 
    Đoàn Thanh niên, Hội Sinh viên tổ chức. 
    <i>(Trừ 05 điểm/1 lần vắng mặt)</i>
  </td>
  <td class="text-center">10</td>
  <td class="value-box text-center"><?= $phieu['diem_iii1_tham_gia'] ?></td>
</tr>

<!-- III.2 -->
<tr>
  <td class="text-center">2</td>
  <td class="text-start">
    Có ý thức và tích cực tham gia tuyên truyền công tác phòng chống tệ nạn xã hội, 
    hoạt động công ích, tình nguyện, từ thiện, nhân đạo, công tác xã hội.
  </td>
  <td class="text-center">05</td>
  <td class="value-box text-center"><?= $phieu['diem_iii2_tuyen_truyen'] ?></td>
</tr>

<!-- III.3 -->
<tr>
  <td class="text-center">3</td>
  <td colspan="3" class="text-start">
    Kết quả phân loại đoàn viên: <i>(Chọn 1 trong 2)</i>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Khá</i></td>
  <td class="text-center">03</td>
  <td class="value-box text-center">
      <?= ($phieu['diem_iii3_xep_loai_doan'] == 3) ? 3 : '' ?>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Xuất sắc</i></td>
  <td class="text-center">05</td>
  <td class="value-box text-center">
      <?= ($phieu['diem_iii3_xep_loai_doan'] == 5) ? 5 : '' ?>
  </td>
</tr>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4" class="text-start">
    <strong>* Điểm thưởng</strong>
    <span style="font-style: italic;">(tối đa không vượt quá 20 điểm)</span><br>

    <div style="margin-left: 20px; margin-top: 5px;">
      Đạt danh hiệu “Sinh viên 5 tốt”, thành tích cao trong văn nghệ – thể thao…
      <span style="font-style: italic;">
        (Nếu tập thể lớp đạt thành tích thì tất cả thành viên đều được hưởng điểm)
      </span>
    </div>

    <div style="margin-left: 35px; margin-top: 5px;">
      – Cấp khoa: 01 điểm<br>
      – Cấp trường: 02 điểm<br>
      – Cấp tỉnh hoặc tương đương: 03 điểm
    </div>

    <div class="mt-2">
      <strong>Điểm đã cộng:</strong>
      <span class="value-box"> <?= $phieu['diem_iii_thuong'] ?> </span>
    </div>
  </td>
</tr>

<!-- IV -->
<tr class="section-header">
  <td colspan="4">
    IV. Đánh giá về ý thức công dân trong quan hệ cộng đồng 
    <span style="font-style: italic;">(Điểm tối đa 25 điểm)</span>
  </td>
</tr>

<?php
$iv = [
  ['key'=>'diem_iv1_chu_truong', 'label'=>'Chấp hành tốt và tích cực tham gia tuyên truyền các chủ trương của Đảng, pháp luật nhà nước.'],
  ['key'=>'diem_iv2_phap_luat', 'label'=>'Tham gia đầy đủ các buổi tìm hiểu pháp luật do Nhà trường tổ chức.'],
  ['key'=>'diem_iv3_xa_hoi', 'label'=>'Tích cực tham gia hoạt động xã hội của Nhà trường hoặc địa phương.'],
  ['key'=>'diem_iv4_quan_he', 'label'=>'Có mối quan hệ tốt trong tập thể, không gây mất đoàn kết.'],
  ['key'=>'diem_iv5_tuong_than', 'label'=>'Thực hiện tốt tinh thần tương thân tương ái.']
];
?>

<?php foreach ($iv as $i => $r): ?>
<tr>
  <td class="text-center"><?= $i + 1 ?></td>
  <td class="text-start"><?= $r['label'] ?></td>
  <td class="text-center">05</td>
  <td class="value-box text-center"><?= $phieu[$r['key']] ?></td>
</tr>
<?php endforeach; ?>


<!-- V -->
<tr class="section-header">
  <td colspan="4">
    V. Đánh giá về ý thức và kết quả khi tham gia công tác cán bộ lớp,
    các đoàn thể, tổ chức trong trường hoặc người học đạt được thành tích
    đặc biệt trong học tập, rèn luyện
    <span style="font-style: italic;">(Điểm tối đa 10 điểm)</span>
  </td>
</tr>

<!-- Mục 1 -->
<tr>
  <td>1</td>
  <td class="text-start">
    Không là cán bộ lớp, Đoàn, Hội nhưng thực hiện tốt nhiệm vụ
  </td>
  <td class="text-center">04</td>
  <td class="value-box text-center"><?= $phieu['diem_v1_khong_can_bo'] ?></td>
</tr>

<!-- Mục 2 -->
<tr>
  <td>2</td>
  <td class="text-start">
    Là cán bộ lớp, cán bộ Đoàn, Hội nhưng không thực hiện tốt nhiệm vụ được giao,
    không gương mẫu trước tập thể
  </td>
  <td class="text-center">00</td>
  <td class="value-box text-center">
      <?= ($phieu['diem_v2_khong_hoan_thanh'] == 0 ? '0' : '') ?>
  </td>
</tr>

<!-- Mục 3 -->
<tr>
  <td>3</td>
  <td colspan="2" class="text-start">
    Nếu là cán bộ lớp, cán bộ Đoàn, Hội thì căn cứ vào kết quả thi đua của tập thể lớp quy định như sau:
    <div class="table-responsive mt-2" style="margin-left:15px;max-width:500px;">
      <table class="table table-bordered table-sm align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th style="width:130px;">Chức vụ</th>
            <th style="width:80px;">Xếp loại XS</th>
            <th style="width:80px;">Tốt</th>
            <th style="width:80px;">Khá</th>
            <th style="width:80px;">TBK</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr><td class="text-start">Cấp trưởng</td><td>10</td><td>9</td><td>8</td><td>6</td></tr>
          <tr><td class="text-start">Cấp phó</td><td>8</td><td>7</td><td>6</td><td>4</td></tr>
        </tbody>
      </table>
    </div>
  </td>
  <td class="value-box text-center"><?= $phieu['diem_v3_can_bo_lop'] ?></td>
</tr>

<!-- Tổng -->
<tr>
  <td colspan="2" class="text-end fw-bold">Tổng cộng</td>
  <td class="fw-bold text-center">100</td>
  <td class="value-box text-center fw-bold"><?= $phieu['tong_diem'] ?></td>
</tr>


</tbody>
</table>
</div>

</div>

</body>
</html>
