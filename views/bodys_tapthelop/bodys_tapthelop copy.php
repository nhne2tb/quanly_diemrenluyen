<?php
// ===========================================
// views/bodys_tapthelop/bodys_tapthelop.php
// ===========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// ============= KIỂM TRA ĐĂNG NHẬP LỚP =============
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_tapthelop') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

// LỚP VÀ KHOA TỪ SESSION
$ma_lop  = strtolower($_SESSION['user']['id']   ?? '');
$ma_khoa = strtolower($_SESSION['user']['khoa'] ?? '');

if (!$ma_lop || !$ma_khoa) {
    $_SESSION['login_error'] = "Phiên đăng nhập không hợp lệ. Hãy đăng nhập lại.";
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$conn = Database::connect();

// Tên bảng
$table_prl = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";
$table_sv  = "sv_{$ma_khoa}_{$ma_lop}";

// ================== BỘ LỌC ==================
$filter_hk = $_GET['hoc_ky'] ?? '';
$filter_nh = $_GET['nam_hoc'] ?? '';

$where_prl = "";
$nh_bd = $nh_kt = null;

if ($filter_hk !== '') {
    $where_prl .= " AND prl.hoc_ky = " . $conn->quote($filter_hk);
}

if ($filter_nh !== '') {
    [$nh_bd, $nh_kt] = explode('-', $filter_nh);
    $where_prl .= " AND prl.nam_bd = " . intval($nh_bd) . " AND prl.nam_kt = " . intval($nh_kt);
}

// ================== LẤY DỮ LIỆU ==================
$sql = "
    SELECT 
        sv.mssv,
        sv.ho_ten,

        prl.id,
        prl.hoc_ky,
        prl.nam_bd,
        prl.nam_kt,
        prl.tong_diem,
        prl.trang_thai,
        prl.trang_thai_lop,
        prl.ghi_chu_lop,
        prl.ghi_chu_co_van,
        prl.nguoi_danh_gia,
        prl.ngay_capnhat


    FROM `$table_sv` sv
    LEFT JOIN `$table_prl` prl
        ON sv.mssv = prl.mssv
        " . ($filter_hk ? " AND prl.hoc_ky = " . $conn->quote($filter_hk) : "") . "
        " . ($filter_nh ? " AND prl.nam_bd = " . intval($nh_bd) . " AND prl.nam_kt = " . intval($nh_kt) : "") . "

    WHERE sv.mssv IS NOT NULL
    ORDER BY sv.mssv ASC
";


$stmt = $conn->prepare($sql);
$stmt->execute();
$dsPhieu = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu rèn luyện lớp <?= strtoupper($ma_lop) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table thead th { background:#004aad;color:white; }
    </style>
</head>

<body class="bg-light">

<div class="container mt-4">

    <h3 class="text-center fw-bold mb-3">
        📘 PHIẾU RÈN LUYỆN — LỚP <?= strtoupper($ma_lop) ?>
    </h3>

    <a href="<?= BASE_URL ?>index.php?route=logout" class="btn btn-danger mb-3">Đăng xuất</a>

    <!-- ================== BỘ LỌC ==================
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="GET">
                <input type="hidden" name="route" value="bodys_tapthelop">

                <div class="row g-2">

                    <div class="col-md-3">
                        <label>Học kỳ</label>
                        <select name="hoc_ky" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="I"  <?= $filter_hk == 'I'  ? 'selected' : '' ?>>I</option>
                            <option value="II" <?= $filter_hk == 'II' ? 'selected' : '' ?>>II</option>
                            <option value="Hè" <?= $filter_hk == 'Hè' ? 'selected' : '' ?>>Hè</option>
                        </select>
</div>

                    <div class="col-md-3">
                        <label>Năm học</label>
                        <select name="nam_hoc" class="form-select">
                            <option value="">Tất cả</option>
                            <?php for ($y = 2020; $y <= 2030; $y++):
                                $opt = $y . "-" . ($y + 1);
                            ?>
                                <option value="<?= $opt ?>" <?= $filter_nh == $opt ? 'selected' : '' ?>>
                                    <?= $opt ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 mt-4">Lọc</button>
                    </div>

                    <div class="col-md-2">
                        <a href="index.php?route=bodys_tapthelop" class="btn btn-secondary w-100 mt-4">Xóa lọc</a>
                    </div>

                </div>
            </form>
        </div>
    </div> -->

    <!-- ================== DANH SÁCH ================== -->
    <div class="card shadow">
        <div class="card-header fw-bold bg-white">
            Danh sách sinh viên lớp <?= strtoupper($ma_lop) ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="text-center">
                    <tr>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                        <th>Học kỳ</th>
                        <th>Năm học</th>
                        <th>Điểm</th>
                        <th>CVHT</th>
                        <th>Lớp</th>
                        
                        <th>Người đánh giá</th>
                        <th>Ghi chú lớp</th>
<th>Ghi chú CVHT</th>

                        <th>Xem</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($dsPhieu)): ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">
                            Không có dữ liệu.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dsPhieu as $row): ?>
                        <?php
                            // Tính giá trị hiển thị cho Học kỳ
                            $displayHK = $row['hoc_ky'];
                            if (!$displayHK && $filter_hk !== '') {
                                $displayHK = $filter_hk; // dùng HK đang lọc
                            }

                            // Tính giá trị hiển thị cho Năm học
                            if ($row['nam_bd']) {
                                $displayNH = $row['nam_bd'] . '–' . $row['nam_kt'];
                            } elseif ($filter_nh !== '') {
                                [$f_bd, $f_kt] = explode('-', $filter_nh);
                                $displayNH = $f_bd . '–' . $f_kt; // dùng năm học đang lọc
                            } else {
                                $displayNH = '—';
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?= $row['mssv'] ?></td>
                            <td><?= htmlspecialchars($row['ho_ten']) ?></td>

                            <td class="text-center">
                                <?= $displayHK ?: '<span class="text-muted">—</span>' ?>
                            </td>

                            <td class="text-center">
                                <?= $displayNH ?: '<span class="text-muted">—</span>' ?>
                            </td>

                            <td class="text-center fw-bold text-primary">
                                <?= $row['tong_diem'] ?: '<span class="text-muted">—</span>' ?>
                            </td>

                            <!-- Trạng thái cá nhân -->
                            <td class="text-center">
                                <?php if (!$row['id']): ?>
                                    <span class="badge bg-secondary">SV chưa đánh giá</span>
                                <?php elseif ($row['trang_thai'] === 'Đã duyệt'): ?>
                                    <span class="badge bg-success">Đã duyệt</span>
                                <?php elseif ($row['trang_thai'] === 'Trả về'): ?>
                                    <span class="badge bg-warning text-dark">Trả về</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Chưa duyệt</span>
                                <?php endif; ?>
                            </td>

<!-- Trạng thái lớp + nút duyệt / trả về -->
<td class="text-center">

    <?php if (!$row['id']): ?>

        <!-- SV chưa đánh giá -->
        <span class="badge bg-secondary mb-1">Chưa duyệt</span>

    <?php else: ?>

        <?php
            // Hiển thị badge trạng thái hiện tại
            if ($row['trang_thai_lop'] === 'Đã duyệt') {
                echo '<span class="badge bg-success mb-1 d-block">Đã duyệt</span>';
            } elseif ($row['trang_thai_lop'] === 'Trả về') {
                echo '<span class="badge bg-warning text-dark mb-1 d-block">Trả về</span>';
            } else {
                echo '<span class="badge bg-secondary mb-1 d-block">Chưa duyệt</span>';
            }
        ?>

        <?php if ($row['trang_thai_lop'] !== 'Đã duyệt'): ?>
            
            <!-- Nút Duyệt -->
            <a href="index.php?route=capnhat_trang_thai_lop&id=<?= $row['id'] ?>&action=duyet&table=<?= $table_prl ?>"
               class="btn btn-sm btn-success w-100 mb-1">
                Duyệt
            </a>

            <!-- Nút Trả về -->
            <button class="btn btn-sm btn-warning w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTraVe"
                    data-id="<?= $row['id'] ?>">
                Trả về
            </button>

        <?php endif; ?>

    <?php endif; ?>

</td>


                            <!-- Người đánh giá -->
                            <td class="text-center">
                                <?= $row['nguoi_danh_gia'] ?: '<span class="text-muted">SV chưa đánh giá</span>' ?>
                            </td>
                            
                            <td class="text-center">
    <?= $row['ghi_chu_lop'] ?: '<span class="text-muted">—</span>' ?>
</td>

<td class="text-center">
    <?= $row['ghi_chu_co_van'] ?: '<span class="text-muted">—</span>' ?>
</td>


                            <!-- Xem -->
                            <td class="text-center">
                                <?php if ($row['id']): ?>
                                    <a href="<?= BASE_URL ?>index.php?route=xem_phieu&id=<?= $row['id'] ?>&table=<?= $table_prl ?>"
                                       class="btn btn-sm btn-outline-primary">Xem</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

</body>
</html>
