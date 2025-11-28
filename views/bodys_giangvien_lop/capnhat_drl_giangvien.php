<?php
// ===========================================
// views/bodys_giangvien_lop/capnhat_drl_giangvien.php
// (CVHT: duyệt / trả về -> lưu vào trang_thai, ghi_chu_co_van)
// ===========================================

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

// ============= KIỂM TRA ĐĂNG NHẬP =============
if (!isset($_SESSION['user']) || ($_SESSION['user']['type'] ?? '') !== 'bodys_giangvien') {
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$ma_lop  = $_GET['lop'] ?? '';
$ma_khoa = strtolower($_SESSION['user']['khoa'] ?? '');

if (!$ma_lop || !$ma_khoa) {
    die("<h3 style='color:red'>Thiếu tham số lớp hoặc khoa!</h3>");
}

$ma_lop = strtolower($ma_lop);

// Kết nối DB
$conn = Database::connect();

// ================== TÊN BẢNG ==================
$table_sv  = "sv_{$ma_khoa}_{$ma_lop}";
$table_prl = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";

// ================== KIỂM TRA BẢNG ==================
function tableExists(PDO $conn, $table) {
    $stmt = $conn->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$table]);
    return $stmt->rowCount() > 0;
}

if (!tableExists($conn, $table_sv))
    die("<h3 style='color:red'>Không tìm thấy bảng sinh viên: $table_sv</h3>");

