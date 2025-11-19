<?php
// ===============================================
// views/layouts/admin/admin_sv/gd_sv.php
// Khoa → Lớp → Sinh viên (có thêm / sửa / xóa SV)
// ===============================================
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

// ================= XỬ LÝ CRUD SINH VIÊN =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action'] ?? '';
    $ma_khoa  = $_POST['ma_khoa'];
    $ma_lop   = $_POST['ma_lop'];
    $table    = "sv_" . strtolower($ma_khoa) . "_" . strtolower($ma_lop);

    // Kiểm tra bảng
    $check = $conn->query("SHOW TABLES LIKE '$table'")->fetch();
    if (!$check) {
        die("Bảng sinh viên không tồn tại.");
    }

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO `$table` (mssv, mat_khau, ho_ten, gioi_tinh, ngay_sinh, email, sdt, dia_chi)
                                VALUES(?,?,?,?,?,?,?,?)");
        $stmt->execute([
            trim($_POST['mssv']),
            password_hash($_POST['mat_khau'], PASSWORD_BCRYPT),
            trim($_POST['ho_ten']),
            $_POST['gioi_tinh'],
            $_POST['ngay_sinh'] ?: null,
            trim($_POST['email']),
            trim($_POST['sdt']),
            trim($_POST['dia_chi']),
        ]);
    }

    if ($action === 'edit') {
        $stmt = $conn->prepare("UPDATE `$table`
                                SET ho_ten=?, gioi_tinh=?, ngay_sinh=?, email=?, sdt=?, dia_chi=?
                                WHERE mssv=?");
        $stmt->execute([
            trim($_POST['ho_ten']),
            $_POST['gioi_tinh'],
            $_POST['ngay_sinh'] ?: null,
            trim($_POST['email']),
            trim($_POST['sdt']),
            trim($_POST['dia_chi']),
            $_POST['mssv']
        ]);
    }

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE mssv=?");
        $stmt->execute([$_POST['mssv']]);
    }

    header("Location: ".$_SERVER['PHP_SELF']."#lop_{$_POST['ma_khoa']}_{$_POST['ma_lop']}");
    exit;
}

// ================= LẤY DANH SÁCH KHOA + LỚP =================
$khoas = $conn->query("SELECT ma_khoa, ten_khoa FROM tb_khoa ORDER BY ten_khoa")->fetchAll(PDO::FETCH_ASSOC);

