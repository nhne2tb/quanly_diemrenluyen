<?php
// views/phieu_ren_luyen/xem_phieu_ren_luyen_sv.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$userType = $_SESSION['user']['type'] ?? '';
$isGV = ($userType === 'bodys_giangvien'); // chỉ CVHT mới được sửa

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

/* Nút Quay lại */
.btn-outline-primary {
    border-color: #004aad !important;
    color: #004aad !important;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background: #004aad !important;
    color: #fff !important;
}
</style>
</head>

<body>

<div class="container-box">



  <div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="m-0 fw-bold" style="color:#004aad;">
  PHIẾU ĐÁNH GIÁ RÈN LUYỆN – CHI TIẾT
</h3>


<?php
$backRoute = "ket_qua_ren_luyen_sv";

if (isset($_SESSION['user']['type'])) {
    if ($_SESSION['user']['type'] === 'bodys_giangvien') {
        $backRoute = "bodys_giangvien_drl";
    }
    if ($_SESSION['user']['type'] === 'bodys_tapthelop') {
        $backRoute = "dashboard";
    }


}
?>
<a href="<?= BASE_URL ?>index.php?route=<?= $backRoute ?>"
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>


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
  

<?php if($isGV): ?>
<form id="formGV" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="table" value="<?= $table ?>">
<?php endif; ?>

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
<td class="text-center">
<?php if($isGV): ?>
    <select name="diem_i1_hoc_tap" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=5;$i++): ?>
            <option value="<?=$i?>" <?= ($phieu['diem_i1_hoc_tap']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
    </select>
<?php else: ?>
    <span class="value-box"><?= $phieu['diem_i1_hoc_tap'] ?></span>
<?php endif; ?>
</td>
</tr>

<!-- I.2 -->
<!-- I.2 -->
<tr><td class="text-center">2</td><td colspan="3" class="text-start">Ý thức, thái độ tham gia</td></tr>

<tr>
  <td></td>
  <td><i>– Các hoạt động học thuật</i></td>
  <td class="text-center">01</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i2_hoc_thuat" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=1;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_i2_hoc_thuat']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i2_hoc_thuat'] ?></span>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td></td>
  <td><i>– Hoạt động ngoại khóa</i></td>
  <td class="text-center">01</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i2_ngoai_khoa" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=1;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_i2_ngoai_khoa']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i2_ngoai_khoa'] ?></span>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td></td>
  <td><i>– Rèn luyện kỹ năng mềm</i></td>
  <td class="text-center">01</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i2_ky_nang_mem" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=1;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_i2_ky_nang_mem']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i2_ky_nang_mem'] ?></span>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td></td>
  <td><i>– Nghiên cứu khoa học</i></td>
  <td class="text-center">01</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i2_nc_khoa_hoc" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=1;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_i2_nc_khoa_hoc']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i2_nc_khoa_hoc'] ?></span>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td></td>
  <td><i>– Các cuộc thi của Trường/Đoàn/Hội</i></td>
  <td class="text-center">01</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i2_cuoc_thi" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=1;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_i2_cuoc_thi']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i2_cuoc_thi'] ?></span>
    <?php endif; ?>
  </td>
</tr>

<!-- I.3 -->
<tr>
  <td class="text-center">3</td>
  <td>Tinh thần vượt khó</td>
  <td class="text-center">02</td>
<td class="text-center">
<?php if($isGV): ?>
    <select name="diem_i3_vuot_kho" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=2;$i++): ?>
            <option value="<?=$i?>" <?= ($phieu['diem_i3_vuot_kho']==$i?'selected':'') ?>><?=$i?></option>
        <?php endfor; ?>
    </select>
<?php else: ?>
    <span class="value-box"><?= $phieu['diem_i3_vuot_kho'] ?></span>
<?php endif; ?>
</td>
</tr>

