<br>
<?php
// views/phieu_ren_luyen/phieu_ren_luyen_sv.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_sinhvien') {
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}

$mssv = $_SESSION['user']['id'];
$lopTable = $_SESSION['user']['lop'];
$conn = Database::connect();
$stmt = $conn->prepare("SELECT * FROM `$lopTable` WHERE mssv = ?");
$stmt->execute([$mssv]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
  session_destroy();
  header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
  exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
  try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Xác định bảng lưu theo lớp
    $lop_table = strtolower($student['lop']); // vd: tin23a
    $khoa_table = strtolower($student['ma_khoa']); // vd: sptin
    $table_name = "phieu_ren_luyen_" . $khoa_table . "_" . $lop_table;

    // Nếu bảng chưa tồn tại thì tự tạo bảng mới
    $conn->exec("
      CREATE TABLE IF NOT EXISTS `$table_name` LIKE phieu_ren_luyen
    ");

    // Kiểm tra xem sinh viên này đã có phiếu chưa
    $check = $conn->prepare("SELECT id FROM `$table_name` WHERE mssv = ? AND hoc_ky = ? AND nam_bd = ? AND nam_kt = ?");
    $check->execute([
      $student['mssv'],
      $_POST['hoc_ky'] ?? '',
      $_POST['nam_bd'] ?? '',
      $_POST['nam_kt'] ?? ''
    ]);

    if ($check->rowCount() > 0) {
      // ⚠️ Nếu đã tồn tại phiếu rèn luyện => Cập nhật
      $sql = "UPDATE `$table_name` SET
        diem_i1_hoc_tap = :diem_i1_hoc_tap,
        diem_i2_hoc_thuat = :diem_i2_hoc_thuat,
        diem_i2_ngoai_khoa = :diem_i2_ngoai_khoa,
        diem_i2_ky_nang_mem = :diem_i2_ky_nang_mem,
        diem_i2_nc_khoa_hoc = :diem_i2_nc_khoa_hoc,
        diem_i2_cuoc_thi = :diem_i2_cuoc_thi,
        diem_i3_vuot_kho = :diem_i3_vuot_kho,
        diem_i4_danh_gia_gv = :diem_i4_danh_gia_gv,
        diem_i5_tbc = :diem_i5_tbc,
        diem_i_thuong = :diem_i_thuong,
        diem_ii1_noi_quy = :diem_ii1_noi_quy,
        diem_ii2_quy_che_sv = :diem_ii2_quy_che_sv,
        diem_ii3_bao_hiem = :diem_ii3_bao_hiem,
        diem_iii1_tham_gia = :diem_iii1_tham_gia,
        diem_iii2_tuyen_truyen = :diem_iii2_tuyen_truyen,
        diem_iii3_xep_loai_doan = :diem_iii3_xep_loai_doan,
        diem_iii_thuong = :diem_iii_thuong,
        diem_iv1_chu_truong = :diem_iv1_chu_truong,
        diem_iv2_phap_luat = :diem_iv2_phap_luat,
        diem_iv3_xa_hoi = :diem_iv3_xa_hoi,
        diem_iv4_quan_he = :diem_iv4_quan_he,
        diem_iv5_tuong_than = :diem_iv5_tuong_than,
        diem_v1_khong_can_bo = :diem_v1_khong_can_bo,
        diem_v2_khong_hoan_thanh = :diem_v2_khong_hoan_thanh,
        diem_v3_can_bo_lop = :diem_v3_can_bo_lop,
        tong_diem = :tong_diem,
        ngay_capnhat = NOW()
      WHERE mssv = :mssv AND hoc_ky = :hoc_ky AND nam_bd = :nam_bd AND nam_kt = :nam_kt";
    } else {
      // ❇️ Chưa có → INSERT mới
      $sql = "INSERT INTO `$table_name` (
        mssv, ho_ten, lop, khoa, nien_khoa, hoc_ky, nam_bd, nam_kt,
        diem_i1_hoc_tap, diem_i2_hoc_thuat, diem_i2_ngoai_khoa, diem_i2_ky_nang_mem,
        diem_i2_nc_khoa_hoc, diem_i2_cuoc_thi, diem_i3_vuot_kho, diem_i4_danh_gia_gv,
        diem_i5_tbc, diem_i_thuong, diem_ii1_noi_quy, diem_ii2_quy_che_sv, diem_ii3_bao_hiem,
        diem_iii1_tham_gia, diem_iii2_tuyen_truyen, diem_iii3_xep_loai_doan, diem_iii_thuong,
        diem_iv1_chu_truong, diem_iv2_phap_luat, diem_iv3_xa_hoi, diem_iv4_quan_he, diem_iv5_tuong_than,
        diem_v1_khong_can_bo, diem_v2_khong_hoan_thanh, diem_v3_can_bo_lop,
        tong_diem, trang_thai, nguoi_danh_gia, ngay_tao
      ) VALUES (
        :mssv, :ho_ten, :lop, :khoa, :nien_khoa, :hoc_ky, :nam_bd, :nam_kt,
        :diem_i1_hoc_tap, :diem_i2_hoc_thuat, :diem_i2_ngoai_khoa, :diem_i2_ky_nang_mem,
        :diem_i2_nc_khoa_hoc, :diem_i2_cuoc_thi, :diem_i3_vuot_kho, :diem_i4_danh_gia_gv,
        :diem_i5_tbc, :diem_i_thuong, :diem_ii1_noi_quy, :diem_ii2_quy_che_sv, :diem_ii3_bao_hiem,
        :diem_iii1_tham_gia, :diem_iii2_tuyen_truyen, :diem_iii3_xep_loai_doan, :diem_iii_thuong,
        :diem_iv1_chu_truong, :diem_iv2_phap_luat, :diem_iv3_xa_hoi, :diem_iv4_quan_he, :diem_iv5_tuong_than,
        :diem_v1_khong_can_bo, :diem_v2_khong_hoan_thanh, :diem_v3_can_bo_lop,
        :tong_diem, 'Chờ duyệt', :nguoi_danh_gia, NOW()
      )";
    }

    // Thực thi truy vấn
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':mssv' => $student['mssv'],
      ':ho_ten' => $student['ho_ten'],
      ':lop' => $student['lop'],
      ':khoa' => $student['ma_khoa'],
      ':nien_khoa' => $student['nien_khoa'],
      ':hoc_ky' => $_POST['hoc_ky'] ?? '',
      ':nam_bd' => $_POST['nam_bd'] ?? '',
      ':nam_kt' => $_POST['nam_kt'] ?? '',
      ':diem_i1_hoc_tap' => $_POST['diem_i1_hoc_tap'] ?? 0,
      ':diem_i2_hoc_thuat' => $_POST['diem_i2_hoc_thuat'] ?? 0,
      ':diem_i2_ngoai_khoa' => $_POST['diem_i2_ngoai_khoa'] ?? 0,
      ':diem_i2_ky_nang_mem' => $_POST['diem_i2_ky_nang_mem'] ?? 0,
      ':diem_i2_nc_khoa_hoc' => $_POST['diem_i2_nc_khoa_hoc'] ?? 0,
      ':diem_i2_cuoc_thi' => $_POST['diem_i2_cuoc_thi'] ?? 0,
      ':diem_i3_vuot_kho' => $_POST['diem_i3_vuot_kho'] ?? 0,
      ':diem_i4_danh_gia_gv' => $_POST['diem_i4_danh_gia_gv'] ?? 0,
      ':diem_i5_tbc' => $_POST['diem_i5_tbc'] ?? 0,
      ':diem_i_thuong' => $_POST['diem_i_thuong'] ?? 0,
      ':diem_ii1_noi_quy' => $_POST['diem_ii1_noi_quy'] ?? 0,
      ':diem_ii2_quy_che_sv' => $_POST['diem_ii2_quy_che_sv'] ?? 0,
      ':diem_ii3_bao_hiem' => $_POST['diem_ii3_bao_hiem'] ?? 0,
      ':diem_iii1_tham_gia' => $_POST['diem_iii1_tham_gia'] ?? 0,
      ':diem_iii2_tuyen_truyen' => $_POST['diem_iii2_tuyen_truyen'] ?? 0,
      ':diem_iii3_xep_loai_doan' => $_POST['diem_iii3_xep_loai_doan'] ?? 0,
      ':diem_iii_thuong' => $_POST['diem_iii_thuong'] ?? 0,
      ':diem_iv1_chu_truong' => $_POST['diem_iv1_chu_truong'] ?? 0,
      ':diem_iv2_phap_luat' => $_POST['diem_iv2_phap_luat'] ?? 0,
      ':diem_iv3_xa_hoi' => $_POST['diem_iv3_xa_hoi'] ?? 0,
      ':diem_iv4_quan_he' => $_POST['diem_iv4_quan_he'] ?? 0,
      ':diem_iv5_tuong_than' => $_POST['diem_iv5_tuong_than'] ?? 0,
      ':diem_v1_khong_can_bo' => $_POST['diem_v1_khong_can_bo'] ?? 0,
      ':diem_v2_khong_hoan_thanh' => $_POST['diem_v2_khong_hoan_thanh'] ?? 0,
      ':diem_v3_can_bo_lop' => $_POST['diem_v3_can_bo_lop'] ?? 0,
      ':tong_diem' => $_POST['tong_diem'] ?? 0,
      ':nguoi_danh_gia' => $student['mssv']
    ]);

    // ✅ Thông báo
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Lưu thành công!',
          text: 'Phiếu rèn luyện học kỳ {$_POST['hoc_ky']} – Năm học {$_POST['nam_bd']}–{$_POST['nam_kt']} đã được lưu vào bảng $table_name.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#28a745'
        });
      });
    </script>";

  } catch (Throwable $e) {
    echo "<pre style='color:red;'>Lỗi SQL: " . htmlspecialchars($e->getMessage()) . "</pre>";
  }
}