if (!tableExists($conn, $table_prl))
    die("<h3 style='color:red'>Không tìm thấy bảng DRL: $table_prl</h3>");

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
<title>Điểm rèn luyện — Lớp <?= strtoupper($ma_lop) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .text-primary-title { color:#004aad !important; }
    .table thead th { background:#004aad; color:white; }
    .btn-compact { padding:3px 8px; font-size:12px; }

    /* Nút Quay lại */
.btn-outline-primary {
    border-color: #004aad !important;
    color: #004aad !important;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background: #004aad !important;
    color: #fff !important;
}
</style>
</head>

<body class="bg-light">

<div class="container mt-4">

<!-- ===== TIÊU ĐỀ ===== -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary-title m-0">
        Điểm rèn luyện — Lớp <?= strtoupper($ma_lop) ?>
    </h3>

    <!-- <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" class="text-decoration-none text-primary-title">
        ← Quay lại
    </a> -->

    <a href="<?= BASE_URL ?>index.php?route=bodys_giangvien_drl" 
   class="btn btn-outline-primary btn-sm rounded-pill">
    <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>
</div>

<!-- ===== BỘ LỌC ===== -->
<div class="card p-3 mb-3">
  <div class="row g-3">

    <!-- Lọc theo trạng thái CVHT -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Trạng thái CVHT</label>
        <select id="filterCVHT" class="form-select form-select-sm">
            <option value="">Tất cả</option>
            <option value="Đã duyệt">Đã duyệt</option>
            <option value="Trả về">Trả về</option>
            <option value="Chưa duyệt">Chưa duyệt</option>
                        <option value="Chưa duyệt">Chưa duyệt</option>

        </select>
    </div>

    <!-- Lọc theo học kỳ -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Học kỳ</label>
        <select id="filterHK" class="form-select form-select-sm">
            <option value="">Tất cả</option>
            <option value="I">I</option>
            <option value="II">II</option>
            <option value="Hè">Hè</option>
        </select>
    </div>

    <!-- Lọc theo năm -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Năm học</label>
        <input type="text" id="filterNam" class="form-control form-control-sm"
               placeholder="VD: 2025–2026">
    </div>

    <!-- Lọc theo tên hoặc MSSV -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Tìm nhanh</label>
        <input type="text" id="filterText" class="form-control form-control-sm"
               placeholder="Nhập tên hoặc MSSV...">
    </div>

  </div>
</div>



<!-- ===== DANH SÁCH SV ===== -->
<div class="card shadow">
    <div class="card-header bg-white fw-bold">
        Danh sách sinh viên lớp <?= strtoupper($ma_lop) ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead class="text-center">
            <tr>
                <th>STT</th>
                <th>MSSV</th>
                <th>Họ tên</th>
                <th>Học kỳ</th>
                <th>Năm học</th>
                <th>Điểm</th>
                <th>CVHT</th>
                <th>Lớp</th>
                <th>Ghi chú lớp</th>
                <th>Ghi chú CVHT</th>
                <th>Duyệt</th>
                <th>Trả về</th>
                <th>Xem</th>
            </tr>
            </thead>

            <tbody>

<?php if (empty($dsPhieu)): ?>
    <tr><td colspan="13" class="text-center py-3">Không có dữ liệu.</td></tr>
<?php else: ?>

<?php $stt = 1;
foreach ($dsPhieu as $row): ?>

<tr data-id="<?= $row['id'] ?>">

    <td class="text-center"><?= $stt++ ?></td>
    <td class="text-center"><?= $row['mssv'] ?></td>
    <td><?= htmlspecialchars($row['ho_ten']) ?></td>

    <!-- Học kỳ -->
    <td class="text-center"><?= $row['hoc_ky'] ?: "—" ?></td>

    <!-- Năm học -->
    <td class="text-center">
        <?= ($row['nam_bd'] && $row['nam_kt']) ? $row['nam_bd']."–".$row['nam_kt'] : "—" ?>
    </td>

    <!-- Điểm -->
    <td class="text-center fw-bold text-primary-title">
        <?= $row['tong_diem'] ?: "—" ?>
    </td>

    <!-- Trạng thái CVHT -->
    <td class="text-center col-cvht">
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

    <!-- Trạng thái lớp (chỉ hiển thị) -->
    <td class="text-center col-lop">
        <?php if (!$row['id']): ?>
            <span class="badge bg-secondary">Chưa đánh giá</span>
        <?php elseif ($row['trang_thai_lop'] === 'Đã duyệt'): ?>
            <span class="badge bg-success">Đã duyệt</span>
        <?php elseif ($row['trang_thai_lop'] === 'Trả về'): ?>
            <span class="badge bg-warning text-dark">Trả về</span>
        <?php else: ?>
            <span class="badge bg-secondary">Chưa duyệt</span>
        <?php endif; ?>
    </td>

    <!-- Ghi chú lớp -->
    <td class="text-center col-ghichu-lop"><?= $row['ghi_chu_lop'] ?: "—" ?></td>

    <!-- Ghi chú CVHT -->
    <td class="text-center col-ghichu-cvht"><?= $row['ghi_chu_co_van'] ?: "—" ?></td>

    <!-- Nút duyệt (CVHT) -->
    <td class="text-center col-duyet">
        <?php if ($row['id']): ?>
            <button class="btn btn-success btn-compact btn-duyet-cvht"
                    data-id="<?= $row['id'] ?>"
                    data-table="<?= $table_prl ?>">
                Duyệt
            </button>
        <?php else: ?>—<?php endif; ?>
    </td>

    <!-- Nút trả về (CVHT) -->
    <td class="text-center col-trave">
        <?php if ($row['id']): ?>
            <button class="btn btn-warning btn-compact btn-trave-cvht"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTraVe"
                    data-id="<?= $row['id'] ?>">
                Trả về
            </button>
        <?php else: ?>—<?php endif; ?>
    </td>

    <!-- Xem phiếu -->
    <td class="text-center">
        <?php if ($row['id']): ?>
            <a href="<?= BASE_URL ?>index.php?route=xem_phieu_ren_luyen_sv&id=<?= $row['id'] ?>&table=<?= $table_prl ?>"
               class="btn btn-outline-primary btn-compact">
                Xem
            </a>
        <?php else: ?>—<?php endif; ?>
    </td>

</tr>

<?php endforeach; endif; ?>

            </tbody>
        </table>
    </div>
</div>

</div>

<!--=================== MODAL TRẢ VỀ CVHT ===================-->
<div class="modal fade" id="modalTraVe">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title fw-bold">CVHT — Trả về phiếu đánh giá</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <textarea id="inputGhiChuTraVe" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-warning" id="btnXacNhanTraVe">Xác nhận</button>
      </div>

    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function updateRowCVHT(id, data) {
    let row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    // Cập nhật cột CVHT & ghi chú CVHT + 2 nút
    row.querySelector('.col-cvht').innerHTML       = data.badge_cvht;
    row.querySelector('.col-ghichu-cvht').innerHTML = data.ghi_chu_co_van;
    row.querySelector('.col-duyet').innerHTML      = data.button_duyet;
    row.querySelector('.col-trave').innerHTML      = data.button_trave;

    attachEventsCVHT();
}

function attachEventsCVHT() {
    // ===== NÚT DUYỆT (CVHT) =====
    document.querySelectorAll('.btn-duyet-cvht').forEach(btn => {
        btn.onclick = function () {
            let id = this.dataset.id;

            fetch('<?= BASE_URL ?>index.php?route=capnhat_trang_thai_cvht', {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=duyet&id=${id}&table=<?= $table_prl ?>`
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') updateRowCVHT(id, res.row);
                else alert(res.msg);
            });
        };
    });
}

// ID hiện tại khi trả về
let currentId = null;
document.getElementById('modalTraVe').addEventListener('show.bs.modal', e => {
    currentId = e.relatedTarget.dataset.id;
});

// ===== XÁC NHẬN TRẢ VỀ (CVHT) =====
document.getElementById('btnXacNhanTraVe').onclick = function () {

    let ghichu = document.getElementById('inputGhiChuTraVe').value.trim();
    if (!ghichu) return alert("Vui lòng nhập lý do!");

    fetch('<?= BASE_URL ?>index.php?route=capnhat_trang_thai_cvht', {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "action=trave&id=" + currentId +
              "&table=<?= $table_prl ?>" +
              "&ghichu=" + encodeURIComponent(ghichu)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            updateRowCVHT(currentId, res.row);
            bootstrap.Modal.getInstance(document.getElementById('modalTraVe')).hide();
            document.getElementById('inputGhiChuTraVe').value = "";
        } else {
            alert(res.msg);
        }
    });
};

attachEventsCVHT();


function applyFilters() {
    let cvht  = document.getElementById('filterCVHT').value.toLowerCase();
    let hk    = document.getElementById('filterHK').value.toLowerCase();
    let nam   = document.getElementById('filterNam').value.toLowerCase();
    let text  = document.getElementById('filterText').value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(tr => {
        let colCVHT  = tr.querySelector('.col-cvht')?.innerText.toLowerCase() || "";
        let colHK    = tr.children[3]?.innerText.toLowerCase() || "";
        let colNam   = tr.children[4]?.innerText.toLowerCase() || "";
        let colText  = tr.innerText.toLowerCase();

        let match =
            (cvht === "" || colCVHT.includes(cvht)) &&
            (hk   === "" || colHK.includes(hk)) &&
            (nam  === "" || colNam.includes(nam)) &&
            (text === "" || colText.includes(text));

        tr.style.display = match ? "" : "none";
    });
}

document.querySelectorAll('#filterCVHT, #filterHK, #filterNam, #filterText')
        .forEach(el => el.addEventListener('input', applyFilters));

</script>

</body>
</html>
