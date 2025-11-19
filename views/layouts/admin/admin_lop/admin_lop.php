<?php
require_once __DIR__ . '/../../../../config/db.php';
$conn = Database::connect();

$sql = "SELECT l.*, g.ho_ten AS ten_gv, k.ten_khoa 
        FROM tb_lop l
        LEFT JOIN tb_giangvien g ON l.ma_gv = g.ma_gv
        LEFT JOIN tb_khoa k ON l.ma_khoa = k.ma_khoa
        ORDER BY l.ma_lop ASC";
$lops = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$total = count($lops);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quản lý danh sách lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:#f8f9fa;font-family:Segoe UI,Roboto,sans-serif;}
.wrap{max-width:1200px;margin:auto;padding:24px}
.card{border:none;border-radius:1rem;box-shadow:0 5px 20px rgba(0,0,0,.05);}
.badge-code{background:#004aad;color:#fff;padding:.4em .6em;border-radius:.4rem;font-weight:600;}
.action-group{display:flex;justify-content:center;gap:.4rem}
.action-btn{display:inline-flex;align-items:center;gap:.3rem;font-size:.85rem;font-weight:500;padding:.35rem .7rem;border-radius:50rem;border:1px solid transparent;transition:.2s}
.action-btn.detail{color:#004aad;border-color:rgba(0,74,173,0.3);}
.action-btn.detail:hover{background:#004aad;color:#fff;}
.action-btn.edit{color:#0d6efd;border-color:rgba(13,110,253,0.3);}
.action-btn.edit:hover{background:#0d6efd;color:#fff;}
.action-btn.delete{color:#dc3545;border-color:rgba(220,53,69,0.3);}
.action-btn.delete:hover{background:#dc3545;color:#fff;}
.action-btn.student{color:#198754;border-color:rgba(25,135,84,0.3);}
.action-btn.student:hover{background:#198754;color:#fff;}
.action-btn.add{color:#ff9800;border-color:rgba(255,152,0,0.3);}
.action-btn.add:hover{background:#ff9800;color:#fff;}
</style>
</head>
<body>
<div class="wrap">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="text-primary fw-bold"><i class="bi bi-collection me-2"></i>Danh sách Lớp (<?= $total ?>)</h4>
      <a href="index.php?route=admin_lop_them" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-lg"></i> Thêm Lớp
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Niên khóa</th>
            <th>Ngành học</th>
            <th>Cố vấn</th>
            <th>Khoa</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($lops)): ?>
            <tr><td colspan="7" class="text-muted py-4">Chưa có dữ liệu lớp.</td></tr>
          <?php else: foreach($lops as $l): ?>
            <tr>
              <td><span class="badge-code"><?= htmlspecialchars($l['ma_lop']) ?></span></td>
              <td class="text-start"><?= htmlspecialchars($l['ten_lop']) ?></td>
              <td><?= htmlspecialchars($l['nien_khoa']) ?></td>
              <td><?= htmlspecialchars($l['nganh_hoc']) ?></td>
              <td><?= htmlspecialchars($l['ten_gv']) ?: '—' ?></td>
              <td><?= htmlspecialchars($l['ten_khoa']) ?></td>
              <td>
                <div class="action-group">
                  <a href="index.php?route=admin_lop_chitiet&ma=<?= urlencode($l['ma_lop']) ?>" class="action-btn detail">
                    <i class="bi bi-eye"></i> Chi tiết
                  </a>
                  <a href="index.php?route=admin_lop_sua&ma=<?= urlencode($l['ma_lop']) ?>" class="action-btn edit">
                    <i class="bi bi-pencil-square"></i> Sửa
                  </a>
                  <a href="index.php?route=admin_lop_xoa&ma=<?= urlencode($l['ma_lop']) ?>" class="action-btn delete" onclick="return confirm('Xóa lớp này và dữ liệu liên quan?')">
                    <i class="bi bi-trash3"></i> Xóa
                  </a>
                  <a href="index.php?route=admin_sinhvien_lop&lop=<?= urlencode($l['ma_lop']) ?>" class="action-btn student">
                    <i class="bi bi-people-fill"></i> Sinh viên
                  </a>
                  <a href="index.php?route=admin_sinhvien_them&lop=<?= urlencode($l['ma_lop']) ?>" class="action-btn add">
                    <i class="bi bi-person-plus-fill"></i> Thêm SV
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