?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Phiếu đánh giá rèn luyện</title>

<!-- Bootstrap trước -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS riêng sau cùng -->
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/phieu_ren_luyen_sv.css?v=<?= time() ?>">

</head>
<body>

<form method="POST" action="">

<div class="container-box">
  <!-- 🇻🇳 Quốc hiệu -->
  <div class="d-flex justify-content-between align-items-center mb-3">

<a href="<?= BASE_URL ?>index.php?route=bodys_sinhvien" 
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>

    <h3 class="flex-grow-1 text-center m-0">PHIẾU ĐÁNH GIÁ KẾT QUẢ RÈN LUYỆN </h3>

    
    <div style="width:80px;"></div> <!-- giữ cân đối khi căn giữa -->
    
  </div>


<!-- 🧾 Thông tin sinh viên -->
<div class="info-box">
  <div class="row">
    <div class="col-md-6">
      <!-- Họ và tên -->
      <div class="info-line">
        <strong>Họ và tên:</strong>
        <div><?= htmlspecialchars($student['ho_ten']) ?></div>
      </div>
      <input type="hidden" name="ho_ten" value="<?= htmlspecialchars($student['ho_ten']) ?>">

      <!-- Ngày sinh -->
      <div class="info-line">
        <strong>Ngày sinh:</strong>
        <div>
          <?php
            $ngaySinh = $student['ngay_sinh'] ?? '';
            if (!empty($ngaySinh)) {
              $formattedDate = date('d/m/Y', strtotime($ngaySinh));
              echo htmlspecialchars($formattedDate);
            } else {
              echo '-';
            }
          ?>
        </div>
      </div>

      <!-- Lớp -->
      <div class="info-line">
        <strong>Lớp:</strong>
        <div><?= htmlspecialchars($student['lop']) ?></div>
      </div>
      <input type="hidden" name="lop" value="<?= htmlspecialchars($student['lop']) ?>">

      <!-- Khóa học -->
      <div class="info-line">
        <strong>Khóa học:</strong>
        <div><?= htmlspecialchars($student['nien_khoa'] ?? '2022 - 2026') ?></div>
      </div>
      <input type="hidden" name="nien_khoa" value="<?= htmlspecialchars($student['nien_khoa'] ?? '2022 - 2026') ?>">

      <!-- Học kỳ -->
      <div class="info-line">
        <strong>Học kỳ:</strong>
        <div>
