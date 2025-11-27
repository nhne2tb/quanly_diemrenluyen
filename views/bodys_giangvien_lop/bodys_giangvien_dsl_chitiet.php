<?php
// ============================================
// FILE: views/bodys_giangvien_lop/bodys_giangvien_dsl_chitiet.php
// ============================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    header("Location: " . BASE_URL . "index.php?route=bodys_login");
    exit;
}

$ma_gv = $_SESSION['user']['id'];
$ma_khoa = strtolower($_SESSION['user']['khoa'] ?? '');

$lop = $_GET['lop'] ?? '';
if (!$lop) die("<h3 style='color:red'>Thiếu tham số lớp!</h3>");

$lop_lower = strtolower($lop);

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tên bảng sinh viên
$table_sv = "sv_{$ma_khoa}_{$lop_lower}";

// Kiểm tra bảng tồn tại
$stmt = $conn->prepare("SHOW TABLES LIKE ?");
$stmt->execute([$table_sv]);
if ($stmt->rowCount() == 0) {
    die("<div class='alert alert-danger m-3'>Không tìm thấy bảng sinh viên: <b>$table_sv</b></div>");
}

// Lấy danh sách sinh viên
$sql = "SELECT * FROM `$table_sv` ORDER BY mssv ASC";
$stmt = $conn->query($sql);
$svList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách sinh viên lớp <?= strtoupper($lop) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --brand: #004aad;
    --brand-light: #e8f0ff;
}

body {
    background: #f4f7fc;
}

.text-primary-title {
    color: var(--brand) !important;
}

.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}

/* ======== BỘ LỌC ======== */
.filter-box {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.filter-box label {
    font-weight: 600;
    color: var(--brand);
    font-size: 14px;
}

.filter-box .form-select,
.filter-box .form-control {
    border-radius: 10px;
    border: 1px solid #d5dff0;
    box-shadow: none;
    transition: 0.2s;
}

.filter-box .form-select:focus,
.filter-box .form-control:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 0.15rem rgba(0,74,173,0.15);
}

/* ======== NÚT QUAY LẠI ======== */
.back-btn {
    color: var(--brand);
    font-weight: 600;
    text-decoration: none;
    font-size: 15px;
}

.back-btn:hover {
    text-decoration: underline;
    color: var(--brand);
}

/* ======== BẢNG ======== */
.table thead th {
    background: var(--brand);
    color: white;
    font-weight: 600;
    border-bottom: none;
}

.table-bordered td {
    background: #fff;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: #eef4ff;
}

/* ======== BADGES ======== */
.badge.bg-success {
    background: #2eb85c !important;
}

.badge.bg-warning {
    background: #f0ad4e !important;
}

.badge.bg-secondary {
    background: #bfc6d4 !important;
}

.badge.bg-info {
    background: #39f !important;
}

/* ====== NÚT ====== */
.btn-compact {
    padding: 4px 10px;
    font-size: 12px;
}

.btn-success {
    background: #2eb85c !important;
    border: none !important;
}

.btn-warning {
    background: #ffca2c !important;
    border: none !important;
    color: #333 !important;
}

.btn-outline-primary {
    border-color: var(--brand);
    color: var(--brand);
}

.btn-outline-primary:hover {
    background: var(--brand);
    color: white;
}

</style>

</head>

<body>

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="page-title m-0">Danh sách sinh viên — Lớp <?= strtoupper($lop) ?></h3>

        <!-- <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_dsl"
           class="btn btn-sm back-btn">
            <i class="bi bi-arrow-left-circle"></i> Quay lại
        </a> -->

        <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" 
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>
    </div>

    <!-- TÌM KIẾM -->
    <div class="input-group mb-3 search-box">
        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm MSSV hoặc tên...">
    </div>

    <!-- DANH SÁCH SV -->
    <div class="card p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" id="svTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>MSSV</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Ngành</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($svList)): ?>
                    <tr><td colspan="6" class="text-muted py-3">Không có sinh viên nào.</td></tr>

                <?php else:
                    $i = 1;
                    foreach ($svList as $sv): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($sv['mssv']) ?></td>
                            <td><?= htmlspecialchars($sv['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($sv['gioi_tinh']) ?></td>
                            <td>
                                <?= !empty($sv['ngay_sinh'])
                                        ? date('d/m/Y', strtotime($sv['ngay_sinh']))
                                        : "—"
                                ?>
                            </td>
                            <td><?= htmlspecialchars($sv['nganh_hoc']) ?></td>
                        </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
// Tìm kiếm theo MSSV + tên
document.getElementById("searchInput").addEventListener("keyup", function () {
    let keyword = this.value.toLowerCase();
    document.querySelectorAll("#svTable tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
    });
});
</script>

</body>
</html>
