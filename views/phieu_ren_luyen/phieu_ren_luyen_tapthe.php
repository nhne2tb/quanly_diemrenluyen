<?php
// views/phieu_ren_luyen/phieu_ren_luyen_tapthe.php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Phiếu đánh giá rèn luyện – Tập thể lớp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    font-family: 'Times New Roman', Times, serif;
    background: #eef2f7;
    padding: 30px;
  }

  .container-box {
    background: #fff;
    max-width: 1100px;
    margin: auto;
    padding: 35px 45px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  }

  h3, h5 {
    text-align: center;
    color: #003366;
    font-weight: 800;
  }

  h3 {
    text-transform: uppercase;
    margin-bottom: 1rem;
  }

  h5 {
    margin-bottom: 1.5rem;
  }

  .table thead th {
    background: linear-gradient(180deg, #e9f0ff 0%, #dce8ff 100%);
    color: #003366;
    font-weight: 600;
    text-align: center;
  }

  .table-bordered th, .table-bordered td {
    border-color: #bbb;
    vertical-align: middle;
    font-size: 15px;
    padding: 8px 6px;
  }

  .section-header {
    background: #f1f6ff;
    color: #003366;
    font-weight: bold;
  }

  select.form-select-sm {
    font-size: 14px;
    padding: 2px 6px;
    height: auto;
    max-width: 100px;
    margin: auto;
  }

  .save-btn {
    background: linear-gradient(135deg, #003366, #0055aa);
    border: none;
    font-weight: 600;
  }

  .save-btn:hover { opacity: 0.9; }

  @media print {
    .save-btn { display: none; }
    .container-box { box-shadow: none; padding: 0; }
  }
</style>
</head>
<body>

<div class="container-box">
  <h3>PHIẾU ĐÁNH GIÁ KẾT QUẢ RÈN LUYỆN – TẬP THỂ LỚP</h3>
  <h5>(Dành cho lớp trưởng, Bí thư chi đoàn đánh giá tổng hợp sinh viên lớp)</h5>

  <!-- Thông tin lớp -->
  <div class="mb-4">
    <div class="row mb-2">
      <div class="col-md-6"><strong>Lớp:</strong> ................................................</div>
      <div class="col-md-6"><strong>Ngành:</strong> ................................................</div>
    </div>
    <div class="row mb-2">
      <div class="col-md-6"><strong>Niên khóa:</strong> ....................................</div>
      <div class="col-md-6"><strong>Cố vấn học tập:</strong> ....................................</div>
    </div>
    <div class="row">
      <div class="col-md-6"><strong>Học kỳ:</strong> ............. / Năm học: .............</div>
      <div class="col-md-6"><strong>Người tổng hợp:</strong> ...................................</div>
    </div>
  </div>

  <!-- Bảng danh sách sinh viên -->
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>STT</th>
          <th>MSSV</th>
          <th>Họ và tên</th>
          <th>Điểm tự đánh giá</th>
          <th>Điểm tập thể lớp đánh giá</th>
          <th>Ghi chú</th>
        </tr>
      </thead>
      <tbody>
        <?php for ($i = 1; $i <= 10; $i++): ?>
        <tr>
          <td class="text-center"><?= $i ?></td>
          <td><input type="text" class="form-control form-control-sm" placeholder="MSSV"></td>
          <td><input type="text" class="form-control form-control-sm" placeholder="Họ tên sinh viên"></td>
          <td class="text-center"><input type="number" min="0" max="100" class="form-control form-control-sm text-center score-input"></td>
          <td class="text-center"><input type="number" min="0" max="100" class="form-control form-control-sm text-center score-input"></td>
          <td><input type="text" class="form-control form-control-sm" placeholder="Ghi chú..."></td>
        </tr>
        <?php endfor; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-end fw-bold">Trung bình cộng của lớp</td>
          <td colspan="2" class="text-center">
            <input type="number" id="avgScore" class="form-control form-control-sm text-center fw-bold" readonly>
          </td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="mt-4 note">
    <strong>Ghi chú:</strong><br>
    – Điểm tập thể lớp đánh giá là kết quả thống nhất trong tập thể lớp sau khi họp lớp.<br>
    – Lớp trưởng và Bí thư chịu trách nhiệm về tính trung thực của điểm đánh giá.<br>
    – Bảng này nộp cho cố vấn học tập để xác nhận và gửi về Khoa.
  </div>

  <div class="text-end mt-4">
    <button class="btn btn-primary save-btn px-4">💾 Lưu bảng đánh giá</button>
  </div>
</div>

<script>
// ✅ Tính trung bình cộng điểm tập thể
function calcAverage() {
  let total = 0, count = 0;
  document.querySelectorAll('tbody tr').forEach(tr => {
    const td = tr.querySelectorAll('td input[type="number"]')[1];
    if (td && td.value) {
      total += parseFloat(td.value);
      count++;
    }
  });
  const avg = count ? (total / count).toFixed(2) : 0;
  document.getElementById('avgScore').value = avg;
}
document.querySelectorAll('.score-input').forEach(el => el.addEventListener('change', calcAverage));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