<select name="hoc_ky" id="hoc_ky" class="input-line" required>
            <option value="">-- Chọn --</option>
            <option value="I">Học kỳ I</option>
            <option value="II">Học kỳ II</option>
            <option value="Hè">Học kỳ Hè</option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <!-- MSSV -->
      <div class="info-line">
        <strong>MSSV:</strong>
        <div><?= htmlspecialchars($student['mssv']) ?></div>
      </div>
      <input type="hidden" name="mssv" value="<?= htmlspecialchars($student['mssv']) ?>">

      <!-- Giới tính -->
      <div class="info-line">
        <strong>Giới tính:</strong>
        <div><?= htmlspecialchars($student['gioi_tinh']) ?></div>
      </div>
      <input type="hidden" name="gioi_tinh" value="<?= htmlspecialchars($student['gioi_tinh']) ?>">

      <!-- Khoa -->
      <div class="info-line">
        <strong>Khoa:</strong>
        <div><?= htmlspecialchars($student['ma_khoa']) ?></div>
      </div>
      <input type="hidden" name="khoa" value="<?= htmlspecialchars($student['ma_khoa']) ?>">

      <!-- Năm học -->
<!-- Năm học -->
<div class="info-line">
  <strong>Năm học:</strong>
  <div>
    <select name="nam_bd" id="nam_bd" class="input-line" required>
      <option value="">-- Chọn --</option>
      <?php for ($i = 2025; $i <= 2030; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>
    -
        <select name="nam_kt" id="nam_kt" class="input-line" required>
      <option value="">-- Chọn --</option>
      <?php for ($i = 2025; $i <= 2030; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>
</div>

    </div>
  </div>
</div>




  
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
  <td>
    <select name="diem_i1_hoc_tap" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=5;$i++): ?>
        <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- I.2 -->
<!-- I.2 -->
<tr>
  <td>2</td>
  <td colspan="3" class="text-start">Ý thức, thái độ tham gia</td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Các hoạt động học thuật (Câu lạc bộ)</i></td>
  <td>01</td>
  <td>
    <select name="diem_i2_hoc_thuat" class="form-select form-select-sm score-input">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Hoạt động ngoại khóa</i></td>
  <td>01</td>
  <td>
    <select name="diem_i2_ngoai_khoa" class="form-select form-select-sm score-input">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Rèn luyện kỹ năng mềm</i></td>
  <td>01</td>
  <td>
    <select name="diem_i2_ky_nang_mem" class="form-select form-select-sm score-input">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Hoạt động nghiên cứu khoa học</i></td>
  <td>01</td>
  <td>
    <select name="diem_i2_nc_khoa_hoc" class="form-select form-select-sm score-input">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </td>
</tr>

<tr>
  <td></td>
  <td class="text-start"><i>– Các cuộc thi do Nhà trường, Đoàn Thanh niên, Hội Sinh viên phát động</i></td>
  <td>01</td>
  <td>
    <select name="diem_i2_cuoc_thi" class="form-select form-select-sm score-input">
      <option value="0">0</option>
      <option value="1">1</option>
    </select>
  </td>
</tr>

<!-- I.3 -->
<tr>
  <td>3</td>
  <td class="text-start">Tinh thần vượt khó, phấn đấu vươn lên trong học tập</td>
  <td>02</td>
  <td>
    <select name="diem_i3_vuot_kho" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=2;$i++): ?>
        <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- I.4 -->