<!-- I.4 -->
<tr>
  <td class="text-center">4</td>
  <td>Tích cực đánh giá hoạt động giảng dạy</td>
  <td class="text-center">02</td>
  <td class="text-center">
    <?php if($isGV): ?>
      <select name="diem_i4_danh_gia_gv" class="form-select form-select-sm">
        <?php for($i=0;$i<=2;$i++): ?>
          <option value="<?=$i?>" <?=($phieu['diem_i4_danh_gia_gv']==$i?'selected':'')?> >
            <?=$i?>
          </option>
        <?php endfor; ?>
      </select>
    <?php else: ?>
      <span class="value-box"><?= $phieu['diem_i4_danh_gia_gv'] ?></span>
    <?php endif; ?>
  </td>
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

  <td class="text-center">
    <?php if($isGV): ?>
        <input type="radio" 
               name="diem_i5_tbc" 
               value="<?= $val ?>" 
               <?= ($phieu['diem_i5_tbc'] == $val ? 'checked' : '') ?>>
    <?php else: ?>
        <span class="value-box">
            <?= ($phieu['diem_i5_tbc'] == $val) ? $val : '' ?>
        </span>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>


<!-- Điểm thưởng -->
<!-- <tr>
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
</tr> -->


<tr>
  <td colspan="3" class="text-start">
    <strong>* Điểm thưởng 
      <span style="font-style: italic; font-weight: bold;">
        (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
      </span>
    </strong><br>

    <div style="margin-left: 20px;">
      Được các cấp khen thưởng khi tham gia các hoạt động trên, điểm thưởng như sau:
    <div style="margin-left: 20px; margin-top: 5px;">
      – Cấp khoa: 01 điểm<br>
      – Cấp trường: 02 điểm<br>
      – Cấp tỉnh hoặc tương đương: 03 điểm
    </div>

    </div>
  </td>

<td class="text-center">
<?php if($isGV): ?>
    <select name="diem_i_thuong" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=3;$i++): ?>
            <option value="<?=$i?>" <?= ($phieu['diem_i_thuong']==$i?'selected':'') ?>>
                <?=$i?>
            </option>
        <?php endfor; ?>
    </select>
<?php else: ?>
    <span class="value-box"><?= $phieu['diem_i_thuong'] ?></span>
<?php endif; ?>
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

  <td class="text-center">

    <?php if($isGV): ?>
      <select name="<?= $r['name'] ?>" class="form-select form-select-sm score-input">
        
        <?php if ($r['max'] == 10): ?>
            <!-- Nếu max = 10 thì chỉ cho phép 0, 5, 10 -->
            <?php foreach ([0,5,10] as $diem): ?>
              <option value="<?= $diem ?>" <?= ($phieu[$r['name']] == $diem ? 'selected' : '') ?>>
                <?= $diem ?>
              </option>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Nếu max != 10 thì duyệt từ 0 → max -->
            <?php for($diem = 0; $diem <= $r['max']; $diem++): ?>
              <option value="<?= $diem ?>" <?= ($phieu[$r['name']] == $diem ? 'selected' : '') ?>>
                <?= $diem ?>
              </option>
            <?php endfor; ?>
        <?php endif; ?>

      </select>

    <?php else: ?>
      <span class="value-box"><?= $phieu[$r['name']] ?></span>
    <?php endif; ?>

  </td>
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

  <td class="text-center">
  <?php if($isGV): ?>
      <select name="diem_iii1_tham_gia" class="form-select form-select-sm score-input">
        <?php foreach ([0,5,10] as $d): ?>
          <option value="<?=$d?>" <?= ($phieu['diem_iii1_tham_gia'] == $d ? "selected" : "") ?>>
            <?=$d?>
          </option>
        <?php endforeach; ?>
      </select>
  <?php else: ?>
      <span class="value-box"><?= $phieu['diem_iii1_tham_gia'] ?></span>
  <?php endif; ?>
  </td>
</tr>

