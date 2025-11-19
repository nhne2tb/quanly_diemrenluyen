<?php
// views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php
require_once __DIR__ . '/../../views/headers/header_login.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'bodys_sinhvien') {
  header('Location: ' . BASE_URL . 'bodys_login');
  exit;
}

$conn = Database::connect();

// ✅ Lấy thông tin sinh viên từ bảng lớp tương ứng
$mssv = $_SESSION['user']['id'];
$lopTable = $_SESSION['user']['lop']; // ví dụ: sv_sptin_tin22

$stmt = $conn->prepare("SELECT * FROM `$lopTable` WHERE mssv = ?");
$stmt->execute([$mssv]);
$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
  die("Không tìm thấy thông tin sinh viên!");
}

// ✅ Tạo tên bảng phiếu rèn luyện theo khoa và lớp
$ma_khoa = strtolower($sv['ma_khoa']);
$ma_lop = strtolower($sv['lop']);
$tableRenLuyen = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";

// Kiểm tra xem bảng phiếu có tồn tại chưa
$check = $conn->query("SHOW TABLES LIKE '$tableRenLuyen'");
if ($check->rowCount() === 0) {
  die("<div class='alert alert-warning text-center mt-5'>⚠ Chưa có bảng phiếu rèn luyện cho lớp <b>{$sv['lop']}</b>!</div>");
}

// 🔽 Lấy dữ liệu phiếu rèn luyện theo MSSV
$stmt = $conn->prepare("SELECT * FROM `$tableRenLuyen` WHERE mssv = ? ORDER BY nam_bd ASC, hoc_ky ASC");
$stmt->execute([$mssv]);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kết quả rèn luyện</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f6f8fb; font-family: "Segoe UI", sans-serif; }
.container-box { background: #fff; max-width: 1000px; margin: 40px auto; padding: 35px 40px; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
h3 { font-weight: 700; color: #004aad; margin-bottom: 25px; }
.table th, .table td { vertical-align: middle; }
.row-xuatsac { background-color: #e0f0ff; }
.row-tot { background-color: #e7ffe7; }
.row-kha { background-color: #fff8dc; }
.row-trungbinh { background-color: #f1f1f1; }
.row-yeu { background-color: #ffe6e6; }
.row-kem { background-color: #dddddd; color: #555; }
</style>
</head>
<body>
<div class="container-box">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="m-0"><i class="bi bi-award me-2"></i>KẾT QUẢ RÈN LUYỆN</h3>
    <a href="<?= BASE_URL ?>index.php?route=logout" class="btn btn-danger btn-sm rounded-pill px-3">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>

  <?php if (!$list): ?>
    <div class="alert alert-info text-center py-4">
      <i class="bi bi-info-circle me-2"></i>Chưa có phiếu rèn luyện nào được lưu cho sinh viên này.
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
          <tr>
            <th>STT</th>
            <th>Học kỳ</th>
            <th>Năm học</th>
            <th>Tổng điểm</th>
            <th>Xếp loại</th>
            <th>Trạng thái lớp</th>
            <th>Trạng thái khoa</th>
            <th>Chi tiết</th>
          </tr>
        </thead>
        <tbody class="text-center">
        <?php
        $i = 1;
        foreach ($list as $r):
          $tong = floatval($r['tong_diem']);
          $xl = 'Kém'; $class = 'row-kem';
          if ($tong >= 90) { $xl = 'Xuất sắc'; $class = 'row-xuatsac'; }
          elseif ($tong >= 80) { $xl = 'Tốt'; $class = 'row-tot'; }
          elseif ($tong >= 65) { $xl = 'Khá'; $class = 'row-kha'; }
          elseif ($tong >= 50) { $xl = 'Trung bình'; $class = 'row-trungbinh'; }
          elseif ($tong >= 35) { $xl = 'Yếu'; $class = 'row-yeu'; }

          $badgeLop = match($r['trang_thai_lop'] ?? '') {
            'Đã duyệt' => '<span class="badge bg-success">Đã duyệt</span>',
            'Chờ duyệt' => '<span class="badge bg-warning text-dark">Chờ duyệt</span>',
            'Từ chối' => '<span class="badge bg-danger">Từ chối</span>',
            default => '<span class="badge bg-secondary">Chưa đánh giá</span>'
          };
          $badgeKhoa = match($r['trang_thai_khoa'] ?? '') {
            'Đã duyệt' => '<span class="badge bg-success">Đã duyệt</span>',
            'Chờ duyệt' => '<span class="badge bg-warning text-dark">Chờ duyệt</span>',
            'Từ chối' => '<span class="badge bg-danger">Từ chối</span>',
            default => '<span class="badge bg-secondary">Chưa đánh giá</span>'
          };
        ?>
          <tr class="<?= $class ?>">
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($r['hoc_ky']) ?></td>
            <td><?= htmlspecialchars($r['nam_bd']) ?>–<?= htmlspecialchars($r['nam_kt']) ?></td>
            <td><b><?= $tong ?></b></td>
            <td><b><?= $xl ?></b></td>
            <td><?= $badgeLop ?></td>
            <td><?= $badgeKhoa ?></td>
            <td>
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill view-detail" data-id="<?= $r['id'] ?>">
                <i class="bi bi-eye"></i> Xem
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-clipboard-check me-2"></i>Chi tiết phiếu rèn luyện</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalContent">
        <p class="text-center text-muted">⏳ Đang tải dữ liệu...</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.view-detail').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;
    const modalBody = document.getElementById('modalContent');
    modalBody.innerHTML = '<p class="text-center text-muted">⏳ Đang tải dữ liệu...</p>';
    const res = await fetch('<?= BASE_URL ?>views/phieu_ren_luyen/xem_chi_tiet_api.php?id=' + id + '&table=<?= $tableRenLuyen ?>');
    const html = await res.text();
    modalBody.innerHTML = html;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
  });
});
</script>
</body>
</html>