<tr>
  <td>4</td>
  <td class="text-start">Tích cực đánh giá hoạt động giảng dạy của giảng viên, khóa đào tạo</td>
  <td>02</td>
  <td>
    <select name="diem_i4_danh_gia_gv" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=2;$i++): ?>
        <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- I.5 -->
<tr>
  <td>5</td>
  <td colspan="3" class="text-start">Kết quả học tập <i>(Chọn 1 trong 4)</i></td>
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
    <input type="radio" name="diem_i5_tbc" value="<?= $g[1] ?>" class="score-input">
  </td>
</tr>
<?php endforeach; ?>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4" class="text-start">
    <strong>* Điểm thưởng 
      <span style="font-style: italic; font-weight: bold;">
        (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
      </span>
    </strong><br>
    <div style="margin-left: 20px;">
      Được các cấp khen thưởng khi tham gia các hoạt động trên, điểm thưởng như sau:
      <div style="display: flex; justify-content: flex-start; gap: 40px; margin-top: 5px; margin-left: 20px;">
        <div>– Cấp khoa: 01 điểm</div>
        <div>– Cấp trường: 02 điểm</div>
        <div>– Cấp tỉnh hoặc tương đương: 03 điểm</div>
      </div>
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
  <td><?= $i + 1 ?></td>
  <td class="text-start"><?= $r['label'] ?></td>
  <td><?= $r['max'] ?></td>
  <td>
    <select name="<?= $r['name'] ?>" class="form-select form-select-sm score-input">
      <?php
      // nếu điểm tối đa là 10 → bước nhảy 5, còn lại bước nhảy 1
      $step = ($r['max'] == 10) ? 5 : 1;
      for ($x = 0; $x <= $r['max']; $x += $step): ?>
        <option value="<?= $x ?>"><?= $x ?></option>
      <?php endfor; ?>
    </select>
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
  <td>1</td>
  <td class="text-start">
    Tham gia nhiệt tình, đầy đủ và có ý thức các hoạt động tập trung do Nhà trường, Khoa, 
    Đoàn Thanh niên, Hội Sinh viên tổ chức. 
    <i>(Trừ 05 điểm/1 lần vắng mặt)</i>
  </td>
  <td>10</td>
  <td>
    <select name="diem_iii1_tham_gia" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=10;$i+=5): ?>
        <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- III.2 -->