<!-- III.2 -->
<tr>
  <td class="text-center">2</td>
  <td class="text-start">
    Có ý thức và tích cực tham gia tuyên truyền công tác phòng chống tệ nạn xã hội, 
    hoạt động công ích, tình nguyện, từ thiện, nhân đạo, công tác xã hội.
  </td>
  <td class="text-center">05</td>

  <td class="text-center">
  <?php if($isGV): ?>
      <select name="diem_iii2_tuyen_truyen" class="form-select form-select-sm score-input">
        <?php for($i=0;$i<=5;$i++): ?>
          <option value="<?=$i?>" <?= ($phieu['diem_iii2_tuyen_truyen'] == $i ? "selected" : "") ?>>
            <?=$i?>
          </option>
        <?php endfor; ?>
      </select>
  <?php else: ?>
      <span class="value-box"><?= $phieu['diem_iii2_tuyen_truyen'] ?></span>
  <?php endif; ?>
  </td>
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

  <td class="text-center">
    <?php if($isGV): ?>
      <input type="radio" name="diem_iii3_xep_loai_doan" value="3"
             <?= ($phieu['diem_iii3_xep_loai_doan'] == 3 ? "checked" : "") ?>>
    <?php else: ?>
      <span class="value-box">
        <?= ($phieu['diem_iii3_xep_loai_doan'] == 3 ? 3 : "") ?>
      </span>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Xuất sắc</i></td>
  <td class="text-center">05</td>

  <td class="text-center">
    <?php if($isGV): ?>
      <input type="radio" name="diem_iii3_xep_loai_doan" value="5"
             <?= ($phieu['diem_iii3_xep_loai_doan'] == 5 ? "checked" : "") ?>>
    <?php else: ?>
      <span class="value-box">
        <?= ($phieu['diem_iii3_xep_loai_doan'] == 5 ? 5 : "") ?>
      </span>
    <?php endif; ?>
  </td>
</tr>

<!-- Điểm thưởng -->
<!-- <tr>
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
</tr> -->

