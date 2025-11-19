<?php
// ===============================================
// views/layouts/admin/admin_lop/them_lop.php
// Thêm lớp + Tự động tạo bảng sinh viên tương ứng
// ===============================================
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

// Lấy danh sách cố vấn & khoa
$giangviens = $conn->query("SELECT ma_gv, ho_ten FROM tb_giangvien ORDER BY ho_ten")->fetchAll(PDO::FETCH_ASSOC);
$khoas = $conn->query("SELECT ma_khoa, ten_khoa FROM tb_khoa ORDER BY ten_khoa")->fetchAll(PDO::FETCH_ASSOC);

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_lop  = strtoupper(trim($_POST['ma_lop']));
    $ten_lop = trim($_POST['ten_lop']);
    $ma_khoa = trim($_POST['ma_khoa']);

    try {
        // 1️⃣ Thêm vào tb_lop
        $stmt = $conn->prepare("
            INSERT INTO tb_lop(
                ma_lop, ten_lop, nien_khoa, khoa_hoc, he_dao_tao, bac_dao_tao,
                nganh_hoc, si_so, ma_gv, ma_khoa, mat_khau_lop, ghi_chu
            ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $ma_lop,
            $ten_lop,
            trim($_POST['nien_khoa']),
            trim($_POST['khoa_hoc']),
            trim($_POST['he_dao_tao']),
            trim($_POST['bac_dao_tao']),
            trim($_POST['nganh_hoc']),
            (int)$_POST['si_so'],
            $_POST['ma_gv'] ?: null,
            $ma_khoa ?: null,
            trim($_POST['mat_khau_lop']),
            trim($_POST['ghi_chu'])
        ]);

        // 2️⃣ Tạo bảng sinh viên tương ứng
        if (!empty($ma_khoa) && !empty($ma_lop)) {
            $tableName = "sv_" . strtolower($ma_khoa) . "_" . strtolower($ma_lop);

            $sql = "
            CREATE TABLE IF NOT EXISTS `$tableName` (
                `mssv` VARCHAR(20) NOT NULL,
                `mat_khau` VARCHAR(255) NOT NULL,
                `ho_ten` VARCHAR(100) NOT NULL,
                `gioi_tinh` ENUM('Nam','Nữ','Khác') DEFAULT 'Nam',
                `ngay_sinh` DATE DEFAULT NULL,
                `noi_sinh` VARCHAR(150) DEFAULT NULL,
                `lop` VARCHAR(50) DEFAULT NULL,
                `ma_khoa` VARCHAR(50) DEFAULT NULL,
                `nien_khoa` VARCHAR(50) DEFAULT NULL,
                `bac_dao_tao` VARCHAR(50) DEFAULT NULL,
                `loai_hinh` VARCHAR(50) DEFAULT NULL,
                `nganh_hoc` VARCHAR(100) DEFAULT NULL,
                `trang_thai` VARCHAR(50) DEFAULT 'Đang học',
                `email` VARCHAR(100) DEFAULT NULL,
                `sdt` VARCHAR(20) DEFAULT NULL,
                `dia_chi` VARCHAR(255) DEFAULT NULL,
                `ngay_tao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`mssv`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            ";
            $conn->exec($sql);
        }
// 3️⃣ Tạo bảng phiếu rèn luyện tương ứng
if (!empty($ma_khoa) && !empty($ma_lop)) {
    $tableRenLuyen = "phieu_ren_luyen_" . strtolower($ma_khoa) . "_" . strtolower($ma_lop);

    $sqlRenLuyen = "
    CREATE TABLE IF NOT EXISTS `$tableRenLuyen` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `mssv` VARCHAR(20) NOT NULL,
        `ho_ten` VARCHAR(100),
        `lop` VARCHAR(50),
        `khoa` VARCHAR(100),
        `nien_khoa` VARCHAR(50),
        `hoc_ky` VARCHAR(10),
        `nam_bd` VARCHAR(10),
        `nam_kt` VARCHAR(10),
        `diem_i1_hoc_tap` INT DEFAULT 0,
        `diem_i2_hoc_thuat` INT DEFAULT 0,
        `diem_i2_ngoai_khoa` INT DEFAULT 0,
        `diem_i2_ky_nang_mem` INT DEFAULT 0,
        `diem_i2_nc_khoa_hoc` INT DEFAULT 0,
        `diem_i2_cuoc_thi` INT DEFAULT 0,
        `diem_i3_vuot_kho` INT DEFAULT 0,
        `diem_i4_danh_gia_gv` INT DEFAULT 0,
        `diem_i5_tbc` INT DEFAULT 0,
        `diem_i_thuong` INT DEFAULT 0,
        `diem_ii1_noi_quy` INT DEFAULT 0,
        `diem_ii2_quy_che_sv` INT DEFAULT 0,
        `diem_ii3_bao_hiem` INT DEFAULT 0,
        `diem_iii1_tham_gia` INT DEFAULT 0,
        `diem_iii2_tuyen_truyen` INT DEFAULT 0,
        `diem_iii3_xep_loai_doan` INT DEFAULT 0,
        `diem_iii_thuong` INT DEFAULT 0,
        `diem_iv1_chu_truong` INT DEFAULT 0,
        `diem_iv2_phap_luat` INT DEFAULT 0,
        `diem_iv3_xa_hoi` INT DEFAULT 0,
        `diem_iv4_quan_he` INT DEFAULT 0,
        `diem_iv5_tuong_than` INT DEFAULT 0,
        `diem_v1_khong_can_bo` INT DEFAULT 0,
        `diem_v2_khong_hoan_thanh` INT DEFAULT 0,
        `diem_v3_can_bo_lop` INT DEFAULT 0,
        `tong_diem` INT DEFAULT 0,

        
        `ghi_chu_lop` TEXT NULL,
        `ghi_chu_co_van` TEXT NULL,
        `trang_thai_lop` VARCHAR(50) DEFAULT 'Chưa duyệt',

        `trang_thai` VARCHAR(50) DEFAULT 'Chưa duyệt',
        `nguoi_duyet` VARCHAR(100) DEFAULT NULL,
        `ngay_duyet` DATETIME DEFAULT NULL,
        `nguoi_danh_gia` VARCHAR(100) DEFAULT NULL,
        `ngay_tao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `ngay_capnhat` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        INDEX(`mssv`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    $conn->exec($sqlRenLuyen);
}


header('Location: index.php?route=gd_lop&msg=' . urlencode('Thêm lớp và bảng sinh viên thành công!') . '&type=success');
exit;

    } catch (PDOException $e) {
        $err = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thêm Lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
label.required::after {content:" *";color:red;}
</style>
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Thêm Lớp Mới</h5>
    </div>

    <?php if($err): ?>
      <div class="alert alert-danger m-3"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post" class="card-body row g-3">

      <div class="col-md-4">
        <label class="form-label required">Mã lớp</label>
        <input name="ma_lop" class="form-control" required oninput="this.value=this.value.toUpperCase()">
      </div>
      <div class="col-md-8">
        <label class="form-label required">Tên lớp</label>
        <input name="ten_lop" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Niên khóa</label>
        <input name="nien_khoa" class="form-control" placeholder="VD: 2022–2026">
      </div>
      <div class="col-md-6">
        <label class="form-label">Khóa học</label>
        <input name="khoa_hoc" class="form-control" placeholder="VD: Khóa 22">
      </div>

      <div class="col-md-6">
        <label class="form-label">Hệ đào tạo</label>
        <input name="he_dao_tao" class="form-control" value="Chính quy">
      </div>
      <div class="col-md-6">
        <label class="form-label">Bậc đào tạo</label>
        <input name="bac_dao_tao" class="form-control" value="Đại học">
      </div>

      <div class="col-md-6">
        <label class="form-label required">Ngành học</label>
        <input name="nganh_hoc" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Sĩ số</label>
        <input name="si_so" type="number" min="0" class="form-control" value="0">
      </div>

      <div class="col-md-6">
        <label class="form-label">Cố vấn học tập</label>
        <select name="ma_gv" class="form-select">
          <option value="">— Chọn giảng viên —</option>
          <?php foreach($giangviens as $gv): ?>
            <option value="<?= htmlspecialchars($gv['ma_gv']) ?>"><?= htmlspecialchars($gv['ho_ten']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label required">Khoa</label>
        <select name="ma_khoa" class="form-select" required>
          <option value="">— Chọn khoa —</option>
          <?php foreach($khoas as $k): ?>
            <option value="<?= htmlspecialchars($k['ma_khoa']) ?>"><?= htmlspecialchars($k['ten_khoa']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label required">Mật khẩu lớp</label>
        <input name="mat_khau_lop" class="form-control" required>
      </div>

      <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="index.php?route=gd_lop" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Thêm lớp</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
