<?php
// views/phieu_ren_luyen/phieu_ren_luyen_sv.php
  header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Phiếu đánh giá rèn luyện</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    font-family: 'Times New Roman', Times, serif;
    background: #eef2f7;
    padding: 30px;
  }

  .container-box {
    background: #fff;
    max-width: 1000px;
    margin: auto;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  }

  h3 {
    text-align: center;
    font-weight: 800;
    color: #003366;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* ===== Bảng ===== */
  table.table-bordered {
    border: 1px solid #ccc;
  }

  table.table-bordered th, table.table-bordered td {
    vertical-align: middle;
    font-size: 15px;
    border-color: #bbb;
    padding: 8px 6px;
  }

  table.table-bordered th {
    background: linear-gradient(180deg, #e9f0ff 0%, #dce8ff 100%);
    text-align: center;
    font-weight: 600;
    color: #003366;
  }

  table.table-bordered td.text-start {
    text-align: left;
  }

  /* Mục tiêu đề I, II, III... */
  .section-header {
    font-weight: bold;
    background: #f1f6ff;
    color: #003366;
    text-align: left;
    border-top: 2px solid #003366 !important;
    font-size: 15.5px;
  }

  /* STT canh giữa */
  table.table-bordered tr:not(.section-header) td:first-child,
  table.table-bordered th:first-child {
    text-align: center;
    vertical-align: middle;
    width: 45px;
  }

  /* Cột điểm */
  table.table-bordered tr:not(.section-header) td:nth-child(3),
  table.table-bordered tr:not(.section-header) td:nth-child(4),
  table.table-bordered th:nth-child(3),
  table.table-bordered th:nth-child(4) {
    text-align: center;
    vertical-align: middle;
  }

  /* Select nhỏ gọn */
  select.form-select-sm {
    max-width: 90px;
    margin: auto;
    font-size: 14px;
    padding: 2px 4px;
    height: auto;
  }

  input[type="number"], input[type="radio"] {
    cursor: pointer;
  }

  /* Bảng con trong mục Cán bộ lớp */
  .table.table-sm th {
    background: #f8f9fc !important;
    font-size: 14px;
  }

  /* Ghi chú */
  .note p {
    margin-bottom: 5px;
  }

  .note strong {
    color: #003366;
  }

  .save-btn {
    background: linear-gradient(135deg, #003366, #0055aa);
    border: none;
    font-weight: 600;
  }
  .save-btn:hover {
    opacity: 0.9;
  }

  @media print {
    body {
      background: none;
      padding: 0;
    }
    .container-box {
      box-shadow: none;
      padding: 0;
      max-width: 100%;
    }
    .save-btn {
      display: none;
    }
  }
</style>
</head>
<body>

<div class="container-box">
  <h3>PHIẾU ĐÁNH GIÁ KẾT QUẢ RÈN LUYỆN</h3>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="width:50px;">TT</th>
          <th>Nội dung đánh giá</th>
          <th style="width:90px;">Điểm tối đa</th>
          <th style="width:130px;">Sinh viên<br>tự đánh giá</th>
        </tr>
      </thead>
      <tbody>

        <!-- I -->
<tr class="section-header">
  <td colspan="4">
    <strong>I. Đánh giá về ý thức tham gia học tập</strong>
    <span style="font-style: italic;">(Điểm tối đa 20 điểm)</span>
  </td>
</tr>
        <tr>
          <td>1</td>
          <td class="text-start">
            Đi học đầy đủ, đúng giờ, chuẩn bị bài đầy đủ, tích cực phát biểu trong giờ học<br>
            <i>(đi học muộn, nghỉ học không lý do, bỏ giờ: trừ 1 điểm/1 lần)</i>
          </td>
          <td>05</td>
          <td>
            <select class="form-select form-select-sm score-input">
              <?php for($i=0;$i<=5;$i++): ?><option><?=$i?></option><?php endfor;?>
            </select>
          </td>
        </tr>
<tr>
  <td>2</td>
  <td colspan="3" class="text-start">
    Ý thức, thái độ tham gia
  </td>
</tr>

<?php
$subs = [
  'Các hoạt động học thuật (Câu lạc bộ)',
  'Hoạt động ngoại khóa',
  'Rèn luyện kỹ năng mềm',
  'Hoạt động nghiên cứu khoa học',
  'Các cuộc thi do Nhà trường, Đoàn Thanh niên, Hội Sinh viên phát động'
];

foreach ($subs as $index => $s): ?>
<tr>
  <td></td>
  <td class="text-start"><i>– <?= $s ?></i></td>
  <td>01</td>
  <td class="text-center">
    <input 
      type="checkbox" 
      name="subs[]" 
      value="1" 
      id="sub_<?= $index ?>"
      class="score-input"
      style="width: 18px; height: 18px; cursor: pointer;"
    >
  </td>
</tr>
<?php endforeach; ?>

<tr>
  <td>3</td>
  <td class="text-start">Tinh thần vượt khó, phấn đấu vươn lên trong học tập</td>
  <td>02</td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=2;$i++): ?><option><?=$i?></option><?php endfor;?>
    </select>
  </td>
</tr>

<tr>
  <td>4</td>
  <td class="text-start">Tích cực đánh giá hoạt động giảng dạy của giảng viên, khóa đào tạo</td>
  <td>02</td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=2;$i++): ?><option><?=$i?></option><?php endfor;?>
    </select>
  </td>
</tr>

<tr>
  <td>5</td>
  <td colspan="3" class="text-start">
    Kết quả học tập
  </td>
</tr>

<?php
$gpa = [
  'Điểm TBCHT: 2,00 đến 2,49' => 3,
  'Điểm TBCHT: 2,50 đến 3,19' => 4,
  'Điểm TBCHT: 3,20 đến 3,59' => 5,
  'Điểm TBCHT: 3,60 đến 4,00' => 6
];
$radioName = 'diem_tbc';
foreach ($gpa as $label => $max): ?>
<tr>
  <td></td>
  <td class="text-start"><i>– <?= $label ?></i></td>
  <td><?= $max ?></td>
  <td class="text-center">
    <input type="radio" name="<?= $radioName ?>" value="<?= $max ?>" class="score-input">
  </td>
</tr>
<?php endforeach; ?>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4" class="text-start">
    <strong>* Điểm thưởng 
      <span style="font-style: italic; font-weight: bold;">
        (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
      </span>
    </strong><br>
    <div style="margin-left: 20px;">
      Được các cấp khen thưởng khi tham gia các hoạt động trên, điểm thưởng như sau:
      <div style="display: flex; justify-content: flex-start; gap: 40px; margin-top: 5px; margin-left: 20px;">
        <div>– Cấp khoa: 01 điểm</div>
        <div>– Cấp trường: 02 điểm</div>
        <div>– Cấp tỉnh hoặc tương đương: 03 điểm</div>
      </div>
    </div>
  </td>
</tr>

<!-- II -->
<tr class="section-header">
  <td colspan="4">
    II. Đánh giá về ý thức chấp hành nội quy, quy chế, quy định trong Nhà trường 
    <span style="font-style: italic;">(Điểm tối đa 25 điểm)</span>
  </td>
</tr>

<?php
$rows = [
  ['Thực hiện tốt nội quy lớp học, quy định của Nhà trường', 5],
  ['Thực hiện tốt Quy chế học sinh, sinh viên; Quy định của Nhà trường đối với sinh viên ngoại trú', 10],
  ['Tham gia đầy đủ bảo hiểm y tế, bảo hiểm tai nạn <i>(05 điểm/1 loại hình bảo hiểm)</i>', 10]
];
foreach ($rows as $i=>$r): ?>
<tr>
  <td><?=$i+1?></td><td class="text-start"><?=$r[0]?></td><td><?=$r[1]?></td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($x=0;$x<=$r[1];$x+=($r[1]==10?5:1)): ?><option><?=$x?></option><?php endfor;?>
    </select>
  </td>
</tr>
<?php endforeach; ?>

<!-- III -->
<tr class="section-header">
  <td colspan="4">
    III. Đánh giá về ý thức và kết quả tham gia các hoạt động chính trị, xã hội, 
    văn hoá, văn nghệ, thể thao, phòng chống tội phạm và các tệ nạn xã hội 
    <span style="font-style: italic;">(Điểm tối đa 20 điểm)</span>
  </td>
</tr>

<tr>
  <td>1</td>
  <td class="text-start">Tham gia nhiệt tình, đầy đủ và có ý thức các hoạt động tập trung do Nhà trường, Khoa, Đoàn Than niên, Hội Sinh viên tổ chức. <i>(Trừ 05 điểm/1 lần vắng mặt)</i></td>
  <td>10</td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=10;$i+=5): ?><option><?=$i?></option><?php endfor;?>
    </select>
  </td>
</tr>
<tr>
  <td>2</td>
  <td class="text-start">Có ý thức và tích cực tham gia tuyên truyền công tác phòng chống tệ nạn xã hội, hoạt động công ích, tình nguyện, từ thiện, nhân đạo, công tác xã hội. </td>
  <td>05</td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=5;$i++): ?><option><?=$i?></option><?php endfor;?>
    </select>
  </td>
