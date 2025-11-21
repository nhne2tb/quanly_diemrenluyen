<?php
// ============================================
// FILE: views/bodys_giangvien_lop/hoso_giangvien.php
// ============================================

// Kiểm tra phiên
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// Bắt buộc đăng nhập giảng viên
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$ma_gv = $_SESSION['user']['id'];
$conn = Database::connect();

// Lấy thông tin giảng viên
$stmt = $conn->prepare("SELECT * FROM tb_giangvien WHERE ma_gv = ?");
$stmt->execute([$ma_gv]);
$giangvien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$giangvien) {
    echo "<div class='alert alert-danger m-3'>Không tìm thấy hồ sơ giảng viên.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hồ sơ giảng viên</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root { --brand:#004aad; --bg:#f6f8fc; }
body { background:var(--bg); font-family:'Segoe UI', system-ui, sans-serif; }
.page-title { font-weight:700; color:var(--brand); }
.card { border:none; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,.08); }
</style>
</head>

<body>
<div class="container-xxl py-4">

<h4 class="page-title mb-4">HỒ SƠ GIẢNG VIÊN</h4>


    <!-- THÔNG TIN GIẢNG VIÊN -->
    <div class="card p-4 mb-4">
        <div class="row g-3 align-items-center">
    <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien"
       class="btn btn-outline-primary btn-sm me-2"
       style="border-radius:8px;">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
            <!-- Avatar -->
            <div class="col-md-3 text-center">
                
                <img src="<?= htmlspecialchars($giangvien['avatar'] ?? BASE_URL.'assets/images/avatar_default.png') ?>"
                     class="rounded-circle mb-2"
                     width="120" height="120">

                <h5 class="mb-1"><?= htmlspecialchars($giangvien['ho_ten']) ?></h5>
                <span class="badge bg-primary">
                    <?= htmlspecialchars($giangvien['chuc_danh'] ?? 'Giảng viên') ?>
                </span>
            </div>

            <!-- Thông tin -->
            <div class="col-md-9">
                <div class="row">

                    <div class="col-md-6">
                        <p class="mb-1"><b>Mã GV:</b> <?= htmlspecialchars($giangvien['ma_gv']) ?></p>
                        <p class="mb-1"><b>Giới tính:</b> <?= htmlspecialchars($giangvien['gioi_tinh'] ?? '') ?></p>
                        <p class="mb-1"><b>Ngày sinh:</b>
                            <?= !empty($giangvien['ngay_sinh']) ? date('d/m/Y', strtotime($giangvien['ngay_sinh'])) : '' ?>
                        </p>
                        <p class="mb-1"><b>Học hàm/Học vị:</b>
                            <?= htmlspecialchars(($giangvien['hoc_ham'] ?? '').' - '.($giangvien['hoc_vi'] ?? '')) ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p class="mb-1"><b>Bộ môn:</b> <?= htmlspecialchars($giangvien['bo_mon'] ?? '') ?></p>
                        <p class="mb-1"><b>Khoa:</b> <?= htmlspecialchars($giangvien['ma_khoa'] ?? '') ?></p>
                        <p class="mb-1"><b>Email:</b> <?= htmlspecialchars($giangvien['email'] ?? '') ?></p>
                        <p class="mb-1"><b>SĐT:</b> <?= htmlspecialchars($giangvien['sdt'] ?? '') ?></p>
                    </div>

                </div>

                <div class="mt-3"><b>Nhiệm vụ:</b> <?= htmlspecialchars($giangvien['nhiem_vu'] ?? '') ?></div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
