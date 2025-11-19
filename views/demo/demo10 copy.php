<?php
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
    background: #f5f7fa;
    padding: 25px;
  }
  .container-box {
    background: #fff;
    max-width: 1100px;
    margin: auto;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  h3 {
    text-align: center;
    font-weight: bold;
    color: #003366;
    margin-bottom: 1.2rem;
    text-transform: uppercase;
  }
  table.table-bordered th, table.table-bordered td {
    vertical-align: middle;
    font-size: 15px;
    border-color: #bbb;
    padding: 6px;
  }
  table.table-bordered th {
    background: #e9f0ff;
    text-align: center;
    font-weight: 600;
  }
  table.table-bordered td.text-start {
    text-align: left;
  }
  .section-header {
    font-weight: bold;
    background: #f2f6ff;
    font-style: italic;
    text-align: left;
  }
  .score-input, select.form-select-sm {
    max-width: 80px;
    text-align: center;
    margin: auto;
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


  /* ✅ Canh giữa STT - không áp với dòng tiêu đề I, II, III... */
table.table-bordered tr:not(.section-header) td:first-child,
table.table-bordered th:first-child {
  text-align: center;
  vertical-align: middle;
  width: 50px;
}

/* ✅ Canh giữa các cột điểm - bỏ qua dòng tiêu đề */
table.table-bordered tr:not(.section-header) td:nth-child(3),
table.table-bordered tr:not(.section-header) td:nth-child(4),
table.table-bordered tr:not(.section-header) td:nth-child(5),
table.table-bordered tr:not(.section-header) td:nth-child(6),
table.table-bordered th:nth-child(3),
table.table-bordered th:nth-child(4),
table.table-bordered th:nth-child(5),
table.table-bordered th:nth-child(6) {
  text-align: center;
  vertical-align: middle;
}

/* Giữ dòng I, II, III... nằm bên trái */
.section-header {
  text-align: left !important;
  font-weight: bold;
  background: #f2f6ff;
  font-style: italic;
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
          <th style="width:130px;">Tập thể lớp<br>đánh giá</th>
          <th style="width:130px;">Khoa<br>đánh giá</th>
        </tr>
      </thead>
      <tbody>

        <!-- I -->
        <tr class="section-header"><td colspan="6">I. Đánh giá về ý thức tham gia học tập (20 điểm)</td></tr>
        <tr>
          <td>1</td>
          <td class="text-start">
            Đi học đầy đủ, đúng giờ, chuẩn bị bài đầy đủ, tích cực phát biểu trong giờ học<br>
            <i>(đi học muộn, nghỉ học không lý do, bỏ giờ: trừ 1 điểm/1 lần)</i>
          </td>
          <td>05</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=5;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <tr><td>2</td><td class="text-start">Ý thức, thái độ tham gia</td><td>05</td><td colspan="3"></td></tr>
        <?php
        $subs = ['Các hoạt động học thuật (Câu lạc bộ)','Hoạt động ngoại khóa','Rèn luyện kỹ năng mềm','Hoạt động nghiên cứu khoa học','Các cuộc thi do Nhà trường, Đoàn Thanh niên, Hội Sinh viên phát động'];
        foreach ($subs as $s): ?>
        <tr>
          <td></td><td class="text-start">– <?=$s?></td><td>01</td>
          <td><select class="form-select form-select-sm"><option>0</option><option>1</option></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td>3</td>
          <td class="text-start">Tinh thần vượt khó, phấn đấu vươn lên trong học tập</td>
          <td>02</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=2;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <tr>
          <td>4</td>
          <td class="text-start">Tích cực đánh giá hoạt động giảng dạy của giảng viên, khóa đào tạo</td>
          <td>02</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=2;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <?php
        $gpa = ['Điểm TBC: 2,00–2,49'=>3,'Điểm TBC: 2,50–3,19'=>4,'Điểm TBC: 3,20–3,59'=>5,'Điểm TBC: 3,60–4,00'=>6];
        foreach ($gpa as $label=>$max): ?>
        <tr>
          <td></td><td class="text-start">– <?=$label?></td><td><?=$max?></td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=$max;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <?php endforeach; ?>

        <!-- II -->
        <tr class="section-header"><td colspan="6">II. Đánh giá về ý thức chấp hành nội quy, quy chế, quy định trong Nhà trường (25 điểm)</td></tr>
        <?php
        $rows = [
          ['Thực hiện tốt nội quy lớp học, quy định của Nhà trường', 5],
          ['Thực hiện tốt Quy chế học sinh, sinh viên; Quy định của Nhà trường đối với sinh viên ngoại trú', 10],
          ['Tham gia đầy đủ bảo hiểm y tế, bảo hiểm tai nạn <i>(05 điểm/1 loại hình)</i>', 10]
        ];
        foreach ($rows as $i=>$r): ?>
        <tr>
          <td><?=$i+1?></td><td class="text-start"><?=$r[0]?></td><td><?=$r[1]?></td>
          <td><select class="form-select form-select-sm"><?php for($x=0;$x<=$r[1];$x+=($r[1]==10?5:1)):?><option><?=$x?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <?php endforeach; ?>

        <!-- III -->
        <tr class="section-header"><td colspan="6">III. Đánh giá về ý thức và kết quả tham gia hoạt động chính trị, xã hội, văn hoá, văn nghệ, thể thao (20 điểm)</td></tr>
        <tr>
          <td>1</td><td class="text-start">Tham gia nhiệt tình, đầy đủ…<br><i>(Trừ 05 điểm/1 lần vắng mặt)</i></td><td>10</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=10;$i+=5):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <tr>
          <td>2</td><td class="text-start">Có ý thức và tích cực tham gia tuyên truyền công tác phòng chống tệ nạn xã hội, tình nguyện, công ích…</td><td>05</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=5;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>

        <!-- IV -->
        <tr class="section-header"><td colspan="6">IV. Đánh giá về ý thức công dân trong quan hệ cộng đồng (25 điểm)</td></tr>
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
          <td><select class="form-select form-select-sm"><?php for($j=0;$j<=5;$j++):?><option><?=$j?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>
        <?php endforeach; ?>

        <!-- V -->
        <tr class="section-header"><td colspan="6">V. Đánh giá về công tác cán bộ lớp, đoàn thể (10 điểm)</td></tr>
        <tr>
          <td>1</td><td class="text-start">Không là cán bộ lớp, Đoàn, Hội nhưng thực hiện tốt nhiệm vụ</td><td>04</td>
          <td><select class="form-select form-select-sm"><?php for($i=0;$i<=4;$i++):?><option><?=$i?></option><?php endfor;?></select></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input" readonly></td>
        </tr>

        <!-- Tổng -->
        <tr>
          <td colspan="2" class="text-end fw-bold">Tổng cộng</td>
          <td class="fw-bold text-center">100</td>
          <td><input type="number" class="form-control form-control-sm score-input text-center fw-bold" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input text-center fw-bold" readonly></td>
          <td><input type="number" class="form-control form-control-sm score-input text-center fw-bold" readonly></td>
        </tr>

      </tbody>
    </table>
  </div>

  <!-- Ghi chú -->
  <div class="mt-4">
    <p><strong>* Ghi chú:</strong></p>
    <p style="font-style: italic; margin-left: 20px;">
      1. Nếu sinh viên vi phạm quy chế thi, kết quả đánh giá rèn luyện trong học kỳ không vượt quá loại Trung bình.<br>
      2. Nếu sinh viên vi phạm quy chế học sinh, sinh viên bị xử lý từ cảnh cáo trở lên khi đánh giá kết quả rèn luyện không được vượt quá loại Trung bình.
    </p>

    <p>Kết luận của Hội đồng đánh giá cấp khoa:
      <span style="display:inline-block;width:150px;border-bottom:1px solid #000;"></span> điểm.
      Bằng chữ: <span style="display:inline-block;width:300px;border-bottom:1px solid #000;"></span>
    </p>
    <p>Xếp loại: <span style="display:inline-block;width:300px;border-bottom:1px solid #000;"></span></p>

    <div class="row text-center mt-4">
      <div class="col"><strong>Người đánh giá</strong><br><em>(Kí và ghi rõ họ tên)</em></div>
      <div class="col"><strong>CV CTSV</strong><br><em>(Kí và ghi rõ họ tên)</em></div>
      <div class="col"><strong>HĐ Cấp khoa</strong><br><em>(Kí và ghi rõ họ tên)</em></div>
    </div>
  </div>

  <div class="text-end mt-3">
    <button class="btn btn-primary save-btn px-4">💾 Lưu đánh giá</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