</tr>

<!-- Mục 3 - Kết quả phân loại đoàn viên -->
<tr>
  <td>3</td>
  <td colspan="3" class="text-start">
    Kết quả phân loại đoàn viên:
  </td>
</tr>

<?php
$gpa = [
  'Khá' => 3,
  'Xuất sắc' => 5
];
foreach ($gpa as $label => $max): ?>
<tr>
  <td></td>
  <td class="text-start"><i>– <?= $label ?></i></td>
  <td><?= str_pad($max, 2, '0', STR_PAD_LEFT) ?></td>
  <td>
    <select class="form-select form-select-sm score-input" name="phan_loai_<?= strtolower(str_replace(' ', '_', $label)) ?>">
      <?php for($i=0; $i<=$max; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>
<?php endforeach; ?>

<!-- Điểm thưởng -->
<tr>
  <td colspan="4" class="text-start">
    <strong>* Điểm thưởng 
      <span style="font-style: italic; font-weight: bold;">
        (được cộng nhưng tổng số điểm của tiêu chí này không vượt quá 20 điểm)
      </span>
    </strong><br>
    <div style="margin-left: 20px; margin-top: 5px;">
      Đạt danh hiệu “Sinh viên 5 tốt” hoặc đạt thành tích cao và được khen thưởng 
      trong các cuộc thi về văn nghệ, thể thao. 
      <span style="font-style: italic;">
        (Nếu tập thể lớp đạt thành tích thì tất cả các thành viên trong lớp đều được hưởng số điểm)
      </span>
    </div>
    <div style="display: flex; justify-content: flex-start; gap: 35px; margin-left: 35px; margin-top: 5px;">
      <div>– Cấp khoa: 01 điểm</div>
      <div>– Cấp trường: 02 điểm</div>
      <div>– Cấp tỉnh hoặc tương đương: 03 điểm</div>
    </div>
  </td>
</tr>

<!-- IV -->
<tr class="section-header">
  <td colspan="4">
    IV. Đánh giá về ý thức công dân trong quan hệ cộng đồng 
    <span style="font-style: italic;">(Điểm tối đa 25 điểm)</span>
  </td>
</tr>

<?php
$iv = [
  'Chấp hành tốt và tích cực tham gia tuyên truyền các chủ trương của Đảng, chính sách và pháp luật của nhà nước trong cộng đồng.',
  'Tham gia đầy đủ các buổi học tập, tìm hiểu pháp luật do Nhà trường tổ chức',
  'Tích cực tham gia các hoạt động xã hội do Nhà trường hoặc địa phương nơi cư trú tổ chức',
  'Có mối quan hệ tốt trong tập thể, không gây mất đoàn kết, bản thân có tác dụng tích cực đối với tập thể',
  'Thực hiện tốt tinh thần tương thân, tương ái trong cuộc sống.'
];
foreach ($iv as $i=>$t): ?>
<tr>
  <td><?=$i+1?></td><td class="text-start"><?=$t?></td><td>05</td>
  <td>
    <select class="form-select form-select-sm score-input">
      <?php for($j=0;$j<=5;$j++): ?><option><?=$j?></option><?php endfor;?>
    </select>
  </td>
</tr>
<?php endforeach; ?>

<!-- V -->
<tr class="section-header">
  <td colspan="4">
    V. Đánh giá về ý thức và kết quả khi tham gia công tác cán bộ lớp,
    các đoàn thể, tổ chức trong trường hoặc người học đạt được thành tích
    đặc biệt trong học tập, rèn luyện
    <span style="font-style: italic;">(Điểm tối đa 10 điểm)</span>
  </td>
</tr>

<!-- Mục 1 -->
<tr>
  <td>1</td>
  <td class="text-start">Không là cán bộ lớp, Đoàn, Hội nhưng thực hiện tốt nhiệm vụ</td>
  <td>04</td>
  <td>
    <select id="opt1" class="form-select form-select-sm score-input">
      <?php for($i=0;$i<=4;$i++): ?><option><?=$i?></option><?php endfor;?>
    </select>
  </td>
</tr>

<!-- Mục 2 -->
<tr>
  <td>2</td>
  <td class="text-start">
    <label class="form-check-label" for="opt2">
      Là cán bộ lớp, cán bộ Đoàn, Hội nhưng không thực hiện tốt nhiệm vụ được giao, không gương mẫu trước tập thể
    </label>
  </td>
  <td>00</td>
  <td class="text-center">
    <input id="opt2" type="checkbox" class="form-check-input score-input">
    <input id="score2" type="hidden" value="0">
  </td>
</tr>

<!-- Mục 3 -->
<tr>
  <td>3</td>
  <td colspan="2" class="text-start">
    Nếu là cán bộ lớp, cán bộ Đoàn, Hội thì căn cứ vào kết quả thi đua của tập thể lớp quy định như sau:
    <div class="table-responsive mt-2" style="margin-left:15px;max-width:500px;">
      <table class="table table-bordered table-sm align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th style="width:130px;">Chức vụ</th>
            <th style="width:80px;">Xếp loại XS</th>
            <th style="width:80px;">Tốt</th>
            <th style="width:80px;">Khá</th>
            <th style="width:80px;">TBK</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr><td class="text-start">Cấp trưởng</td><td>10</td><td>9</td><td>8</td><td>6</td></tr>
          <tr><td class="text-start">Cấp phó</td><td>8</td><td>7</td><td>6</td><td>4</td></tr>
        </tbody>
      </table>
    </div>
  </td>
<td class="text-center">
  <select id="opt3" class="form-select form-select-sm text-center score-input">
    <option value="">0</option>
    <option value="4">4</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
  </select>
</td>

</tr>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const opt1 = document.getElementById('opt1');
  const opt2 = document.getElementById('opt2');
  const opt3 = document.getElementById('opt3');

  function lockOthers(selected) {
    // Bỏ khóa hết
    opt1.disabled = opt2.disabled = opt3.disabled = false;

    // Nếu chọn 1 → khóa 2,3
    if (selected === 'opt1' && opt1.value > 0) {
      opt2.disabled = true;
      opt3.disabled = true;
      opt2.checked = false;
      opt3.value = '';
    }

    // Nếu chọn 2 → khóa 1,3
    if (selected === 'opt2' && opt2.checked) {
      opt1.disabled = true;
      opt3.disabled = true;
      opt1.value = 0;
      opt3.value = '';
    }

    // Nếu nhập 3 → khóa 1,2
    if (selected === 'opt3' && opt3.value !== '') {
      opt1.disabled = true;
      opt2.disabled = true;
      opt2.checked = false;
      opt1.value = 0;
    }

    // Nếu bỏ chọn hết → mở lại
    if (
      (!opt2.checked) &&
      (opt1.value == 0) &&
      (opt3.value === '')
    ) {
      opt1.disabled = opt2.disabled = opt3.disabled = false;
    }
  }

  opt1.addEventListener('change', () => lockOthers('opt1'));
  opt2.addEventListener('change', () => lockOthers('opt2'));
  opt3.addEventListener('input', () => lockOthers('opt3'));
});
</script>


