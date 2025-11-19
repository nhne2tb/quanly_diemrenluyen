<?php
require_once __DIR__ . '/../../../../config/db.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// ==================== TẢI FILE MẪU ====================
if (isset($_GET['download_template'])) {
    ini_set('display_errors', '0');
    if (function_exists('ob_get_level')) {
        while (ob_get_level() > 0) { ob_end_clean(); }
    }

    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mẫu Import SV');
        $headers = ['MSSV','Họ tên','Giới tính','Ngày sinh','Nơi sinh','Email','SĐT','Địa chỉ'];
        $sample  = ['0022410322','Nguyễn Hồ Ninh Em','Nam','2004-02-01','Đồng Tháp','0022410322@dthu.edu.vn','0912345678','Đồng Tháp'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($sample,  null, 'A2');

        header_remove('X-Powered-By');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="mau_import_sv.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    } catch (\Throwable $e) {
        header('Content-Type: text/plain; charset=utf-8');
        http_response_code(500);
        echo "Lỗi tạo file mẫu: " . $e->getMessage();
    }
    exit;
}

// ==================== KẾT NỐI DB ====================
$conn = Database::connect();

// ==================== MÀU CHỦ ĐẠO ====================
$color1 = $SCHOOL_INFO['mau1'] ?? '#0056B3';
function darkenColor($hex, $p = 15) {
  $hex = str_replace('#', '', $hex);
  if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
  [$r,$g,$b] = [hexdec(substr($hex,0,2)),hexdec(substr($hex,2,2)),hexdec(substr($hex,4,2))];
  $r = max(0, min(255, $r - $r*$p/100));
  $g = max(0, min(255, $g - $g*$p/100));
  $b = max(0, min(255, $b - $b*$p/100));
  return sprintf("#%02x%02x%02x",$r,$g,$b);
}
$color1_hover = darkenColor($color1,15);
function h($v){return htmlspecialchars($v ?? '',ENT_QUOTES,'UTF-8');}

// ==================== LẤY THÔNG TIN LỚP ====================
$lop = $_GET['lop'] ?? '';
if (!$lop) die("Thiếu mã lớp!");

$stmt = $conn->prepare("SELECT ten_lop, ma_khoa, nien_khoa, bac_dao_tao, he_dao_tao, nganh_hoc 
                        FROM tb_lop WHERE ma_lop=?");
$stmt->execute([$lop]);
$lop_info = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$lop_info) die("Không tìm thấy thông tin lớp!");

// ==================== IMPORT EXCEL ====================
if (isset($_POST['import_excel'])) {
    if (!empty($_FILES['excel_file']['tmp_name'])) {
        $table = "sv_" . strtolower($lop_info['ma_khoa']) . "_" . strtolower($lop);
        $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        array_shift($rows); // Bỏ dòng tiêu đề
        $count = 0; $skip = 0;

        foreach ($rows as $r) {
            if (empty($r[0])) continue;
            [$mssv, $ho_ten, $gioi_tinh, $ngay_sinh, $noi_sinh, $email, $sdt, $dia_chi] = array_pad($r, 8, '');

            // Chuyển ngày sinh
            if (is_numeric($ngay_sinh)) {
                $ngay_sinh = gmdate('Y-m-d', ($ngay_sinh - 25569) * 86400);
            } else {
                $ngay_sinh = date('Y-m-d', strtotime(str_replace('/', '-', trim($ngay_sinh))));
            }

            // Kiểm tra MSSV trùng
            $check = $conn->prepare("SELECT COUNT(*) FROM `$table` WHERE mssv=?");
            $check->execute([$mssv]);
            if ($check->fetchColumn() > 0) { $skip++; continue; }

            // Thêm sinh viên
            $stmt = $conn->prepare("INSERT INTO `$table`
              (mssv, mat_khau, ho_ten, gioi_tinh, ngay_sinh, noi_sinh, lop, ma_khoa, nien_khoa,
               bac_dao_tao, loai_hinh, nganh_hoc, trang_thai, email, sdt, dia_chi, ngay_tao)
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?, NOW())");

            $stmt->execute([
                trim($mssv), trim($mssv), trim($ho_ten), trim($gioi_tinh),
                $ngay_sinh, trim($noi_sinh),
                $lop, $lop_info['ma_khoa'], $lop_info['nien_khoa'],
                $lop_info['bac_dao_tao'], $lop_info['he_dao_tao'], $lop_info['nganh_hoc'],
                'Đang học', trim($email), trim($sdt), trim($dia_chi)
            ]);
            $count++;
        }

        header('Location: index.php?route=them_sv&lop='.urlencode($lop).
            '&msg='.urlencode("Đã nhập $count sinh viên, bỏ qua $skip MSSV trùng!"));
        exit;
    } else {
        echo "<script>alert('Vui lòng chọn file Excel để nhập!');</script>";
    }
}

