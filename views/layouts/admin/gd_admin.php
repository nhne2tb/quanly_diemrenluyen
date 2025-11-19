<?php
// views/layouts/admin/gd_admin.php
// ===============================================
// ADMIN QUẢN TRỊ CSDL - QLDRL
// ===============================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../config/db.php';
$conn = Database::connect();  // ✅ PDO

// ===============================================
// XỬ LÝ CRUD
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';

    // ===== Thêm bản ghi =====
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $fields = [];
        $placeholders = [];
        $values = [];
        foreach ($_POST as $key => $val) {
            if (in_array($key, ['action','table'])) continue;
            $fields[] = "`$key`";
            $placeholders[] = "?";
            $values[] = $val;
        }

        $sql = "INSERT INTO `$table` (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);

        // ✅ Nếu thêm lớp → tạo bảng sinh viên tương ứng
        if ($table === 'tb_lop') {
            $maLopIndex = array_search('`ma_lop`', $fields);
            if ($maLopIndex !== false) {
                $maLopValue = $values[$maLopIndex];
                $svTableName = "tb_sv_" . $maLopValue;

                $createSQL = "
                    CREATE TABLE IF NOT EXISTS `$svTableName` (
                        mssv VARCHAR(15) PRIMARY KEY,
                        ho_ten VARCHAR(100) NOT NULL,
                        gioi_tinh ENUM('Nam','Nữ','Khác') NOT NULL,
                        ngay_sinh DATE NOT NULL,
                        noi_sinh VARCHAR(100),
                        sdt VARCHAR(15),
                        email VARCHAR(100),
                        avatar VARCHAR(255),
                        trang_thai VARCHAR(50) DEFAULT 'Đang học',
                        ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ";
                $conn->exec($createSQL);
            }
        }
    }

    // ===== Sửa bản ghi =====
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $idField = $_POST['id_field'];
        $idValue = $_POST['id_value'];

        $updates = [];
        $values = [];
        foreach ($_POST as $key => $val) {
            if (in_array($key, ['action','table','id_field','id_value'])) continue;
            $updates[] = "`$key` = ?";
            $values[] = $val;
        }
        $values[] = $idValue;

        $sql = "UPDATE `$table` SET " . implode(',', $updates) . " WHERE `$idField` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
    }

    // ===== Xóa bản ghi =====
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $idField = $_POST['id_field'];
        $idValue = $_POST['id_value'];
        $sql = "DELETE FROM `$table` WHERE `$idField` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idValue]);
    }

    header("Location: ".$_SERVER['PHP_SELF']."?table=$table");
    exit;
}

// ===============================================
// LẤY DANH SÁCH BẢNG
// ===============================================
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

// ===============================================
// LẤY DỮ LIỆU BẢNG HIỆN TẠI
// ===============================================
$currentTable = $_GET['table'] ?? ($tables[0] ?? null);
$columns = [];
$rows = [];

if ($currentTable) {
    $colRes = $conn->query("SHOW COLUMNS FROM `$currentTable`");
    $columns = $colRes->fetchAll(PDO::FETCH_ASSOC);

    $dataRes = $conn->query("SELECT * FROM `$currentTable`");
    $rows = $dataRes->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản trị CSDL - QLDRL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f7fa; font-family:'Segoe UI',sans-serif;}
    .sidebar {min-height:100vh;background:#004aad;color:#fff;padding:20px;}
    .sidebar a {color:#fff;text-decoration:none;display:block;padding:8px 12px;border-radius:6px;margin-bottom:4px;}
    .sidebar a.active, .sidebar a:hover {background:#2e8bff;}
    .table-wrapper {background:#fff;padding:15px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,.05);}
    .topbar {background:#fff;padding:12px 20px;box-shadow:0 1px 5px rgba(0,0,0,.08);}
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- SIDEBAR -->
    <div class="col-2 sidebar">
      <h5 class="fw-bold mb-3">📊 Quản trị</h5>
      <?php foreach($tables as $t): ?>
        <a href="?table=<?= $t ?>" class="<?= ($t==$currentTable)?'active':'' ?>"><?= $t ?></a>
      <?php endforeach; ?>
    </div>

    <!-- MAIN -->
    <div class="col-10 p-0">
      <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold"><?= $currentTable ? strtoupper($currentTable) : 'Chọn bảng' ?></h5>
        <?php if($currentTable): ?>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">➕ Thêm bản ghi</button>
        <?php endif; ?>
      </div>

      <div class="p-3">
        <?php if($currentTable): ?>
        <div class="table-wrapper">
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
              <thead class="table-primary">
                <tr>
                  <?php foreach($columns as $c): ?><th><?= $c['Field'] ?></th><?php endforeach; ?>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($rows as $r): ?>
                <tr>
                  <?php foreach($columns as $c): ?>
                    <td><?= htmlspecialchars($r[$c['Field']]) ?></td>
                  <?php endforeach; ?>
                  <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $r[$columns[0]['Field']] ?>">Sửa</button>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="table" value="<?= $currentTable ?>">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id_field" value="<?= $columns[0]['Field'] ?>">
                      <input type="hidden" name="id_value" value="<?= $r[$columns[0]['Field']] ?>">
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa bản ghi này?')">Xóa</button>
                    </form>
                  </td>
                </tr>

                <!-- MODAL SỬA -->
                <div class="modal fade" id="editModal<?= $r[$columns[0]['Field']] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="post" class="modal-content">
                      <div class="modal-header"><h5 class="modal-title">Sửa bản ghi</h5></div>
                      <div class="modal-body">
                        <input type="hidden" name="table" value="<?= $currentTable ?>">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id_field" value="<?= $columns[0]['Field'] ?>">
                        <input type="hidden" name="id_value" value="<?= $r[$columns[0]['Field']] ?>">
                        <?php foreach($columns as $c): ?>
                          <div class="mb-2">
                            <label class="form-label"><?= $c['Field'] ?></label>
                            <input type="text" name="<?= $c['Field'] ?>" class="form-control" value="<?= htmlspecialchars($r[$c['Field']]) ?>" <?= ($c['Field']==$columns[0]['Field'])?'readonly':'' ?>>
                          </div>
                        <?php endforeach; ?>
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
        </div>
        <?php else: ?>
          <p>Vui lòng chọn bảng bên trái để quản lý.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL THÊM -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Thêm bản ghi mới</h5></div>
      <div class="modal-body">
        <input type="hidden" name="table" value="<?= $currentTable ?>">
        <input type="hidden" name="action" value="add">
        <?php foreach($columns as $c): ?>
          <div class="mb-2">
            <label class="form-label"><?= $c['Field'] ?></label>
            <input type="text" name="<?= $c['Field'] ?>" class="form-control">
          </div>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Thêm</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