<!-- Tổng -->
<tr>
  <td colspan="2" class="text-end fw-bold">Tổng cộng</td>
  <td class="fw-bold text-center">100</td>
  <td>
    <input type="number" id="totalScore" class="form-control form-control-sm text-center fw-bold" readonly>
  </td>
</tr>

      </tbody>
    </table>
  </div>

  <!-- Ghi chú -->
  <div class="mt">
    <p><strong>* Ghi chú:</strong></p>
    <p style="font-style: italic; margin-left: 20px;">
      1. Nếu sinh viên vi phạm quy chế thi, kết quả đánh giá rèn luyện trong học kỳ không vượt quá loại Trung bình.<br>
      2. Nếu sinh viên vi phạm quy chế học sinh, sinh viên bị xử lý từ cảnh cáo trở lên khi đánh giá kết quả rèn luyện không được vượt quá loại Trung bình.
    </p>

  <div class="text-end mt">
    <button class="btn btn-primary save-btn px-4">💾 Lưu đánh giá</button>
  </div>
</div>

<script>
// ✅ Script tự tính tổng điểm
function calculateTotal() {
  let total = 0;

  document.querySelectorAll('.score-input').forEach(el => {
    if (el.tagName === 'SELECT') {
      total += parseFloat(el.value) || 0;
    } else if (el.type === 'radio' && el.checked) {
      total += parseFloat(el.value) || 0;
    } else if (el.type === 'checkbox' && el.checked) {
      total += parseFloat(el.value) || 0;
    } else if (el.type === 'number') {
      total += parseFloat(el.value) || 0;
    }
  });

  if (total > 100) total = 100;
  document.getElementById('totalScore').value = total;
}

// Lắng nghe sự kiện
document.querySelectorAll('.score-input').forEach(el => {
  el.addEventListener('change', calculateTotal);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
