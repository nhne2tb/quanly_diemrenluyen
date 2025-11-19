<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$msg = null; 
$msgType = 'success';

// ====== Lấy mã GV từ URL ======
$ma_gv = $_GET['ma'] ?? '';
if (!$ma_gv) {
    header('Location: gd_giangvien.php');
    exit;
}

// ====== Lấy thông tin GV ======
$stmt = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ?");
$stmt->execute([$ma_gv]);
$gv = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$gv) {
    header('Location: gd_giangvien.php?msg='.urlencode('Không tìm thấy giảng viên').'&type=danger');
    exit;
}

// ====== Lấy danh sách khoa ======
$khoas = $conn->query("SELECT ma_khoa, ten_khoa FROM tb_khoa ORDER BY ten_khoa")->fetchAll(PDO::FETCH_ASSOC);

// ====== Xử lý cập nhật ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = trim($_POST['ho_ten']);
    $gioi_tinh = $_POST['gioi_tinh'] ?? 'Nam';
    $ngay_sinh = $_POST['ngay_sinh'] ?: null;
    $hoc_ham = trim($_POST['hoc_ham']);
    $hoc_vi = trim($_POST['hoc_vi']);
    $chuc_danh = trim($_POST['chuc_danh']);
    $bo_mon = trim($_POST['bo_mon']);
    $chuyen_mon = trim($_POST['chuyen_mon']);
    $nhiem_vu = trim($_POST['nhiem_vu']);
    $nam_cong_tac = (int)($_POST['nam_cong_tac'] ?? 0);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);
    $ma_khoa = $_POST['ma_khoa'] ?: null;
    $avatar = trim($_POST['avatar']);

    try {
        $update = $conn->prepare("
            UPDATE tb_giangvien SET 
                ho_ten=?, gioi_tinh=?, ngay_sinh=?, hoc_ham=?, hoc_vi=?, chuc_danh=?, 
                bo_mon=?, chuyen_mon=?, nhiem_vu=?, nam_cong_tac=?, 
                email=?, sdt=?, ma_khoa=?, avatar=? 
            WHERE ma_gv=?
        ");
        $update->execute([
            $ho_ten, $gioi_tinh, $ngay_sinh, $hoc_ham, $hoc_vi, $chuc_danh,
            $bo_mon, $chuyen_mon, $nhiem_vu, $nam_cong_tac,
            $email, $sdt, $ma_khoa, $avatar,
            $ma_gv
        ]);

        header('Location: gd_giangvien.php?msg='.urlencode('Cập nhật giảng viên thành công').'&type=success');
        exit;
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sửa Giảng viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
label.required::after { content:" *"; color:red; }
</style>
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:900px">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Sửa Giảng viên: <?= htmlspecialchars($gv['ma_gv']) ?></h5>
      <a href="gd_giangvien.php" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>

    <div class="card-body">
      <?php if($msg): ?>
        <div class="alert alert-<?= $msgType ?>"><?= $msg ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label required">Mã GV</label>
            <input class="form-control" value="<?= htmlspecialchars($gv['ma_gv']) ?>" disabled>
          </div>
          <div class="col-md-8">
            <label class="form-label required">Họ tên</label>
            <input name="ho_ten" class="form-control" required value="<?= htmlspecialchars($gv['ho_ten']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Giới tính</label>
            <select name="gioi_tinh" class="form-select">
              <?php foreach(['Nam','Nữ','Khác'] as $gt): ?>
                <option <?= $gv['gioi_tinh']==$gt?'selected':'' ?>><?= $gt ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="ngay_sinh" class="form-control" value="<?= htmlspecialchars($gv['ngay_sinh']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Năm công tác</label>
            <input type="number" name="nam_cong_tac" class="form-control" value="<?= htmlspecialchars($gv['nam_cong_tac']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Học hàm</label>
            <input name="hoc_ham" class="form-control" value="<?= htmlspecialchars($gv['hoc_ham']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Học vị</label>
            <input name="hoc_vi" class="form-control" value="<?= htmlspecialchars($gv['hoc_vi']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Chức danh</label>
            <input name="chuc_danh" class="form-control" value="<?= htmlspecialchars($gv['chuc_danh']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Bộ môn</label>
            <input name="bo_mon" class="form-control" value="<?= htmlspecialchars($gv['bo_mon']) ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Chuyên môn</label>
            <textarea name="chuyen_mon" class="form-control" rows="2"><?= htmlspecialchars($gv['chuyen_mon']) ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Nhiệm vụ</label>
            <textarea name="nhiem_vu" class="form-control" rows="2"><?= htmlspecialchars($gv['nhiem_vu']) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($gv['email']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">SĐT</label>
            <input name="sdt" class="form-control" value="<?= htmlspecialchars($gv['sdt']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Khoa</label>
            <select name="ma_khoa" class="form-select">
              <option value="">-- Chọn khoa --</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= htmlspecialchars($k['ma_khoa']) ?>" <?= $gv['ma_khoa']==$k['ma_khoa']?'selected':'' ?>>
                  <?= htmlspecialchars($k['ten_khoa']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Ảnh đại diện (URL)</label>
            <input name="avatar" class="form-control" value="<?= htmlspecialchars($gv['avatar']) ?>">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