<tr>
  <td>2</td>
  <td class="text-start">
    Có ý thức và tích cực tham gia tuyên truyền công tác phòng chống tệ nạn xã hội, 
    hoạt động công ích, tình nguyện, từ thiện, nhân đạo, công tác xã hội.
  </td>
  <td>05</td>
  <td>
    <select name="diem_iii2_tuyen_truyen" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=5;$i++): ?>
        <option value="<?=$i?>"><?=$i?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>

<!-- III.3 -->
<tr>
  <td>3</td>
  <td colspan="3" class="text-start">Kết quả phân loại đoàn viên: <i>(Chọn 1 trong 2)</i></td>
</tr>
<tr>
  <td></td>
  <td class="text-start"><i>– Khá</i></td>
  <td>03</td>
  <td>
    <input type="radio" name="diem_iii3_xep_loai_doan" value="3" class="score-input">
  </td>
</tr>
<tr>
  <td></td>
  <td class="text-start"><i>– Xuất sắc</i></td>
  <td>05</td>
  <td>
    <input type="radio" name="diem_iii3_xep_loai_doan" value="5" class="score-input">
  </td>
</tr>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4" class="text-start">
    <strong>* Điểm thưởng 
      <span style="font-style: italic; font-weight: bold;">
        (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
      </span>
    </strong><br>
    <div style="margin-left: 20px; margin-top: 5px;">
      Đạt danh hiệu “Sinh viên 5 tốt” hoặc đạt thành tích cao và được khen thưởng 
      trong các cuộc thi về văn nghệ, thể thao. 
      <span style="font-style: italic;">
        (Nếu tập thể lớp đạt thành tích thì tất cả các thành viên trong lớp đều được hưởng số điểm)
      </span>
    </div>
    <div style="display: flex; justify-content: flex-start; gap: 35px; margin-left: 35px; margin-top: 5px;">
      <div>– Cấp khoa: 01 điểm</div>
      <div>– Cấp trường: 02 điểm</div>
      <div>– Cấp tỉnh hoặc tương đương: 03 điểm</div>
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
  ['name' => 'diem_iv1_chu_truong', 'label' => 'Chấp hành tốt và tích cực tham gia tuyên truyền các chủ trương của Đảng, chính sách và pháp luật của nhà nước trong cộng đồng.'],
  ['name' => 'diem_iv2_phap_luat', 'label' => 'Tham gia đầy đủ các buổi học tập, tìm hiểu pháp luật do Nhà trường tổ chức'],
  ['name' => 'diem_iv3_xa_hoi', 'label' => 'Tích cực tham gia các hoạt động xã hội do Nhà trường hoặc địa phương nơi cư trú tổ chức'],
  ['name' => 'diem_iv4_quan_he', 'label' => 'Có mối quan hệ tốt trong tập thể, không gây mất đoàn kết, bản thân có tác dụng tích cực đối với tập thể'],
  ['name' => 'diem_iv5_tuong_than', 'label' => 'Thực hiện tốt tinh thần tương thân, tương ái trong cuộc sống.']
];
?>

