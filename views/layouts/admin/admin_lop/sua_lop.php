<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$ma = $_GET['ma'] ?? '';
$stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_lop=?");
$stmt->execute([$ma]);
$lop = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$lop) die("Không tìm thấy lớp!");

$giangviens = $conn->query("SELECT ma_gv, ho_ten FROM tb_giangvien ORDER BY ho_ten")->fetchAll(PDO::FETCH_ASSOC);
$khoas = $conn->query("SELECT ma_khoa, ten_khoa FROM tb_khoa ORDER BY ten_khoa")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE tb_lop SET ten_lop=?, nien_khoa=?, khoa_hoc=?, he_dao_tao=?, bac_dao_tao=?, nganh_hoc=?, si_so=?, ma_gv=?, ma_khoa=?, mat_khau_lop=?, ghi_chu=? WHERE ma_lop=?");
    $stmt->execute([
        trim($_POST['ten_lop']),
        trim($_POST['nien_khoa']),
        trim($_POST['khoa_hoc']),
        trim($_POST['he_dao_tao']),
        trim($_POST['bac_dao_tao']),
        trim($_POST['nganh_hoc']),
        (int)$_POST['si_so'],
        $_POST['ma_gv'] ?: null,
        $_POST['ma_khoa'] ?: null,
        trim($_POST['mat_khau_lop']),
        trim($_POST['ghi_chu']),
        $ma
    ]);
header('Location: index.php?route=gd_lop&msg='.urlencode('Cập nhật lớp thành công').'&type=success');
    exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sửa Lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Sửa Thông Tin Lớp</h5></div>
    <form method="post" class="card-body row g-3">
      <div class="col-md-4">
        <label class="form-label">Mã lớp</label>
        <input class="form-control bg-light" readonly value="<?= htmlspecialchars($lop['ma_lop']) ?>">
      </div>
      <div class="col-md-8">
        <label class="form-label required">Tên lớp</label>
        <input name="ten_lop" class="form-control" required value="<?= htmlspecialchars($lop['ten_lop']) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Niên khóa</label>
        <input name="nien_khoa" class="form-control" value="<?= htmlspecialchars($lop['nien_khoa']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Khóa học</label>
        <input name="khoa_hoc" class="form-control" value="<?= htmlspecialchars($lop['khoa_hoc']) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Hệ đào tạo</label>
        <input name="he_dao_tao" class="form-control" value="<?= htmlspecialchars($lop['he_dao_tao']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Bậc đào tạo</label>
        <input name="bac_dao_tao" class="form-control" value="<?= htmlspecialchars($lop['bac_dao_tao']) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Ngành học</label>
        <input name="nganh_hoc" class="form-control" value="<?= htmlspecialchars($lop['nganh_hoc']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Sĩ số</label>
        <input name="si_so" type="number" min="0" class="form-control" value="<?= htmlspecialchars($lop['si_so']) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Cố vấn</label>
        <select name="ma_gv" class="form-select">
          <option value="">— Chọn giảng viên —</option>
          <?php foreach($giangviens as $gv): ?>
            <option value="<?= $gv['ma_gv'] ?>" <?= $lop['ma_gv']==$gv['ma_gv']?'selected':'' ?>>
              <?= htmlspecialchars($gv['ho_ten']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Khoa</label>
        <select name="ma_khoa" class="form-select">
          <option value="">— Chọn khoa —</option>
          <?php foreach($khoas as $k): ?>
            <option value="<?= $k['ma_khoa'] ?>" <?= $lop['ma_khoa']==$k['ma_khoa']?'selected':'' ?>>
              <?= htmlspecialchars($k['ten_khoa']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Mật khẩu lớp</label>
        <input name="mat_khau_lop" class="form-control" value="<?= htmlspecialchars($lop['mat_khau_lop']) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Ghi chú</label>
        <textarea name="ghi_chu" class="form-control" rows="2"><?= htmlspecialchars($lop['ghi_chu']) ?></textarea>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-3">
        <a href="<?= BASE_URL ?>index.php?route=gd_lop" class="btn btn-secondary">Hủy</a>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