// ==================== NHẬP THỦ CÔNG ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mssv']) && empty($_POST['import_excel'])) {
    $mssv = trim($_POST['mssv']);
    $mat_khau = $mssv; // mật khẩu = MSSV
    $ho_ten = trim($_POST['ho_ten']);
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'] ?: null;
    $noi_sinh = trim($_POST['noi_sinh']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);
    $dia_chi = trim($_POST['dia_chi']);

    $table = "sv_" . strtolower($lop_info['ma_khoa']) . "_" . strtolower($lop);

    // Kiểm tra MSSV trùng
    $check = $conn->prepare("SELECT COUNT(*) FROM `$table` WHERE mssv=?");
    $check->execute([$mssv]);
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('MSSV $mssv đã tồn tại trong lớp này, vui lòng nhập lại!'); history.back();</script>";
        exit;
    }

    // Thêm mới
    $sql = "INSERT INTO `$table`
            (mssv, mat_khau, ho_ten, gioi_tinh, ngay_sinh, noi_sinh, lop, ma_khoa, nien_khoa,
             bac_dao_tao, loai_hinh, nganh_hoc, trang_thai, email, sdt, dia_chi, ngay_tao)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $mssv, $mat_khau, $ho_ten, $gioi_tinh, $ngay_sinh, $noi_sinh,
        $lop, $lop_info['ma_khoa'], $lop_info['nien_khoa'], $lop_info['bac_dao_tao'],
        $lop_info['he_dao_tao'], $lop_info['nganh_hoc'], 'Đang học', $email, $sdt, $dia_chi
    ]);

    header('Location: index.php?route=danhsach_sv&lop='.urlencode($lop).
        '&msg='.urlencode('Đã thêm sinh viên thành công!'));
    exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Thêm sinh viên vào lớp <?= h($lop) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
:root{--brand: <?= h($color1) ?>;--brand-hover: <?= h($color1_hover) ?>;}
body{background:#f8f9fa;font-family:system-ui,Segoe UI,Roboto,sans-serif;}
.container{max-width:950px;margin-top:40px;}
.card{border:none;border-radius:1rem;box-shadow:0 8px 25px rgba(0,0,0,.05);}
.card-header{background:var(--brand);color:#fff;padding:1rem 1.5rem;font-weight:600;}
.btn-brand{background:var(--brand);color:#fff;}
.btn-brand:hover{background:var(--brand-hover);color:#fff;}
input[readonly]{background:#f8f9fa!important;}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div><i class="bi bi-person-plus-fill me-2"></i>Thêm Sinh Viên vào lớp <strong><?= h($lop) ?></strong></div>
      <a href="index.php?route=gd_lop&lop=<?= urlencode($lop) ?>" class="btn btn-light btn-sm text-dark">
        <i class="bi bi-arrow-left"></i> Quay lại
      </a>
    </div>

    <div class="card-body">
      <!-- Import Excel -->
      <form method="post" enctype="multipart/form-data" class="mb-4 border rounded p-3 bg-light">
        <h6 class="fw-bold mb-2"><i class="bi bi-file-earmark-spreadsheet"></i> Nhập từ Excel (.xlsx)</h6>
        <div class="row g-2 align-items-center">
          <div class="col-md-8">
            <input type="file" name="excel_file" accept=".xlsx" class="form-control" required>
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button type="submit" name="import_excel" class="btn btn-success w-50">
              <i class="bi bi-upload"></i> Import
            </button>
            <a href="?route=them_sv&lop=<?= urlencode($lop) ?>&download_template=1"
               class="btn btn-outline-secondary w-50">
               <i class="bi bi-download"></i> Tải mẫu
            </a>
          </div>
        </div>
      </form>

      <hr>

      <!-- Nhập tay -->
      <form method="post" class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">MSSV <span class="text-danger">*</span></label>
          <input name="mssv" class="form-control" required placeholder="VD: 0022410322">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Họ và tên</label>
          <input name="ho_ten" class="form-control" placeholder="VD: Nguyễn Văn A">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Giới tính</label>
          <select name="gioi_tinh" class="form-select">
            <option>Nam</option><option>Nữ</option><option>Khác</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Ngày sinh</label>
          <input type="date" name="ngay_sinh" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Nơi sinh</label>
          <input name="noi_sinh" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Số điện thoại</label>
          <input name="sdt" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Niên khóa</label>
          <input class="form-control" value="<?= h($lop_info['nien_khoa']) ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Bậc đào tạo</label>
          <input class="form-control" value="<?= h($lop_info['bac_dao_tao']) ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Hệ đào tạo</label>
          <input class="form-control" value="<?= h($lop_info['he_dao_tao']) ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Ngành học</label>
          <input class="form-control" value="<?= h($lop_info['nganh_hoc']) ?>" readonly>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Địa chỉ</label>
          <textarea name="dia_chi" rows="2" class="form-control" placeholder="VD: Ấp 1, Tp. Cao Lãnh, Đồng Tháp"></textarea>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
          <button type="submit" class="btn btn-brand">
            <i class="bi bi-person-check"></i> Lưu sinh viên
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