<?php foreach ($iv as $i => $r): ?>
<tr>
  <td><?= $i + 1 ?></td>
  <td class="text-start"><?= $r['label'] ?></td>
  <td>05</td>
  <td>
    <select name="<?= $r['name'] ?>" class="form-select form-select-sm score-input">
      <?php for ($j = 0; $j <= 5; $j++): ?>
        <option value="<?= $j ?>"><?= $j ?></option>
      <?php endfor; ?>
    </select>
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
  <td class="text-start">
    Không là cán bộ lớp, Đoàn, Hội nhưng thực hiện tốt nhiệm vụ
  </td>
  <td>04</td>
  <td>
    <select name="diem_v1_khong_can_bo" id="opt1" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=4;$i++): ?>
        <option value="<?=$i?>"><?=$i?></option>
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
    <input type="checkbox" id="opt2" name="diem_v2_khong_hoan_thanh" value="0" class="form-check-input score-input">
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
  <td class="text-center">
    <select name="diem_v3_can_bo_lop" id="opt3" class="form-select form-select-sm text-center score-input">
      <option value="">0</option>
      <option value="4">4</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
    </select>
  </td>
</tr>




<!-- Tổng -->
<tr>
  <td colspan="2" class="text-end fw-bold">Tổng cộng</td>
  <td class="fw-bold text-center">100</td>
  <td>
    <input 
      type="number" 
      id="totalScore" 
      name="tong_diem" 
      class="form-control form-control-sm text-center fw-bold" 
      readonly
    >
  </td>
</tr>


      </tbody>
    </table>
  </div>

  <!-- Ghi chú -->
  <div class="mt">
    <p><strong>* Ghi chú:</strong></p>
    <p style="font-style: italic; margin-left: 20px;">
      1. Nếu sinh viên vi phạm quy chế thi, kết quả đánh giá rèn luyện trong học kỳ không vượt quá loại Trung bình.<br>
      2. Nếu sinh viên vi phạm quy chế học sinh, sinh viên bị xử lý từ cảnh cáo trở lên khi đánh giá kết quả rèn luyện không được vượt quá loại Trung bình.
    </p>

