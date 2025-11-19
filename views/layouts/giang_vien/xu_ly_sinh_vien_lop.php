<?php
require_once __DIR__ . '/../../../config/db.php';
$conn = Database::connect();
$ma_lop = $_GET['ma_lop'] ?? '';

$sql = "SELECT ma_sv, ho_ten, gioi_tinh, ngay_sinh, email, sdt 
        FROM tb_sinhvien 
        WHERE ma_lop = :malop";
$stmt = $conn->prepare($sql);
$stmt->execute(['malop' => $ma_lop]);
$ds_sv = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Mã SV</th>
      <th>Họ tên</th>
      <th>Giới tính</th>
      <th>Ngày sinh</th>
      <th>Email</th>
      <th>SĐT</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($ds_sv): ?>
      <?php foreach ($ds_sv as $i => $sv): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($sv['ma_sv']) ?></td>
          <td><?= htmlspecialchars($sv['ho_ten']) ?></td>
          <td><?= htmlspecialchars($sv['gioi_tinh']) ?></td>
          <td><?= date('d/m/Y', strtotime($sv['ngay_sinh'])) ?></td>
          <td><?= htmlspecialchars($sv['email']) ?></td>
          <td><?= htmlspecialchars($sv['sdt']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center text-muted">Không có sinh viên nào</td></tr>
    <?php endif; ?>
  </tbody>
</table>
