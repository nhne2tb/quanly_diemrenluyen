<?php
// ============================================================
// 📌 FILE: public/index.php — Router trung tâm (Không dùng .htaccess)
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================================
// 📌 NẠP FILE CẤU HÌNH & CSDL
// ============================================================
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// (Tùy chọn) Header chung cho giao diện login
require_once __DIR__ . '/../views/headers/header_login.php';

// ============================================================
// 🌐 LẤY THAM SỐ 'route' TỪ QUERY STRING
// ============================================================
$route = $_GET['route'] ?? '';  // ví dụ: index.php?route=login

// ============================================================
// 🧭 ROUTER KHÔNG DÙNG .HTACCESS
// ============================================================
switch ($route) {

  // 1️⃣ Trang chủ / giao diện đăng nhập
  case '':
  case 'bodys_login':
    require_once __DIR__ . '/../views/bodys/bodys_login.php';
    break;

  // 2️⃣ Xử lý đăng nhập
  case 'login':
    require_once __DIR__ . '/../views/bodys/login.php';
    break;

  // gd_giangvien
  case 'gd_giangvien':
    require_once __DIR__ . '/../views/layouts/admin/admin_giangvien/gd_giangvien.php';
    break;

  // gd_lop
  case 'gd_lop':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/gd_lop.php';
    break;

  // gd_thongbao 
  case 'gd_thongbao':
    require_once __DIR__ . '/../views/layouts/admin/admin_thongbao/gd_thongbao.php';
    break;

  // gd_taikhoan
  case 'gd_taikhoan':
    require_once __DIR__ . '/../views/layouts/admin/admin_taikhoan/gd_taikhoan.php';
    break;

  // phieu_ren_luyen_sv.php
  case 'phieu_ren_luyen_sv':
    require_once __DIR__ . '/../views/phieu_ren_luyen/phieu_ren_luyen_sv.php';
    break;

  // bodys_sinhvien.php
  case 'bodys_sinhvien':  
    require_once __DIR__ . '/../views/bodys_sinhvien/bodys_sinhvien.php';
    break;

  // ket_qua_ren_luyen_sv
  case 'ket_qua_ren_luyen_sv':
    require_once __DIR__ . '/../views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php';
    break;

  // bodys_giangvien_lop_chitiet
  case 'bodys_giangvien_lop_chitiet':
    require_once __DIR__ . '/../views/bodys_giangvien_lop/bodys_giangvien_lop_chitiet.php';
    break;

  // them_lop.php
  case 'them_lop':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/them_lop.php';
    break;
  // sua_lop.php
  case 'sua_lop':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/sua_lop.php';
    break;
  // xoa_lop.php
  case 'xoa_lop':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/xoa_lop.php';
    break;
  //admin_lop.php
  case 'admin_lop':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/admin_lop.php';
    break;
  // them_sv.php
  case 'them_sv':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/them_sv.php';
    break;
  // danhsach_sv.php
  case 'danhsach_sv':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/danhsach_sv.php';
    break;
  //mau_import_sv
  case 'mau_import_sv':
    require_once __DIR__ . '/../views/layouts/admin/admin_lop/mau_import_sv.php';
    break;
  // views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php
  case 'ket_qua_ren_luyen_sv':
    require_once __DIR__ . '/../views/phieu_ren_luyen/ket_qua_ren_luyen_sv.php';
    break;
  // bodys_giangvien_drl.php
  case 'bodys_giangvien_drl':
    require_once __DIR__ . '/../views/bodys_giangvien_lop/bodys_giangvien_drl.php';
    break;

























  // đăng xuất 
  case 'logout':
    require_once __DIR__ . '/../views/xuly/logout.php';
    break;

// chuc nang admin views/layouts/admin/admin_khoa/gd_khoa.php
case 'gd_khoa':
    require_once __DIR__ . '/../views/layouts/admin/admin_khoa/gd_khoa.php';
    break;

  // 3️⃣ Dashboard — chuyển hướng theo loại tài khoản
case 'dashboard':
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?route=bodys_login');
        exit;
    }




    $type = $_SESSION['user']['type'] ?? '';

    switch ($type) {
        case 'bodys_admin':
            require_once __DIR__ . '/../views/bodys_admin/bodys_admin.php';
            break;

        case 'bodys_giangvien':
        case 'bodys_giangvien_lop':
            require_once __DIR__ . '/../views/bodys_giangvien_lop/bodys_giangvien_lop.php';
            break;

        case 'bodys_tapthelop':
            require_once __DIR__ . '/../views/bodys_tapthelop/bodys_tapthelop.php';
            break;

        case 'bodys_sinhvien':
            require_once __DIR__ . '/../views/bodys_sinhvien/bodys_sinhvien.php';
            break;

        default:
            session_destroy();
            header('Location: index.php?route=bodys_login');
            exit;
    }
    break;


  // 4️⃣ Quên mật khẩu
  case 'quenmatkhau':
    require_once __DIR__ . '/../views/bodys/bodys_forgot.php';
    break;

  // 5️⃣ Trang mặc định 404
  default:
    http_response_code(404);
    echo "<h1 style='text-align:center; margin-top:50px;'>
            ⚠️ Trang bạn truy cập không tồn tại.
          </h1>";
    break;
}

// ============================================================
// 📌 Footer chung cho trang login
// ============================================================
require_once __DIR__ . '/../views/footers/footer_login.php';