<div class="text-end mt-3">
  <button type="button" id="save" class="btn btn-primary px-4">
     Lưu đánh giá
  </button>
</div>

        
</div>

</form>
<style>
/* Ghi đè Bootstrap, giữ nguyên id */
button#save.btn.btn-primary {
  background-color: #004aad !important;
  border-color: #004aad !important;
  color: #fff !important;
  font-weight: 600;
}

button#save.btn.btn-primary:hover {
  background-color: #003580 !important;
  border-color: #003580 !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const btnSave = document.getElementById("save");
  const totalInput = document.getElementById("totalScore");
  const hocKy = document.getElementById("hoc_ky");
  const namBD = document.getElementById("nam_bd");
  const namKT = document.getElementById("nam_kt");
  const opt1 = document.getElementById("opt1");
  const opt2 = document.getElementById("opt2");
  const opt3 = document.getElementById("opt3");

  if (!form || !btnSave) {
    console.error("⚠️ Không tìm thấy form hoặc nút lưu!");
    return;
  }

  // 🧮 Tính tổng điểm
  function calculateTotal() {
    let total = 0;
    document.querySelectorAll(".score-input").forEach(el => {
      if (el.tagName === "SELECT") total += parseFloat(el.value) || 0;
      else if (el.type === "radio" && el.checked) total += parseFloat(el.value) || 0;
      else if (el.type === "checkbox" && el.checked) total += parseFloat(el.value) || 0;
      else if (el.type === "number") total += parseFloat(el.value) || 0;
    });
    totalInput.value = Math.min(total, 100);
  }

  // 🔄 Ràng buộc phần V – chỉ được chọn 1 trong 3
  function handlePartV(changed) {
    if (changed === "opt2" && opt2.checked) {
      opt1.value = 0; opt3.value = "";
    } else if (changed === "opt1" && parseInt(opt1.value) > 0) {
      opt2.checked = false; opt3.value = "";
    } else if (changed === "opt3" && opt3.value !== "") {
      opt1.value = 0; opt2.checked = false;
    }
    calculateTotal();
  }

  [opt1, opt2, opt3].forEach(el => el?.addEventListener("change", e => handlePartV(e.target.id)));
  document.querySelectorAll(".score-input").forEach(el => el.addEventListener("change", calculateTotal));

  // 📅 Tự động cộng năm học
  namBD?.addEventListener("change", () => {
    const start = parseInt(namBD.value);
    if (!isNaN(start)) {
      const next = start + 1;
      const option = [...namKT.options].find(o => parseInt(o.value) === next);
      namKT.value = option ? next : "";
    } else namKT.value = "";
  });

  // 💾 Xử lý khi bấm Lưu
  btnSave.addEventListener("click", (e) => {
    e.preventDefault();
    calculateTotal();

    // 1️⃣ Kiểm tra học kỳ và năm học
    if (!hocKy?.value.trim()) {
      Swal.fire({ icon: "warning", title: "Thiếu học kỳ!", text: "⚠️ Vui lòng chọn Học kỳ trước khi lưu.", confirmButtonColor: "#d33" });
      return;
    }
    if (!namBD?.value.trim()) {
      Swal.fire({ icon: "warning", title: "Thiếu năm bắt đầu!", text: "⚠️ Vui lòng chọn Năm học bắt đầu.", confirmButtonColor: "#d33" });
      return;
    }
    if (!namKT?.value.trim()) {
      Swal.fire({ icon: "warning", title: "Thiếu năm kết thúc!", text: "⚠️ Vui lòng chọn Năm học kết thúc.", confirmButtonColor: "#d33" });
      return;
    }

    // 2️⃣ Kiểm tra radio bắt buộc
    const radioGroups = new Set();
    let allChecked = true;
    document.querySelectorAll('input[type="radio"]').forEach(r => radioGroups.add(r.name));
    for (const name of radioGroups) {
      if (!document.querySelector(`input[name="${name}"]:checked`)) {
        allChecked = false;
        break;
      }
    }
    if (!allChecked) {
      Swal.fire({
        icon: "warning",
        title: "Thiếu thông tin!",
        text: "⚠️ Vui lòng chọn đầy đủ các ô chấm tròn (radio) trước khi lưu.",
        confirmButtonText: "Đã hiểu",
        confirmButtonColor: "#3085d6"
      });
      return;
    }

    // 3️⃣ Gán giá trị 0 cho ô chưa chọn
    document.querySelectorAll(".score-input").forEach(el => {
      const name = el.name;
      if (!name) return;
      if (el.type === "radio") {
        const group = document.querySelectorAll(`input[name="${name}"]`);
        const checked = Array.from(group).some(r => r.checked);
        if (!checked && !form.querySelector(`input[name="${name}"][type="hidden"]`)) {
          const hidden = document.createElement("input");
          hidden.type = "hidden"; hidden.name = name; hidden.value = 0;
          form.appendChild(hidden);
        }
      }
      if ((el.tagName === "SELECT" || el.type === "checkbox" || el.type === "number")
          && !form.querySelector(`input[name="${name}"][type="hidden"]`)) {
        const isEmpty =
          (el.tagName === "SELECT" && el.value === "") ||
          (el.type === "checkbox" && !el.checked) ||
          (el.type === "number" && el.value === "");
        if (isEmpty) {
          const hidden = document.createElement("input");
          hidden.type = "hidden"; hidden.name = name; hidden.value = 0;
          form.appendChild(hidden);
        }
      }
    });

    // 4️⃣ Xác nhận lưu
    Swal.fire({
      icon: "question",
      title: "Xác nhận lưu?",
      text: `Lưu phiếu rèn luyện cho ${hocKy.value} - Năm học ${namBD.value}–${namKT.value}?`,
      showCancelButton: true,
      confirmButtonText: "💾 Lưu ngay",
      cancelButtonText: "🔍 Kiểm tra lại",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#d33"
    }).then(result => {
      if (result.isConfirmed) {
        if (!form.querySelector('input[name="save"]')) {
          const hiddenSave = document.createElement("input");
          hiddenSave.type = "hidden";
          hiddenSave.name = "save";
          hiddenSave.value = "1";
          form.appendChild(hiddenSave);
        }
        Swal.fire({
          title: "Đang lưu dữ liệu...",
          text: "Vui lòng chờ giây lát",
          icon: "info",
          showConfirmButton: false,
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        form.submit();
      }
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const opt1 = document.getElementById('opt1');
  const opt2 = document.getElementById('opt2');
  const opt3 = document.getElementById('opt3');

  function lockOthers(active) {
    if (active === 'opt1') {
      opt2.disabled = true;
      opt3.disabled = true;
    } else if (active === 'opt2') {
      opt1.disabled = true;
      opt3.disabled = true;
    } else if (active === 'opt3') {
      opt1.disabled = true;
      opt2.disabled = true;
    }
  }

  function unlockAll() {
    opt1.disabled = false;
    opt2.disabled = false;
    opt3.disabled = false;
  }

  // --- Sự kiện cho select opt1 ---
  opt1.addEventListener('change', function() {
    if (this.value > 0) lockOthers('opt1');
    else unlockAll();
  });

  // --- Sự kiện cho checkbox opt2 ---
  opt2.addEventListener('change', function() {
    if (this.checked) lockOthers('opt2');
    else unlockAll();
  });

  // --- Sự kiện cho select opt3 ---
  opt3.addEventListener('change', function() {
    if (this.value && this.value !== "0") lockOthers('opt3');
    else unlockAll();
  });
});
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

