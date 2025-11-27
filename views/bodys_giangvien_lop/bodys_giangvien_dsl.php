<?php
// views/bodys_giangvien_lop/bodys_giangvien_dsl.php    
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$ma_gv = $_SESSION['user']['id'] ?? '';

$conn = Database::connect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// LẤY LỚP PHỤ TRÁCH
$stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_gv = ?");
$stmt->execute([$ma_gv]);
$lopList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tạo tên bảng sinh viên theo lớp
function getStudentTable($lop) {
    return "sv_sptin_" . strtolower($lop);
}

// Kiểm tra bảng tồn tại
function tableExists(PDO $conn, $table) {
    $stmt = $conn->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$table]);
    return $stmt->rowCount() > 0;
}
?>

<style>
    body {
        background: #f2f6fc;
        font-family: "Segoe UI", sans-serif;
    }
    .page-title {
        color: #004aad;
        font-weight: 700;
        font-size: 28px;
    }
    .back-btn {
        background: #004aad;
        color: #fff;
        border-radius: 8px;
        padding: 8px 16px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.2s;
    }
    .back-btn:hover {
        background: #003984;
    }

    .class-card {
        border-radius: 20px;
        padding: 26px;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.10);
        transition: 0.25s ease;
        border-top: 4px solid #004aad;
    }
    .class-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.15);
    }

    .class-name {
        font-size: 24px;
        font-weight: 700;
        color: #004aad;
        margin-bottom: 8px;
    }

    .class-info {
        font-size: 15px;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .btn-view {
        text-decoration: none;
        padding: 10px 20px;
        background: #004aad;
        color: white;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.2s;
    }
    .btn-view:hover {
        background: #003984;
    }


.text-btn {
    background: transparent !important;
    color: #004aad !important;
    padding: 0;
    border: none;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}

.text-btn:hover {
    color: #003984 !important;
    text-decoration: underline;
}

</style>

<div class="container py-4">



<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="page-title m-0">Danh sách các lớp phụ trách</h3>
<!-- 
    <a href="<?= BASE_URL ?>index.php?route=dashboard" class="text-btn">
        ← Quay lại
    </a> -->

    <a href="<?= BASE_URL ?>index.php?route=dashboard" 
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>
</div>


    <?php if (empty($lopList)): ?>
        <div class="alert alert-info">
            Bạn hiện chưa phụ trách lớp nào.
        </div>
    <?php else: ?>

        <div class="row d-flex justify-content-start">

            <?php foreach ($lopList as $lop): ?>

                <?php
                    $table_sv = getStudentTable($lop['ma_lop']);
                    $si_so_thuc = 0;

                    if (tableExists($conn, $table_sv)) {
                        $count = $conn->prepare("SELECT COUNT(*) AS total FROM `$table_sv`");
                        $count->execute();
                        $si_so_thuc = $count->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
                    }
                ?>

                <div class="col-md-4 mb-4">
                    <div class="class-card">
                        <div class="class-name"><?= $lop['ma_lop'] ?></div>

                        <div class="class-info">
                            <strong>Tên lớp:</strong> <?= $lop['ten_lop'] ?><br>
                            <strong>Niên khóa:</strong> <?= $lop['nien_khoa'] ?><br>
                            <strong>Sĩ số:</strong> <?= $si_so_thuc ?> sinh viên
                        </div>

                        <a class="btn-view" href="<?= BASE_URL ?>index.php?route=bodys_giangvien_dsl_chitiet&lop=<?= $lop['ma_lop'] ?>">
                            Xem danh sách sinh viên
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>
