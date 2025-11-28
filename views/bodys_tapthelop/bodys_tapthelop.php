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
$ma_lop  = strtolower($_SESSION['user']['id'] ?? '');
$ma_khoa = strtolower($_SESSION['user']['khoa'] ?? '');

if (!$ma_lop || !$ma_khoa) {
    $_SESSION['login_error'] = "Phiên đăng nhập không hợp lệ. Hãy đăng nhập lại.";
    header('Location: ' . BASE_URL . 'index.php?route=bodys_login');
    exit;
}

$conn = Database::connect();

$table_prl = "phieu_ren_luyen_{$ma_khoa}_{$ma_lop}";
$table_sv  = "sv_{$ma_khoa}_{$ma_lop}";

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
    <title>Phiếu rèn luyện lớp <?= strtoupper($ma_lop) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
.text-primary-title {
    color: #004aad !important;
}


        
        .table thead th { background:#004aad;color:white; }
        .btn-compact { padding:2px 8px; font-size:12px; border-radius:4px; }


        .btn-logout {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #004aad !important;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
    border: none;
}

.btn-logout:hover {
    background-color: #003984;
    color: #fff !important;
}

    </style>
</head>

<body class="bg-light">

<div class="container mt-4">

<h3 class="text-center fw-bold mb-3 text-primary-title">
    Quản Lý Phiếu Rèn Luyện — Lớp: <?= strtoupper($ma_lop) ?>
</h3>


<a href="<?= BASE_URL ?>index.php?route=logout" class="btn-logout">
    <i class="bi bi-box-arrow-right"></i> Đăng xuất
</a>
<br>
<!-- ===== BỘ LỌC ===== -->
<div class="card p-3 mb-3">
  <div class="row g-3">

    <!-- Lọc theo trạng thái lớp -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Trạng thái lớp</label>
        <select id="filterLop" class="form-select form-select-sm">
            <option value="">Tất cả</option>
            <option value="Đã duyệt">Đã duyệt</option>
            <option value="Trả về">Trả về</option>
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

    <div class="card shadow">
        <div class="card-header fw-bold bg-white">
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
    <th class="col-lop-header">Lớp</th>
    <th class="col-ghichu-lop-header">Ghi chú lớp</th>
    <th>Ghi chú CVHT</th>
    <th class="col-duyet-header">Duyệt</th>
    <th class="col-trave-header">Từ chối</th>
    <th>Xem</th>
</tr>
</thead>

<tbody>
<?php 

if (empty($dsPhieu)): ?>
    <tr><td colspan="13" class="text-center text-muted py-3">Không có dữ liệu.</td></tr>

<?php else:
$stt = 1;

foreach ($dsPhieu as $row): ?>

<tr data-id="<?= $row['id'] ?>">
    <td class="text-center"><?= $stt++ ?></td>
    <td class="text-center"><?= $row['mssv'] ?></td>
    <td><?= htmlspecialchars($row['ho_ten']) ?></td>

    <td class="text-center"><?= $row['hoc_ky'] ?: '—' ?></td>
    <td class="text-center"><?= ($row['nam_bd'] && $row['nam_kt']) ? ($row['nam_bd'].'–'.$row['nam_kt']) : '—' ?></td>

    <td class="text-center fw-bold text-primary">
        <?= $row['tong_diem'] ?: '—' ?>
    </td>

    <!-- CVHT -->
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

    <!-- Lớp -->
    <td class="text-center col-lop">
        <?php if (!$row['id']): ?>
            <span class="badge bg-secondary">Chưa duyệt</span>
        <?php elseif ($row['trang_thai_lop'] === 'Đã duyệt'): ?>
            <span class="badge bg-success">Đã duyệt</span>
        <?php elseif ($row['trang_thai_lop'] === 'Trả về'): ?>
            <span class="badge bg-warning text-dark">Trả về</span>
        <?php else: ?>
            <span class="badge bg-secondary">Chưa duyệt</span>
        <?php endif; ?>
    </td>

    <!-- Ghi chú lớp -->
    <td class="text-center col-ghichu-lop"><?= $row['ghi_chu_lop'] ?: '—' ?></td>

    <!-- Ghi chú CVHT -->
    <td class="text-center"><?= $row['ghi_chu_co_van'] ?: '—' ?></td>

    <!-- Duyệt -->
    <td class="text-center col-duyet">
        <?php if ($row['id']): ?>
            <button class="btn btn-success btn-compact btn-duyet"
                    data-id="<?= $row['id'] ?>"
                    data-table="<?= $table_prl ?>">
                Duyệt
            </button>
        <?php else: ?>
            <span class="text-muted">—</span>
        <?php endif; ?>
    </td>

    <!-- Trả về -->
    <td class="text-center col-trave">
        <?php if ($row['id']): ?>
            <button class="btn btn-warning btn-compact btn-trave"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTraVe"
                    data-id="<?= $row['id'] ?>">
                Trả về
            </button>
        <?php else: ?>
            <span class="text-muted">—</span>
        <?php endif; ?>
    </td>

    <td class="text-center">
        <?php if ($row['id']): ?>
            <a href="<?= BASE_URL ?>index.php?route=xem_phieu_ren_luyen_sv&id=<?= $row['id'] ?>&table=<?= $table_prl ?>"
               class="btn btn-outline-primary btn-compact">Xem</a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>

</tr>

<?php endforeach; endif; ?>
</tbody>
</table>
</div></div></div>


<!-- ================== MODAL TRẢ VỀ ================== -->
<div class="modal fade" id="modalTraVe" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title fw-bold">Trả về phiếu rèn luyện</h5>
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


<script>

// ========== UPDATE 1 DÒNG ==========
function updateRow(id, data) {

    let row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    row.querySelector('.col-lop').innerHTML = data.badge_lop;
    row.querySelector('.col-ghichu-lop').innerHTML = data.ghi_chu_lop;
    row.querySelector('.col-duyet').innerHTML = data.button_duyet;
    row.querySelector('.col-trave').innerHTML = data.button_trave;

    attachEvents();
}


// ========== GẮN LẠI EVENT ==========
function attachEvents() {

document.querySelectorAll('.btn-duyet').forEach(btn => {
    btn.onclick = function() {

        let id = this.dataset.id;

        fetch('<?= BASE_URL ?>index.php?route=capnhat_trang_thai_lop', {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=duyet&id=${id}&table=<?= $table_prl ?>`
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                updateRow(id, res.row);
            } else {
                alert(res.msg);
            }
        })
        .catch(() => alert("Không thể kết nối server!"));
    };
});
}

let currentId = null;

// ========== LẤY ID TRẢ VỀ ==========
document.getElementById('modalTraVe').addEventListener('show.bs.modal', e => {
    currentId = e.relatedTarget.dataset.id;
});

// ========== XÁC NHẬN TRẢ VỀ ==========
document.getElementById('btnXacNhanTraVe').onclick = function() {

    let ghichu = document.getElementById('inputGhiChuTraVe').value.trim();
    if (!ghichu) return alert("Vui lòng nhập ghi chú!");

    fetch('<?= BASE_URL ?>index.php?route=capnhat_trang_thai_lop', {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
body: "action=trave&id=" + currentId +
      "&table=<?= $table_prl ?>" +
      "&ghichu=" + encodeURIComponent(ghichu)
    })
    .then(r => r.json())
    .then(res => {

        if (res.status === 'success') {

            updateRow(currentId, res.row);

            bootstrap.Modal.getInstance(document.getElementById('modalTraVe')).hide();
            document.getElementById('inputGhiChiTraVe').value = "";
        } else {
            alert(res.msg);
        }
    })
    // .catch(() => alert("Không thể kết nối server!"));
};

attachEvents();


function applyFilters() {

    let lop  = document.getElementById('filterLop').value.toLowerCase();
    let hk   = document.getElementById('filterHK').value.toLowerCase();
    let nam  = document.getElementById('filterNam').value.toLowerCase();
    let text = document.getElementById('filterText').value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(tr => {

        let colLop  = tr.querySelector('.col-lop')?.innerText.trim().toLowerCase() || "";
        let colHK   = tr.children[3]?.innerText.trim().toLowerCase() || "";
        let colNam  = tr.children[4]?.innerText.trim().toLowerCase() || "";
        let colText = tr.innerText.toLowerCase();

        // ⭐ KHÔNG DÙNG includes() cho Học kỳ vì gây trùng I / II
        let match =
            (lop === "" || colLop === lop) &&
            (hk  === "" || colHK === hk) &&
            (nam === "" || colNam === nam) &&
            (text === "" || colText.includes(text));

        tr.style.display = match ? "" : "none";
    });
}



document.querySelectorAll('#filterLop, #filterHK, #filterNam, #filterText')
        .forEach(el => el.addEventListener('input', applyFilters));

</script>



</body>
</html>