<!-- Điểm thưởng III -->
<tr>
  <td colspan="3" class="text-start">
    <strong>* Điểm thưởng</strong>
    <span style="font-style: italic; font-weight: bold;">
      (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
    </span><br>

    <div style="margin-left:20px; margin-top:5px;">
      Đạt danh hiệu “Sinh viên 5 tốt”, hoặc được khen thưởng cuộc thi văn nghệ, thể thao...
      <span style="font-style: italic;">
        (Nếu tập thể lớp đạt thành tích thì tất cả thành viên đều được hưởng)
      </span>
    </div>

    <div style="margin-left: 20px; margin-top: 5px;">
      – Cấp khoa: 01 điểm<br>
      – Cấp trường: 02 điểm<br>
      – Cấp tỉnh hoặc tương đương: 03 điểm
    </div>

  </td>

  <!-- Cột chọn điểm -->
  <td class="text-center">
    <div class="mt-2">

      <strong>Điểm đã cộng:</strong>

      <?php if($isGV): ?>
        <select name="diem_iii_thuong" class="form-select form-select-sm score-input mt-2">
          <?php foreach ([0,1,2,3] as $d): ?>
            <option value="<?= $d ?>" <?= ($phieu['diem_iii_thuong'] == $d ? 'selected' : '') ?>>
              <?= $d ?>
            </option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <span class="value-box"><?= $phieu['diem_iii_thuong'] ?></span>
      <?php endif; ?>

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

  <td class="text-center">
    <?php if($isGV): ?>
        <select name="<?= $r['key'] ?>" class="form-select form-select-sm score-input">
            <?php for($d=0; $d<=5; $d++): ?>
                <option value="<?= $d ?>" <?= ($phieu[$r['key']] == $d ? 'selected' : '') ?>>
                    <?= $d ?>
                </option>
            <?php endfor; ?>
        </select>
    <?php else: ?>
        <span class="value-box"><?= $phieu[$r['key']] ?></span>
    <?php endif; ?>
  </td>

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
  <td class="text-start">Không là cán bộ lớp, Đoàn, Hội nhưng thực hiện tốt nhiệm vụ</td>
  <td>04</td>
  <td>
    <select name="diem_v1_khong_can_bo" id="opt1" class="form-select form-select-sm score-input lock-group">
      <option value="">-- Chọn --</option>
      <?php for($i=0;$i<=4;$i++): ?>
        <option value="<?=$i?>" <?= ($phieu['diem_v1_khong_can_bo']==$i?'selected':'') ?>>
          <?=$i?>
        </option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- Mục 2 -->
<tr>
  <td>2</td>
  <td class="text-start">
    Là cán bộ lớp, cán bộ Đoàn, Hội nhưng không thực hiện tốt nhiệm vụ được giao, không gương mẫu trước tập thể
  </td>
  <td>00</td>
  <td class="text-center">
    <input type="checkbox"
           id="opt2"
           class="form-check-input score-input lock-group"
           name="diem_v2_khong_hoan_thanh"
           value="0"
           <?= ($phieu['diem_v2_khong_hoan_thanh']==0?'checked':'') ?>>
  </td>
</tr>

<!-- Mục 3 -->
<tr>
  <td>3</td>
  <td colspan="2" class="text-start">
    Nếu là cán bộ lớp, cán bộ Đoàn, Hội thì căn cứ vào kết quả thi đua...
    <div class="table-responsive mt-2" style="margin-left:15px;max-width:500px;">
      <table class="table table-bordered table-sm align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th>Chức vụ</th><th>XS</th><th>Tốt</th><th>Khá</th><th>TBK</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr><td class="text-start">Cấp trưởng</td><td>10</td><td>9</td><td>8</td><td>6</td></tr>
          <tr><td class="text-start">Cấp phó</td><td>8</td><td>7</td><td>6</td><td>4</td></tr>
        </tbody>
      </table>
    </div>
  </td>
  <td class="text-center">
    <select name="diem_v3_can_bo_lop" id="opt3" class="form-select form-select-sm score-input lock-group">
      <option value="">-- Chọn --</option>

      <?php $valid=[4,6,7,8,9,10]; ?>
      <?php foreach($valid as $v): ?>
        <option value="<?=$v?>" <?= ($phieu['diem_v3_can_bo_lop']==$v?'selected':'') ?>>
          <?=$v?>
        </option>
      <?php endforeach; ?>

    </select>
  </td>
</tr>

<!-- Tổng -->
<tr>
  <td colspan="2" class="text-end fw-bold">Tổng cộng</td>
  <td class="fw-bold text-center">100</td>
  <td class="value-box text-center fw-bold"><?= $phieu['tong_diem'] ?></td>
</tr>


</tbody>
</table>


<?php if ($isGV): ?>
</form>  <!-- thêm dòng này -->
<button id="btnUpdateGV" class="btn btn-primary mt-3">
    💾 Lưu cập nhật
</button>
<?php endif; ?>



</div>

</div>

</body>
<script>
document.getElementById('btnUpdateGV')?.addEventListener('click', function(e) {
    e.preventDefault();

    let form = document.getElementById("formGV");
    let formData = new FormData(form);

    fetch("<?= BASE_URL ?>index.php?route=capnhat_phieu_gv", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            alert("Đã cập nhật thành công!\nTổng điểm mới: " + res.tong_diem);
            location.reload();
        } else {
            alert(res.msg);
        }
    })
    .catch(err => {
        alert("Lỗi kết nối server!");
        console.error(err);
    });
});

function updateLock() {

    const opt1 = document.getElementById("opt1");
    const opt2 = document.getElementById("opt2");
    const opt3 = document.getElementById("opt3");

    let v1 = opt1.value !== "";
    let v2 = opt2.checked;
    let v3 = opt3.value !== "";

    // Reset trước khi xử lý
    opt1.disabled = false;
    opt2.disabled = false;
    opt3.disabled = false;

    // Nếu mục 1 được chọn
    if (v1) {
        opt2.checked = false;
        opt3.value = "";
        
        opt2.disabled = true;
        opt3.disabled = true;
        return;
    }

    // Nếu mục 2 được chọn
    if (v2) {
        opt1.value = "";
        opt3.value = "";

        opt1.disabled = true;
        opt3.disabled = true;
        return;
    }

    // Nếu mục 3 được chọn
    if (v3) {
        opt1.value = "";
        opt2.checked = false;

        opt1.disabled = true;
        opt2.disabled = true;
        return;
    }

    // Nếu không chọn gì → mở tất cả
    opt1.disabled = false;
    opt2.disabled = false;
    opt3.disabled = false;
}

// Lắng nghe sự kiện thay đổi
document.querySelectorAll(".lock-group").forEach(el => {
    el.addEventListener("change", updateLock);
});

// Chạy khi trang load
document.addEventListener("DOMContentLoaded", updateLock);
</script>



</html>