$lopsByKhoa = [];
foreach ($khoas as $khoa) {
    $ma_khoa = $khoa['ma_khoa'];
    $stmt = $conn->prepare("SELECT * FROM tb_lop WHERE ma_khoa=? ORDER BY ten_lop");
    $stmt->execute([$ma_khoa]);
    $lopsByKhoa[$ma_khoa] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ================= LẤY SINH VIÊN THEO LỚP =================
function getStudentsByClass(PDO $conn, $ma_khoa, $ma_lop){
    $table = "sv_" . strtolower($ma_khoa) . "_" . strtolower($ma_lop);
    $check = $conn->query("SHOW TABLES LIKE '$table'")->fetch();
    if(!$check) return [];
    return $conn->query("SELECT * FROM `$table` ORDER BY ho_ten")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quản lý Sinh viên</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{
  --brand:#0056B3;
  --brand-hover:#004799;
  --bg-light:#f8f9fa;
}
body{background:var(--bg-light);font-family:system-ui,Segoe UI,Roboto,sans-serif;}
.wrap{max-width:1200px;margin:auto;padding:24px}
.accordion-button:not(.collapsed){background:var(--brand);color:#fff;}
.badge-code{background:var(--brand);color:#fff;padding:.35em .6em;border-radius:.4rem;font-weight:600;}
.action-btn{border:none;background:none;padding:0 .3rem;}
</style>
</head>
<body>
<div class="wrap">
  <h3 class="mb-4 text-primary"><i class="bi bi-building me-2"></i>Danh sách Khoa → Lớp → Sinh viên</h3>

  <div class="accordion" id="khoaAccordion">
    <?php foreach($khoas as $ki=>$khoa): ?>
      <div class="accordion-item mb-2">
        <h2 class="accordion-header" id="headingKhoa<?= $ki ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKhoa<?= $ki ?>">
            <?= h($khoa['ten_khoa']) ?> (<?= h($khoa['ma_khoa']) ?>)
          </button>
        </h2>
        <div id="collapseKhoa<?= $ki ?>" class="accordion-collapse collapse" data-bs-parent="#khoaAccordion">
          <div class="accordion-body">

            <?php $lops = $lopsByKhoa[$khoa['ma_khoa']] ?? []; ?>
            <?php if(empty($lops)): ?>
              <p class="text-muted fst-italic">Chưa có lớp nào.</p>
            <?php else: ?>
              <div class="accordion" id="lopAccordion<?= $ki ?>">
                <?php foreach($lops as $li=>$lop): 
                      $students = getStudentsByClass($conn, $lop['ma_khoa'], $lop['ma_lop']);
                ?>
                  <div class="accordion-item mb-2" id="lop_<?= h($lop['ma_khoa']) ?>_<?= h($lop['ma_lop']) ?>">
                    <h2 class="accordion-header" id="headingLop<?= $ki.$li ?>">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLop<?= $ki.$li ?>">
                        <?= h($lop['ten_lop']) ?> (<?= h($lop['ma_lop']) ?>)
                      </button>
                    </h2>
                    <div id="collapseLop<?= $ki.$li ?>" class="accordion-collapse collapse" data-bs-parent="#lopAccordion<?= $ki ?>">
                      <div class="accordion-body">

                        <!-- Nút thêm sinh viên -->
                        <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSVModal<?= $ki.$li ?>">
                          <i class="bi bi-person-plus"></i> Thêm sinh viên
                        </button>

                        <?php if(empty($students)): ?>
                          <p class="text-muted fst-italic">Chưa có sinh viên trong lớp này.</p>
                        <?php else: ?>
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                              <thead>
                                <tr>
                                  <th>MSSV</th>
                                  <th>Họ tên</th>
                                  <th>Giới tính</th>
                                  <th>Email</th>
                                  <th>SĐT</th>
                                  <th class="text-center">Thao tác</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach($students as $sv): ?>
                                  <tr>
                                    <td><span class="badge-code"><?= h($sv['mssv']) ?></span></td>
                                    <td><?= h($sv['ho_ten']) ?></td>
                                    <td><?= h($sv['gioi_tinh']) ?></td>
                                    <td><?= h($sv['email']) ?: '—' ?></td>
                                    <td><?= h($sv['sdt']) ?: '—' ?></td>
                                    <td class="text-center">
                                      <!-- Sửa -->
                                      <button class="action-btn text-primary" data-bs-toggle="modal" data-bs-target="#editSVModal<?= $ki.$li.h($sv['mssv']) ?>">
                                        <i class="bi bi-pencil-square"></i>
                                      </button>
                                      <!-- Xóa -->
                                      <form method="post" class="d-inline" onsubmit="return confirm('Xóa SV này?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="ma_khoa" value="<?= h($lop['ma_khoa']) ?>">
                                        <input type="hidden" name="ma_lop" value="<?= h($lop['ma_lop']) ?>">
                                        <input type="hidden" name="mssv" value="<?= h($sv['mssv']) ?>">
                                        <button type="submit" class="action-btn text-danger"><i class="bi bi-trash"></i></button>
                                      </form>
                                    </td>
                                  </tr>

                                  <!-- Modal sửa SV -->
                                  <div class="modal fade" id="editSVModal<?= $ki.$li.h($sv['mssv']) ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                      <form method="post" class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Sửa SV</h5></div>
                                        <div class="modal-body">
                                          <input type="hidden" name="action" value="edit">
                                          <input type="hidden" name="ma_khoa" value="<?= h($lop['ma_khoa']) ?>">
                                          <input type="hidden" name="ma_lop" value="<?= h($lop['ma_lop']) ?>">
                                          <input type="hidden" name="mssv" value="<?= h($sv['mssv']) ?>">

                                          <div class="mb-2">
                                            <label class="form-label">Họ tên</label>
                                            <input type="text" name="ho_ten" class="form-control" value="<?= h($sv['ho_ten']) ?>">
                                          </div>
                                          <div class="mb-2">
                                            <label class="form-label">Giới tính</label>
                                            <select name="gioi_tinh" class="form-select">
                                              <option <?= $sv['gioi_tinh']=='Nam'?'selected':'' ?>>Nam</option>
                                              <option <?= $sv['gioi_tinh']=='Nữ'?'selected':'' ?>>Nữ</option>
                                              <option <?= $sv['gioi_tinh']=='Khác'?'selected':'' ?>>Khác</option>
                                            </select>
                                          </div>
                                          <div class="mb-2">
                                            <label class="form-label">Ngày sinh</label>
                                            <input type="date" name="ngay_sinh" class="form-control" value="<?= h($sv['ngay_sinh']) ?>">
                                          </div>
                                          <div class="mb-2">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= h($sv['email']) ?>">
                                          </div>
                                          <div class="mb-2">
                                            <label class="form-label">SĐT</label>
                                            <input type="text" name="sdt" class="form-control" value="<?= h($sv['sdt']) ?>">
                                          </div>
                                          <div class="mb-2">
                                            <label class="form-label">Địa chỉ</label>
                                            <input type="text" name="dia_chi" class="form-control" value="<?= h($sv['dia_chi']) ?>">
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="submit" class="btn btn-success">Lưu</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        <?php endif; ?>

                        <!-- Modal thêm SV -->
                        <div class="modal fade" id="addSVModal<?= $ki.$li ?>" tabindex="-1">
                          <div class="modal-dialog">
                            <form method="post" class="modal-content">
                              <div class="modal-header"><h5 class="modal-title">Thêm sinh viên</h5></div>
                              <div class="modal-body">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="ma_khoa" value="<?= h($lop['ma_khoa']) ?>">
                                <input type="hidden" name="ma_lop" value="<?= h($lop['ma_lop']) ?>">

                                <div class="mb-2"><label class="form-label">MSSV</label><input name="mssv" class="form-control" required></div>
                                <div class="mb-2"><label class="form-label">Mật khẩu</label><input name="mat_khau" type="password" class="form-control" required></div>
                                <div class="mb-2"><label class="form-label">Họ tên</label><input name="ho_ten" class="form-control" required></div>
                                <div class="mb-2">
                                  <label class="form-label">Giới tính</label>
                                  <select name="gioi_tinh" class="form-select">
                                    <option>Nam</option>
                                    <option>Nữ</option>
                                    <option>Khác</option>
                                  </select>
                                </div>
                                <div class="mb-2"><label class="form-label">Ngày sinh</label><input name="ngay_sinh" type="date" class="form-control"></div>
                                <div class="mb-2"><label class="form-label">Email</label><input name="email" type="email" class="form-control"></div>
                                <div class="mb-2"><label class="form-label">SĐT</label><input name="sdt" class="form-control"></div>
                                <div class="mb-2"><label class="form-label">Địa chỉ</label><input name="dia_chi" class="form-control"></div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Thêm</button>
                              </div>
                            </form>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
