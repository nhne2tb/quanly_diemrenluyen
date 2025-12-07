<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$msg = null; 
$msgType = 'success';

// ===== Lấy danh sách khoa =====
$khoas = $conn->query("SELECT ma_khoa, ten_khoa FROM tb_khoa ORDER BY ten_khoa")->fetchAll(PDO::FETCH_ASSOC);

// ===== Xử lý khi gửi form =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_gv = trim($_POST['ma_gv']);
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
        // --- Kiểm tra trùng mã GV ---
        $check = $conn->prepare("SELECT 1 FROM tb_giangvien WHERE ma_gv = ?");
        $check->execute([$ma_gv]);
        if ($check->fetch()) {
            throw new Exception("Mã giảng viên <b>$ma_gv</b> đã tồn tại!");
        }

        // --- Thêm mới ---
$mat_khau = password_hash('123456a', PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO tb_giangvien
    (ma_gv, ho_ten, gioi_tinh, ngay_sinh, hoc_ham, hoc_vi, chuc_danh, 
     chuyen_mon, nhiem_vu, nam_cong_tac, email, sdt, bo_mon, 
     ma_khoa, avatar, mat_khau) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->execute([
    $ma_gv,          // 1
    $ho_ten,         // 2
    $gioi_tinh,      // 3
    $ngay_sinh,      // 4
    $hoc_ham,        // 5
    $hoc_vi,         // 6
    $chuc_danh,      // 7
    $chuyen_mon,     // 8
    $nhiem_vu,       // 9
    $nam_cong_tac,   // 10
    $email,          // 11
    $sdt,            // 12
    $bo_mon,         // 13
    $ma_khoa,        // 14
    $avatar,         // 15
    $mat_khau        // 16
]);



header('Location: ' . BASE_URL . 'index.php?route=gd_giangvien&msg=' . urlencode('Thêm giảng viên thành công') . '&type=success');
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
<title>Thêm Giảng viên</title>
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
      <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i> Thêm Giảng viên</h5>
<!-- <a href="<?= BASE_URL ?>index.php?route=gd_giangvien" class="btn btn-light btn-sm">
    <i class="bi bi-arrow-left"></i> Quay lại
</a> -->

    <a href="<?= BASE_URL ?>index.php?route=gd_giangvien"
       class="btn btn-sm rounded-pill"
       style="border:1px solid #ffffffff; color:#ffffffff; font-weight:600;">
        <i class="bi bi-arrow-left-circle"></i> Quay lại
    </a>
    </div>

    <div class="card-body">
      <?php if($msg): ?>
        <div class="alert alert-<?= $msgType ?>"><?= $msg ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label required">Mã GV</label>
            <input name="ma_gv" class="form-control" required placeholder="VD: GV001">
          </div>
          <div class="col-md-8">
            <label class="form-label required">Họ tên</label>
            <input name="ho_ten" class="form-control" required placeholder="VD: ThS. Nguyễn Văn Giảng">
          </div>
          <div class="col-md-4">
            <label class="form-label">Giới tính</label>
            <select name="gioi_tinh" class="form-select">
              <option value="Nam">Nam</option>
              <option value="Nữ">Nữ</option>
              <option value="Khác">Khác</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="ngay_sinh" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Năm công tác</label>
            <input type="number" name="nam_cong_tac" class="form-control" placeholder="VD: 12">
          </div>
          <div class="col-md-6">
            <label class="form-label">Học hàm</label>
            <input name="hoc_ham" class="form-control" placeholder="VD: Thạc sĩ">
          </div>
          <div class="col-md-6">
            <label class="form-label">Học vị</label>
            <input name="hoc_vi" class="form-control" placeholder="VD: Đại học">
          </div>
          <div class="col-md-6">
            <label class="form-label">Chức danh</label>
            <input name="chuc_danh" class="form-control" placeholder="VD: Giảng viên chính">
          </div>
          <div class="col-md-6">
            <label class="form-label">Bộ môn</label>
            <input name="bo_mon" class="form-control" placeholder="VD: Sư phạm Tin học">
          </div>
          <div class="col-12">
            <label class="form-label">Chuyên môn</label>
            <textarea name="chuyen_mon" class="form-control" rows="2" placeholder="VD: Công nghệ phần mềm, Sư phạm Tin học"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Nhiệm vụ</label>
            <textarea name="nhiem_vu" class="form-control" rows="2" placeholder="VD: Giảng dạy, Cố vấn học tập lớp SPTIN22A,..."></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="VD: giangnv@dthu.edu.vn">
          </div>
          <div class="col-md-6">
            <label class="form-label">SĐT</label>
            <input name="sdt" class="form-control" placeholder="VD: 0912345678">
          </div>
          <div class="col-md-6">
            <label class="form-label">Khoa</label>
            <select name="ma_khoa" class="form-select">
              <option value="">-- Chọn khoa --</option>
              <?php foreach($khoas as $k): ?>
                <option value="<?= htmlspecialchars($k['ma_khoa']) ?>">
                  <?= htmlspecialchars($k['ten_khoa']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Ảnh đại diện (URL)</label>
            <input name="avatar" class="form-control" placeholder="VD: https://cdn-icons-png.flaticon.com/512/2202/2202112.png">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <button type="reset" class="btn btn-secondary">Nhập lại</button>
          <button type="submit" class="btn btn-primary">Thêm giảng viên</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
