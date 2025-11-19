-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 03, 2025 at 05:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quanly_diemrenluyen`
--

-- --------------------------------------------------------

--
-- Table structure for table `phieu_ren_luyen`
--

CREATE TABLE `phieu_ren_luyen` (
  `id` int(11) NOT NULL,
  `mssv` varchar(20) NOT NULL,
  `ho_ten` varchar(256) DEFAULT NULL,
  `lop` varchar(256) DEFAULT NULL,
  `khoa` varchar(256) DEFAULT NULL,
  `nien_khoa` varchar(256) DEFAULT NULL,
  `hoc_ky` varchar(256) DEFAULT NULL,
  `nam_bd` varchar(256) DEFAULT NULL,
  `nam_kt` varchar(256) DEFAULT NULL,
  `diem_i1_hoc_tap` tinyint(4) DEFAULT 0,
  `diem_i2_hoc_thuat` tinyint(4) DEFAULT 0,
  `diem_i2_ngoai_khoa` tinyint(4) DEFAULT 0,
  `diem_i2_ky_nang_mem` tinyint(4) DEFAULT 0,
  `diem_i2_nc_khoa_hoc` tinyint(4) DEFAULT 0,
  `diem_i2_cuoc_thi` tinyint(4) DEFAULT 0,
  `diem_i3_vuot_kho` tinyint(4) DEFAULT 0,
  `diem_i4_danh_gia_gv` tinyint(4) DEFAULT 0,
  `diem_i5_tbc` float DEFAULT 0,
  `diem_i_thuong` tinyint(4) DEFAULT 0,
  `diem_ii1_noi_quy` tinyint(4) DEFAULT 0,
  `diem_ii2_quy_che_sv` tinyint(4) DEFAULT 0,
  `diem_ii3_bao_hiem` tinyint(4) DEFAULT 0,
  `diem_iii1_tham_gia` tinyint(4) DEFAULT 0,
  `diem_iii2_tuyen_truyen` tinyint(4) DEFAULT 0,
  `diem_iii3_xep_loai_doan` varchar(20) DEFAULT NULL,
  `diem_iii_thuong` tinyint(4) DEFAULT 0,
  `diem_iv1_chu_truong` tinyint(4) DEFAULT 0,
  `diem_iv2_phap_luat` tinyint(4) DEFAULT 0,
  `diem_iv3_xa_hoi` tinyint(4) DEFAULT 0,
  `diem_iv4_quan_he` tinyint(4) DEFAULT 0,
  `diem_iv5_tuong_than` tinyint(4) DEFAULT 0,
  `diem_v1_khong_can_bo` tinyint(4) DEFAULT 0,
  `diem_v2_khong_hoan_thanh` tinyint(4) DEFAULT 0,
  `diem_v3_can_bo_lop` tinyint(4) DEFAULT 0,
  `tong_diem` float DEFAULT 0,
  `trang_thai` varchar(256) DEFAULT NULL,
  `nguoi_duyet` varchar(256) DEFAULT NULL,
  `ngay_duyet` datetime DEFAULT NULL,
  `nguoi_danh_gia` varchar(256) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_capnhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieu_ren_luyen_sptin_tin22a`
--

CREATE TABLE `phieu_ren_luyen_sptin_tin22a` (
  `id` int(11) NOT NULL,
  `mssv` varchar(20) NOT NULL,
  `ho_ten` varchar(256) DEFAULT NULL,
  `lop` varchar(256) DEFAULT NULL,
  `khoa` varchar(256) DEFAULT NULL,
  `nien_khoa` varchar(256) DEFAULT NULL,
  `hoc_ky` varchar(256) DEFAULT NULL,
  `nam_bd` varchar(256) DEFAULT NULL,
  `nam_kt` varchar(256) DEFAULT NULL,
  `diem_i1_hoc_tap` tinyint(4) DEFAULT 0,
  `diem_i2_hoc_thuat` tinyint(4) DEFAULT 0,
  `diem_i2_ngoai_khoa` tinyint(4) DEFAULT 0,
  `diem_i2_ky_nang_mem` tinyint(4) DEFAULT 0,
  `diem_i2_nc_khoa_hoc` tinyint(4) DEFAULT 0,
  `diem_i2_cuoc_thi` tinyint(4) DEFAULT 0,
  `diem_i3_vuot_kho` tinyint(4) DEFAULT 0,
  `diem_i4_danh_gia_gv` tinyint(4) DEFAULT 0,
  `diem_i5_tbc` float DEFAULT 0,
  `diem_i_thuong` tinyint(4) DEFAULT 0,
  `diem_ii1_noi_quy` tinyint(4) DEFAULT 0,
  `diem_ii2_quy_che_sv` tinyint(4) DEFAULT 0,
  `diem_ii3_bao_hiem` tinyint(4) DEFAULT 0,
  `diem_iii1_tham_gia` tinyint(4) DEFAULT 0,
  `diem_iii2_tuyen_truyen` tinyint(4) DEFAULT 0,
  `diem_iii3_xep_loai_doan` varchar(20) DEFAULT NULL,
  `diem_iii_thuong` tinyint(4) DEFAULT 0,
  `diem_iv1_chu_truong` tinyint(4) DEFAULT 0,
  `diem_iv2_phap_luat` tinyint(4) DEFAULT 0,
  `diem_iv3_xa_hoi` tinyint(4) DEFAULT 0,
  `diem_iv4_quan_he` tinyint(4) DEFAULT 0,
  `diem_iv5_tuong_than` tinyint(4) DEFAULT 0,
  `diem_v1_khong_can_bo` tinyint(4) DEFAULT 0,
  `diem_v2_khong_hoan_thanh` tinyint(4) DEFAULT 0,
  `diem_v3_can_bo_lop` tinyint(4) DEFAULT 0,
  `tong_diem` float DEFAULT 0,
  `trang_thai` varchar(256) DEFAULT NULL,
  `nguoi_duyet` varchar(256) DEFAULT NULL,
  `ngay_duyet` datetime DEFAULT NULL,
  `nguoi_danh_gia` varchar(256) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_capnhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieu_ren_luyen_sptin_tin22a`
--

INSERT INTO `phieu_ren_luyen_sptin_tin22a` (`id`, `mssv`, `ho_ten`, `lop`, `khoa`, `nien_khoa`, `hoc_ky`, `nam_bd`, `nam_kt`, `diem_i1_hoc_tap`, `diem_i2_hoc_thuat`, `diem_i2_ngoai_khoa`, `diem_i2_ky_nang_mem`, `diem_i2_nc_khoa_hoc`, `diem_i2_cuoc_thi`, `diem_i3_vuot_kho`, `diem_i4_danh_gia_gv`, `diem_i5_tbc`, `diem_i_thuong`, `diem_ii1_noi_quy`, `diem_ii2_quy_che_sv`, `diem_ii3_bao_hiem`, `diem_iii1_tham_gia`, `diem_iii2_tuyen_truyen`, `diem_iii3_xep_loai_doan`, `diem_iii_thuong`, `diem_iv1_chu_truong`, `diem_iv2_phap_luat`, `diem_iv3_xa_hoi`, `diem_iv4_quan_he`, `diem_iv5_tuong_than`, `diem_v1_khong_can_bo`, `diem_v2_khong_hoan_thanh`, `diem_v3_can_bo_lop`, `tong_diem`, `trang_thai`, `nguoi_duyet`, `ngay_duyet`, `nguoi_danh_gia`, `ngay_tao`, `ngay_capnhat`) VALUES
(1, '00220001', 'Phạm Văn Tùng', 'TIN22A', 'SPTIN', '2022-2026', 'I', '2025', '2026', 1, 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 0, 0, 0, 0, '5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, '0', NULL, NULL, '00220001', '2025-11-03 00:56:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phieu_ren_luyen_sptin_tin23a`
--

CREATE TABLE `phieu_ren_luyen_sptin_tin23a` (
  `id` int(11) NOT NULL,
  `mssv` varchar(20) NOT NULL,
  `ho_ten` varchar(256) DEFAULT NULL,
  `lop` varchar(256) DEFAULT NULL,
  `khoa` varchar(256) DEFAULT NULL,
  `nien_khoa` varchar(256) DEFAULT NULL,
  `hoc_ky` varchar(256) DEFAULT NULL,
  `nam_bd` varchar(256) DEFAULT NULL,
  `nam_kt` varchar(256) DEFAULT NULL,
  `diem_i1_hoc_tap` tinyint(4) DEFAULT 0,
  `diem_i2_hoc_thuat` tinyint(4) DEFAULT 0,
  `diem_i2_ngoai_khoa` tinyint(4) DEFAULT 0,
  `diem_i2_ky_nang_mem` tinyint(4) DEFAULT 0,
  `diem_i2_nc_khoa_hoc` tinyint(4) DEFAULT 0,
  `diem_i2_cuoc_thi` tinyint(4) DEFAULT 0,
  `diem_i3_vuot_kho` tinyint(4) DEFAULT 0,
  `diem_i4_danh_gia_gv` tinyint(4) DEFAULT 0,
  `diem_i5_tbc` float DEFAULT 0,
  `diem_i_thuong` tinyint(4) DEFAULT 0,
  `diem_ii1_noi_quy` tinyint(4) DEFAULT 0,
  `diem_ii2_quy_che_sv` tinyint(4) DEFAULT 0,
  `diem_ii3_bao_hiem` tinyint(4) DEFAULT 0,
  `diem_iii1_tham_gia` tinyint(4) DEFAULT 0,
  `diem_iii2_tuyen_truyen` tinyint(4) DEFAULT 0,
  `diem_iii3_xep_loai_doan` varchar(20) DEFAULT NULL,
  `diem_iii_thuong` tinyint(4) DEFAULT 0,
  `diem_iv1_chu_truong` tinyint(4) DEFAULT 0,
  `diem_iv2_phap_luat` tinyint(4) DEFAULT 0,
  `diem_iv3_xa_hoi` tinyint(4) DEFAULT 0,
  `diem_iv4_quan_he` tinyint(4) DEFAULT 0,
  `diem_iv5_tuong_than` tinyint(4) DEFAULT 0,
  `diem_v1_khong_can_bo` tinyint(4) DEFAULT 0,
  `diem_v2_khong_hoan_thanh` tinyint(4) DEFAULT 0,
  `diem_v3_can_bo_lop` tinyint(4) DEFAULT 0,
  `tong_diem` float DEFAULT 0,
  `trang_thai` varchar(256) DEFAULT NULL,
  `nguoi_duyet` varchar(256) DEFAULT NULL,
  `ngay_duyet` datetime DEFAULT NULL,
  `nguoi_danh_gia` varchar(256) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_capnhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieu_ren_luyen_sptin_tin23a`
--

INSERT INTO `phieu_ren_luyen_sptin_tin23a` (`id`, `mssv`, `ho_ten`, `lop`, `khoa`, `nien_khoa`, `hoc_ky`, `nam_bd`, `nam_kt`, `diem_i1_hoc_tap`, `diem_i2_hoc_thuat`, `diem_i2_ngoai_khoa`, `diem_i2_ky_nang_mem`, `diem_i2_nc_khoa_hoc`, `diem_i2_cuoc_thi`, `diem_i3_vuot_kho`, `diem_i4_danh_gia_gv`, `diem_i5_tbc`, `diem_i_thuong`, `diem_ii1_noi_quy`, `diem_ii2_quy_che_sv`, `diem_ii3_bao_hiem`, `diem_iii1_tham_gia`, `diem_iii2_tuyen_truyen`, `diem_iii3_xep_loai_doan`, `diem_iii_thuong`, `diem_iv1_chu_truong`, `diem_iv2_phap_luat`, `diem_iv3_xa_hoi`, `diem_iv4_quan_he`, `diem_iv5_tuong_than`, `diem_v1_khong_can_bo`, `diem_v2_khong_hoan_thanh`, `diem_v3_can_bo_lop`, `tong_diem`, `trang_thai`, `nguoi_duyet`, `ngay_duyet`, `nguoi_danh_gia`, `ngay_tao`, `ngay_capnhat`) VALUES
(1, '00230001', 'Nguyễn Văn An', 'TIN23A', 'SPTIN', '2023-2027', 'I', '2025', '2026', 5, 1, 1, 1, 1, 1, 2, 2, 6, 0, 2, 10, 5, 10, 5, '5', 0, 3, 3, 3, 4, 4, 0, 0, 0, 74, 'Chờ duyệt', NULL, NULL, '00230001', '2025-11-03 00:38:59', NULL),
(2, '00230002', 'Trần Thị Bích', 'TIN23A', 'SPTIN', '2023-2027', 'I', '2025', '2026', 0, 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 0, 0, 0, 0, '5', 0, 0, 0, 0, 0, 0, 0, 0, 0, 11, 'Chờ duyệt', NULL, NULL, '00230002', '2025-11-03 00:40:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phieu_ren_luyen_sptin_tin24a`
--

CREATE TABLE `phieu_ren_luyen_sptin_tin24a` (
  `id` int(11) NOT NULL,
  `mssv` varchar(20) NOT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `lop` varchar(50) DEFAULT NULL,
  `khoa` varchar(100) DEFAULT NULL,
  `nien_khoa` varchar(50) DEFAULT NULL,
  `hoc_ky` varchar(10) DEFAULT NULL,
  `nam_bd` varchar(10) DEFAULT NULL,
  `nam_kt` varchar(10) DEFAULT NULL,
  `diem_i1_hoc_tap` int(11) DEFAULT 0,
  `diem_i2_hoc_thuat` int(11) DEFAULT 0,
  `diem_i2_ngoai_khoa` int(11) DEFAULT 0,
  `diem_i2_ky_nang_mem` int(11) DEFAULT 0,
  `diem_i2_nc_khoa_hoc` int(11) DEFAULT 0,
  `diem_i2_cuoc_thi` int(11) DEFAULT 0,
  `diem_i3_vuot_kho` int(11) DEFAULT 0,
  `diem_i4_danh_gia_gv` int(11) DEFAULT 0,
  `diem_i5_tbc` int(11) DEFAULT 0,
  `diem_i_thuong` int(11) DEFAULT 0,
  `diem_ii1_noi_quy` int(11) DEFAULT 0,
  `diem_ii2_quy_che_sv` int(11) DEFAULT 0,
  `diem_ii3_bao_hiem` int(11) DEFAULT 0,
  `diem_iii1_tham_gia` int(11) DEFAULT 0,
  `diem_iii2_tuyen_truyen` int(11) DEFAULT 0,
  `diem_iii3_xep_loai_doan` int(11) DEFAULT 0,
  `diem_iii_thuong` int(11) DEFAULT 0,
  `diem_iv1_chu_truong` int(11) DEFAULT 0,
  `diem_iv2_phap_luat` int(11) DEFAULT 0,
  `diem_iv3_xa_hoi` int(11) DEFAULT 0,
  `diem_iv4_quan_he` int(11) DEFAULT 0,
  `diem_iv5_tuong_than` int(11) DEFAULT 0,
  `diem_v1_khong_can_bo` int(11) DEFAULT 0,
  `diem_v2_khong_hoan_thanh` int(11) DEFAULT 0,
  `diem_v3_can_bo_lop` int(11) DEFAULT 0,
  `tong_diem` int(11) DEFAULT 0,
  `trang_thai` varchar(50) DEFAULT 'Chưa duyệt',
  `nguoi_duyet` varchar(100) DEFAULT NULL,
  `ngay_duyet` datetime DEFAULT NULL,
  `nguoi_danh_gia` varchar(100) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_capnhat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_sptin_tin22a`
--

CREATE TABLE `sv_sptin_tin22a` (
  `mssv` varchar(20) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT 'Nam',
  `ngay_sinh` date DEFAULT NULL,
  `noi_sinh` varchar(150) DEFAULT NULL,
  `lop` varchar(50) DEFAULT NULL,
  `ma_khoa` varchar(50) DEFAULT NULL,
  `nien_khoa` varchar(50) DEFAULT NULL,
  `bac_dao_tao` varchar(50) DEFAULT NULL,
  `loai_hinh` varchar(50) DEFAULT NULL,
  `nganh_hoc` varchar(100) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT 'Đang học',
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sv_sptin_tin22a`
--

INSERT INTO `sv_sptin_tin22a` (`mssv`, `mat_khau`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `noi_sinh`, `lop`, `ma_khoa`, `nien_khoa`, `bac_dao_tao`, `loai_hinh`, `nganh_hoc`, `trang_thai`, `email`, `sdt`, `dia_chi`, `ngay_tao`) VALUES
('00220001', '00220001', 'Phạm Văn Tùng', 'Nam', '2003-05-10', 'Hà Nội', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220001@sv.edu.vn', '0922000001', 'Hà Nội', '2025-11-02 17:55:21'),
('00220002', '00220002', 'Lê Thị Thu Hằng', 'Nữ', '2003-08-12', 'Đà Nẵng', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220002@sv.edu.vn', '0922000002', 'Đà Nẵng', '2025-11-02 17:55:21'),
('00220003', '00220003', 'Trần Minh Hoàng', 'Nam', '2003-01-20', 'Hải Phòng', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220003@sv.edu.vn', '0922000003', 'Hải Phòng', '2025-11-02 17:55:21'),
('00220004', '00220004', 'Nguyễn Thị Mai', 'Nữ', '2003-11-05', 'Cần Thơ', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220004@sv.edu.vn', '0922000004', 'Cần Thơ', '2025-11-02 17:55:21'),
('00220005', '00220005', 'Võ Văn Nam', 'Nam', '2003-04-18', 'Nghệ An', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220005@sv.edu.vn', '0922000005', 'Nghệ An', '2025-11-02 17:55:21'),
('00220006', '00220006', 'Đỗ Thị Lan Anh', 'Nữ', '2003-07-25', 'Thanh Hóa', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220006@sv.edu.vn', '0922000006', 'Thanh Hóa', '2025-11-02 17:55:21'),
('00220007', '00220007', 'Hoàng Minh Đức', 'Nam', '2003-02-14', 'Nam Định', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220007@sv.edu.vn', '0922000007', 'Nam Định', '2025-11-02 17:55:21'),
('00220008', '00220008', 'Bùi Thị Bích', 'Nữ', '2003-09-01', 'Quảng Ninh', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220008@sv.edu.vn', '0922000008', 'Quảng Ninh', '2025-11-02 17:55:21'),
('00220009', '00220009', 'Lý Văn Khoa', 'Nam', '2003-12-30', 'Lạng Sơn', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220009@sv.edu.vn', '0922000009', 'Lạng Sơn', '2025-11-02 17:55:21'),
('00220010', '00220010', 'Trịnh Thị Huyền', 'Nữ', '2003-06-08', 'Hà Tĩnh', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220010@sv.edu.vn', '0922000010', 'Hà Tĩnh', '2025-11-02 17:55:21'),
('00220011', '00220011', 'Dương Văn Tùng', 'Nam', '2003-10-11', 'Thái Bình', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220011@sv.edu.vn', '0922000011', 'Thái Bình', '2025-11-02 17:55:21'),
('00220012', '00220012', 'Phan Thị Yến', 'Nữ', '2003-03-22', 'Bình Định', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220012@sv.edu.vn', '0922000012', 'Bình Định', '2025-11-02 17:55:21'),
('00220013', '00220013', 'Mai Văn Chiến', 'Nam', '2003-08-17', 'Phú Yên', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220013@sv.edu.vn', '0922000013', 'Phú Yên', '2025-11-02 17:55:21'),
('00220014', '00220014', 'Vũ Thị Kim Chi', 'Nữ', '2003-01-07', 'Khánh Hòa', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220014@sv.edu.vn', '0922000014', 'Khánh Hòa', '2025-11-02 17:55:21'),
('00220015', '00220015', 'Đinh Văn Mạnh', 'Nam', '2003-07-03', 'Bình Dương', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220015@sv.edu.vn', '0922000015', 'Bình Dương', '2025-11-02 17:55:21'),
('00220016', '00220016', 'Lâm Thị Ngọc', 'Nữ', '2003-11-28', 'Đồng Nai', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220016@sv.edu.vn', '0922000016', 'Đồng Nai', '2025-11-02 17:55:21'),
('00220017', '00220017', 'Ngô Văn Bảo', 'Nam', '2003-05-16', 'Long An', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220017@sv.edu.vn', '0922000017', 'Long An', '2025-11-02 17:55:21'),
('00220018', '00220018', 'Sơn Thị Mỹ Lệ', 'Nữ', '2003-09-24', 'Trà Vinh', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220018@sv.edu.vn', '0922000018', 'Trà Vinh', '2025-11-02 17:55:21'),
('00220019', '00220019', 'Hồ Văn Trung', 'Nam', '2003-02-09', 'Vĩnh Long', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220019@sv.edu.vn', '0922000019', 'Vĩnh Long', '2025-11-02 17:55:21'),
('00220020', '00220020', 'Trần Thị Thùy Linh', 'Nữ', '2003-12-19', 'An Giang', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220020@sv.edu.vn', '0922000020', 'An Giang', '2025-11-02 17:55:21'),
('00220021', '00220021', 'Nguyễn Hữu Phước', 'Nam', '2003-01-30', 'Kiên Giang', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220021@sv.edu.vn', '0922000021', 'Kiên Giang', '2025-11-02 17:55:21'),
('00220022', '00220022', 'Huỳnh Thị Cẩm', 'Nữ', '2003-04-25', 'Sóc Trăng', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220022@sv.edu.vn', '0922000022', 'Sóc Trăng', '2025-11-02 17:55:21'),
('00220023', '00220023', 'Lê Văn Luyện', 'Nam', '2003-07-14', 'Bạc Liêu', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220023@sv.edu.vn', '0922000023', 'Bạc Liêu', '2025-11-02 17:55:21'),
('00220024', '00220024', 'Trần Thị Diễm', 'Nữ', '2003-10-02', 'Cà Mau', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220024@sv.edu.vn', '0922000024', 'Cà Mau', '2025-11-02 17:55:21'),
('00220025', '00220025', 'Phan Thanh Hùng', 'Nam', '2003-05-21', 'Đồng Tháp', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220025@sv.edu.vn', '0922000025', 'Đồng Tháp', '2025-11-02 17:55:21'),
('00220026', '00220026', 'Nguyễn Ngọc Trâm', 'Nữ', '2003-08-09', 'Hậu Giang', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220026@sv.edu.vn', '0922000026', 'Hậu Giang', '2025-11-02 17:55:21'),
('00220027', '00220027', 'Bùi Anh Tuấn', 'Nam', '2003-11-17', 'Bến Tre', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220027@sv.edu.vn', '0922000027', 'Bến Tre', '2025-11-02 17:55:21'),
('00220028', '00220028', 'Võ Thị Hồng', 'Nữ', '2003-02-27', 'Tiền Giang', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220028@sv.edu.vn', '0922000028', 'Tiền Giang', '2025-11-02 17:55:21'),
('00220029', '00220029', 'Hoàng Văn Phúc', 'Nam', '2003-06-15', 'Tây Ninh', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220029@sv.edu.vn', '0922000029', 'Tây Ninh', '2025-11-02 17:55:21'),
('00220030', '00220030', 'Đặng Thị Thảo', 'Nữ', '2003-09-03', 'Bình Phước', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220030@sv.edu.vn', '0922000030', 'Bình Phước', '2025-11-02 17:55:21'),
('00220031', '00220031', 'Lương Văn Can', 'Nam', '2003-12-11', 'Lâm Đồng', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220031@sv.edu.vn', '0922000031', 'Lâm Đồng', '2025-11-02 17:55:21'),
('00220032', '00220032', 'Nguyễn Thị Kim Ngân', 'Nữ', '2003-03-04', 'Đắk Nông', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220032@sv.edu.vn', '0922000032', 'Đắk Nông', '2025-11-02 17:55:21'),
('00220033', '00220033', 'Trần Văn Sĩ', 'Nam', '2003-07-28', 'Gia Lai', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220033@sv.edu.vn', '0922000033', 'Gia Lai', '2025-11-02 17:55:21'),
('00220034', '00220034', 'Phạm Thị Huệ', 'Nữ', '2003-10-16', 'Kon Tum', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220034@sv.edu.vn', '0922000034', 'Kon Tum', '2025-11-02 17:55:21'),
('00220035', '00220035', 'Huỳnh Văn Toàn', 'Nam', '2003-01-19', 'Bình Thuận', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220035@sv.edu.vn', '0922000035', 'Bình Thuận', '2025-11-02 17:55:21'),
('00220036', '00220036', 'Lê Thị Thu Thủy', 'Nữ', '2003-05-08', 'Ninh Thuận', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220036@sv.edu.vn', '0922000036', 'Ninh Thuận', '2025-11-02 17:55:21'),
('00220037', '00220037', 'Vũ Văn Thanh', 'Nam', '2003-08-27', 'Bà Rịa - Vũng Tàu', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220037@sv.edu.vn', '0922000037', 'Bà Rịa - Vũng Tàu', '2025-11-02 17:55:21'),
('00220038', '00220038', 'Hồ Thị Kiều', 'Nữ', '2003-11-13', 'Quảng Nam', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220038@sv.edu.vn', '0922000038', 'Quảng Nam', '2025-11-02 17:55:21'),
('00220039', '00220039', 'Ngô Văn Dũng', 'Nam', '2003-02-22', 'Quảng Ngãi', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220039@sv.edu.vn', '0922000039', 'Quảng Ngãi', '2025-11-02 17:55:21'),
('00220040', '00220040', 'Dương Thị Mỹ', 'Nữ', '2003-06-19', 'Quảng Bình', 'TIN22A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00220040@sv.edu.vn', '0922000040', 'Quảng Bình', '2025-11-02 17:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `sv_sptin_tin23a`
--

CREATE TABLE `sv_sptin_tin23a` (
  `mssv` varchar(20) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT 'Nam',
  `ngay_sinh` date DEFAULT NULL,
  `noi_sinh` varchar(150) DEFAULT NULL,
  `lop` varchar(50) DEFAULT NULL,
  `ma_khoa` varchar(50) DEFAULT NULL,
  `nien_khoa` varchar(50) DEFAULT NULL,
  `bac_dao_tao` varchar(50) DEFAULT NULL,
  `loai_hinh` varchar(50) DEFAULT NULL,
  `nganh_hoc` varchar(100) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT 'Đang học',
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sv_sptin_tin23a`
--

INSERT INTO `sv_sptin_tin23a` (`mssv`, `mat_khau`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `noi_sinh`, `lop`, `ma_khoa`, `nien_khoa`, `bac_dao_tao`, `loai_hinh`, `nganh_hoc`, `trang_thai`, `email`, `sdt`, `dia_chi`, `ngay_tao`) VALUES
('0022410322', '0022410322', '', 'Nam', NULL, '', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin Học', 'Đang học', '', '', '', '2025-11-02 09:12:44'),
('00230001', '00230001', 'Nguyễn Văn An', 'Nam', '2004-03-15', 'Hà Nội', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230001@sv.edu.vn', '0912345001', 'Hà Nội', '2025-11-02 16:43:08'),
('00230002', '00230002', 'Trần Thị Bích', 'Nữ', '2004-07-22', 'TP. Hồ Chí Minh', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230002@sv.edu.vn', '0912345002', 'TP. Hồ Chí Minh', '2025-11-02 16:43:08'),
('00230003', '00230003', 'Lê Minh Cường', 'Nam', '2004-01-05', 'Đà Nẵng', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230003@sv.edu.vn', '0912345003', 'Đà Nẵng', '2025-11-02 16:43:08'),
('00230004', '00230004', 'Phạm Thu Dung', 'Nữ', '2004-11-30', 'Hải Phòng', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230004@sv.edu.vn', '0912345004', 'Hải Phòng', '2025-11-02 16:43:08'),
('00230005', '00230005', 'Hoàng Văn Giang', 'Nam', '2004-09-12', 'Cần Thơ', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230005@sv.edu.vn', '0912345005', 'Cần Thơ', '2025-11-02 16:43:08'),
('00230006', '00230006', 'Vũ Thị Hoài', 'Nữ', '2004-02-28', 'An Giang', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230006@sv.edu.vn', '0912345006', 'An Giang', '2025-11-02 16:43:08'),
('00230007', '00230007', 'Đặng Minh Khang', 'Nam', '2004-05-19', 'Bà Rịa - Vũng Tàu', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230007@sv.edu.vn', '0912345007', 'Bà Rịa - Vũng Tàu', '2025-11-02 16:43:08'),
('00230008', '00230008', 'Bùi Thị Lan', 'Nữ', '2004-08-01', 'Bắc Giang', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230008@sv.edu.vn', '0912345008', 'Bắc Giang', '2025-11-02 16:43:08'),
('00230009', '00230009', 'Hồ Văn Minh', 'Nam', '2004-12-10', 'Bắc Ninh', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230009@sv.edu.vn', '0912345009', 'Bắc Ninh', '2025-11-02 16:43:08'),
('00230010', '00230010', 'Ngô Thị Ngọc', 'Nữ', '2004-04-07', 'Bến Tre', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230010@sv.edu.vn', '0912345010', 'Bến Tre', '2025-11-02 16:43:08'),
('00230011', '00230011', 'Dương Văn Long', 'Nam', '2004-06-25', 'Bình Định', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230011@sv.edu.vn', '0912345011', 'Bình Định', '2025-11-02 16:43:08'),
('00230012', '00230012', 'Lý Thị Phương', 'Nữ', '2004-10-18', 'Bình Dương', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230012@sv.edu.vn', '0912345012', 'Bình Dương', '2025-11-02 16:43:08'),
('00230013', '00230013', 'Trịnh Văn Quân', 'Nam', '2004-03-02', 'Bình Phước', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230013@sv.edu.vn', '0912345013', 'Bình Phước', '2025-11-02 16:43:08'),
('00230014', '00230014', 'Phan Thị Thảo', 'Nữ', '2004-07-14', 'Bình Thuận', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230014@sv.edu.vn', '0912345014', 'Bình Thuận', '2025-11-02 16:43:08'),
('00230015', '00230015', 'Đỗ Minh Tuấn', 'Nam', '2004-09-09', 'Cà Mau', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230015@sv.edu.vn', '0912345015', 'Cà Mau', '2025-11-02 16:43:08'),
('00230016', '00230016', 'Võ Thị Uyên', 'Nữ', '2004-01-27', 'Cao Bằng', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230016@sv.edu.vn', '0912345016', 'Cao Bằng', '2025-11-02 16:43:08'),
('00230017', '00230017', 'Nguyễn Hữu Hùng', 'Nam', '2004-05-03', 'Đắk Lắk', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230017@sv.edu.vn', '0912345017', 'Đắk Lắk', '2025-11-02 16:43:08'),
('00230018', '00230018', 'Lâm Thị Huệ', 'Nữ', '2004-11-11', 'Đồng Tháp', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230018@sv.edu.vn', '0912345018', 'Đồng Tháp', '2025-11-02 16:43:08'),
('00230019', '00230019', 'Mai Văn Nam', 'Nam', '2004-02-14', 'Gia Lai', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230019@sv.edu.vn', '0912345019', 'Gia Lai', '2025-11-02 16:43:08'),
('00230020', '00230020', 'Nguyễn Thị Kim Anh', 'Nữ', '2004-08-19', 'Đồng Nai', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230020@sv.edu.vn', '0912345020', 'Đồng Nai', '2025-11-02 16:43:08'),
('00230021', '00230021', 'Nguyễn Văn Bình', 'Nam', '2004-01-10', 'Hà Giang', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230021@sv.edu.vn', '0912345021', 'Hà Giang', '2025-11-02 16:43:08'),
('00230022', '00230022', 'Trần Thị Cúc', 'Nữ', '2004-02-12', 'Hà Nam', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230022@sv.edu.vn', '0912345022', 'Hà Nam', '2025-11-02 16:43:08'),
('00230023', '00230023', 'Lê Minh Dũng', 'Nam', '2004-03-14', 'Hà Tĩnh', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230023@sv.edu.vn', '0912345023', 'Hà Tĩnh', '2025-11-02 16:43:08'),
('00230024', '00230024', 'Phạm Thị Hoa', 'Nữ', '2004-04-16', 'Hải Dương', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230024@sv.edu.vn', '0912345024', 'Hải Dương', '2025-11-02 16:43:08'),
('00230025', '00230025', 'Hoàng Văn Em', 'Nam', '2004-05-18', 'Hậu Giang', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230025@sv.edu.vn', '0912345025', 'Hậu Giang', '2025-11-02 16:43:08'),
('00230026', '00230026', 'Vũ Thị Lan', 'Nữ', '2004-06-20', 'Hòa Bình', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230026@sv.edu.vn', '0912345026', 'Hòa Bình', '2025-11-02 16:43:08'),
('00230027', '00230027', 'Đặng Minh Hải', 'Nam', '2004-07-22', 'Hưng Yên', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230027@sv.edu.vn', '0912345027', 'Hưng Yên', '2025-11-02 16:43:08'),
('00230028', '00230028', 'Bùi Thị Mai', 'Nữ', '2004-08-24', 'Khánh Hòa', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230028@sv.edu.vn', '0912345028', 'Khánh Hòa', '2025-11-02 16:43:08'),
('00230029', '00230029', 'Hồ Văn Kiên', 'Nam', '2004-09-26', 'Kiên Giang', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230029@sv.edu.vn', '0912345029', 'Kiên Giang', '2025-11-02 16:43:08'),
('00230030', '00230030', 'Ngô Thị Nhung', 'Nữ', '2004-10-28', 'Kon Tum', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230030@sv.edu.vn', '0912345030', 'Kon Tum', '2025-11-02 16:43:08'),
('00230031', '00230031', 'Dương Văn Lợi', 'Nam', '2004-11-01', 'Lai Châu', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230031@sv.edu.vn', '0912345031', 'Lai Châu', '2025-11-02 16:43:08'),
('00230032', '00230032', 'Lý Thị Oanh', 'Nữ', '2004-12-03', 'Lâm Đồng', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230032@sv.edu.vn', '0912345032', 'Lâm Đồng', '2025-11-02 16:43:08'),
('00230033', '00230033', 'Trịnh Văn Phúc', 'Nam', '2004-01-05', 'Lạng Sơn', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230033@sv.edu.vn', '0912345033', 'Lạng Sơn', '2025-11-02 16:43:08'),
('00230034', '00230034', 'Phan Thị Quỳnh', 'Nữ', '2004-02-07', 'Lào Cai', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230034@sv.edu.vn', '0912345034', 'Lào Cai', '2025-11-02 16:43:08'),
('00230035', '00230035', 'Đỗ Minh Sơn', 'Nam', '2004-03-09', 'Long An', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230035@sv.edu.vn', '0912345035', 'Long An', '2025-11-02 16:43:08'),
('00230036', '00230036', 'Võ Thị Thủy', 'Nữ', '2004-04-11', 'Nam Định', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230036@sv.edu.vn', '0912345036', 'Nam Định', '2025-11-02 16:43:08'),
('00230037', '00230037', 'Nguyễn Hữu Tâm', 'Nam', '2004-05-13', 'Nghệ An', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230037@sv.edu.vn', '0912345037', 'Nghệ An', '2025-11-02 16:43:08'),
('00230038', '00230038', 'Lâm Thị Trang', 'Nữ', '2004-06-15', 'Ninh Bình', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230038@sv.edu.vn', '0912345038', 'Ninh Bình', '2025-11-02 16:43:08'),
('00230039', '00230039', 'Mai Văn Vỹ', 'Nam', '2004-07-17', 'Ninh Thuận', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230039@sv.edu.vn', '0912345039', 'Ninh Thuận', '2025-11-02 16:43:08'),
('00230040', '00230040', 'Nguyễn Thị Xuân', 'Nữ', '2004-08-19', 'Phú Thọ', 'TIN23A', 'SPTIN', '2023-2027', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00230040@sv.edu.vn', '0912345040', 'Phú Thọ', '2025-11-02 16:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `sv_sptin_tin24a`
--

CREATE TABLE `sv_sptin_tin24a` (
  `mssv` varchar(20) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT 'Nam',
  `ngay_sinh` date DEFAULT NULL,
  `noi_sinh` varchar(150) DEFAULT NULL,
  `lop` varchar(50) DEFAULT NULL,
  `ma_khoa` varchar(50) DEFAULT NULL,
  `nien_khoa` varchar(50) DEFAULT NULL,
  `bac_dao_tao` varchar(50) DEFAULT NULL,
  `loai_hinh` varchar(50) DEFAULT NULL,
  `nganh_hoc` varchar(100) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT 'Đang học',
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sv_sptin_tin24a`
--

INSERT INTO `sv_sptin_tin24a` (`mssv`, `mat_khau`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `noi_sinh`, `lop`, `ma_khoa`, `nien_khoa`, `bac_dao_tao`, `loai_hinh`, `nganh_hoc`, `trang_thai`, `email`, `sdt`, `dia_chi`, `ngay_tao`) VALUES
('00240001', '00240001', 'Trần Văn An', 'Nam', '2005-01-20', 'Hà Nội', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240001@sv.edu.vn', '0924000001', 'Hà Nội', '2025-11-02 18:00:34'),
('00240002', '00240002', 'Nguyễn Thị Bình', 'Nữ', '2005-02-15', 'TP. Hồ Chí Minh', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240002@sv.edu.vn', '0924000002', 'TP. Hồ Chí Minh', '2025-11-02 18:00:34'),
('00240003', '00240003', 'Lê Văn Cường', 'Nam', '2005-03-10', 'Đà Nẵng', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240003@sv.edu.vn', '0924000003', 'Đà Nẵng', '2025-11-02 18:00:34'),
('00240004', '00240004', 'Phạm Thị Dung', 'Nữ', '2005-04-05', 'Hải Phòng', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240004@sv.edu.vn', '0924000004', 'Hải Phòng', '2025-11-02 18:00:34'),
('00240005', '00240005', 'Hoàng Văn Giang', 'Nam', '2005-05-12', 'Cần Thơ', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240005@sv.edu.vn', '0924000005', 'Cần Thơ', '2025-11-02 18:00:34'),
('00240006', '00240006', 'Vũ Thị Hà', 'Nữ', '2005-06-18', 'Nghệ An', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240006@sv.edu.vn', '0924000006', 'Nghệ An', '2025-11-02 18:00:34'),
('00240007', '00240007', 'Đặng Minh Hùng', 'Nam', '2005-07-22', 'Thanh Hóa', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240007@sv.edu.vn', '0924000007', 'Thanh Hóa', '2025-11-02 18:00:34'),
('00240008', '00240008', 'Bùi Thị Lan', 'Nữ', '2005-08-30', 'Nam Định', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240008@sv.edu.vn', '0924000008', 'Nam Định', '2025-11-02 18:00:34'),
('00240009', '00240009', 'Lý Văn Khoa', 'Nam', '2005-09-05', 'Quảng Ninh', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240009@sv.edu.vn', '0924000009', 'Quảng Ninh', '2025-11-02 18:00:34'),
('00240010', '00240010', 'Trịnh Thị Mai', 'Nữ', '2005-10-11', 'Lạng Sơn', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240010@sv.edu.vn', '0924000010', 'Lạng Sơn', '2025-11-02 18:00:34'),
('00240011', '00240011', 'Dương Văn Nam', 'Nam', '2005-11-16', 'Hà Tĩnh', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240011@sv.edu.vn', '0924000011', 'Hà Tĩnh', '2025-11-02 18:00:34'),
('00240012', '00240012', 'Phan Thị Ngọc', 'Nữ', '2005-12-25', 'Thái Bình', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240012@sv.edu.vn', '0924000012', 'Thái Bình', '2025-11-02 18:00:34'),
('00240013', '00240013', 'Mai Văn Phúc', 'Nam', '2005-01-08', 'Bình Định', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240013@sv.edu.vn', '0924000013', 'Bình Định', '2025-11-02 18:00:34'),
('00240014', '00240014', 'Vũ Thị Quỳnh', 'Nữ', '2005-02-17', 'Phú Yên', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240014@sv.edu.vn', '0924000014', 'Phú Yên', '2025-11-02 18:00:34'),
('00240015', '00240015', 'Đinh Văn Sơn', 'Nam', '2005-03-24', 'Khánh Hòa', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240015@sv.edu.vn', '0924000015', 'Khánh Hòa', '2025-11-02 18:00:34'),
('00240016', '00240016', 'Lâm Thị Trang', 'Nữ', '2005-04-19', 'Bình Dương', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240016@sv.edu.vn', '0924000016', 'Bình Dương', '2025-11-02 18:00:34'),
('00240017', '00240017', 'Ngô Văn Tú', 'Nam', '2005-05-28', 'Đồng Nai', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240017@sv.edu.vn', '0924000017', 'Đồng Nai', '2025-11-02 18:00:34'),
('00240018', '00240018', 'Sơn Thị Uyên', 'Nữ', '2005-06-03', 'Long An', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240018@sv.edu.vn', '0924000018', 'Long An', '2025-11-02 18:00:34'),
('00240019', '00240019', 'Hồ Văn Việt', 'Nam', '2005-07-14', 'Trà Vinh', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240019@sv.edu.vn', '0924000019', 'Trà Vinh', '2025-11-02 18:00:34'),
('00240020', '00240020', 'Trần Thị Xuân', 'Nữ', '2005-08-21', 'An Giang', 'TIN24A', 'SPTIN', '2024-2028', 'Đại học', 'Chính quy', 'Sư phạm Tin học', 'Đang học', '00240020@sv.edu.vn', '0924000020', 'An Giang', '2025-11-02 18:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `ma_ad` varchar(20) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `quyen` varchar(50) DEFAULT 'admin',
  `avatar` varchar(255) DEFAULT 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`ma_ad`, `mat_khau`, `hoten`, `email`, `quyen`, `avatar`, `ngay_tao`) VALUES
('admin', '123456', 'Quản trị hệ thống', 'admin@dthu.edu.vn', 'Super Admin', 'https://cdn-icons-png.flaticon.com/512/149/149071.png', '2025-10-08 09:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `tb_giangvien`
--

CREATE TABLE `tb_giangvien` (
  `ma_gv` varchar(20) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `hoc_ham` varchar(50) DEFAULT NULL,
  `hoc_vi` varchar(50) DEFAULT NULL,
  `chuc_danh` varchar(100) DEFAULT NULL,
  `chuyen_mon` varchar(150) DEFAULT NULL,
  `nhiem_vu` text DEFAULT NULL,
  `nam_cong_tac` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `bo_mon` varchar(100) DEFAULT NULL,
  `ma_khoa` varchar(10) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_giangvien`
--

INSERT INTO `tb_giangvien` (`ma_gv`, `mat_khau`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `hoc_ham`, `hoc_vi`, `chuc_danh`, `chuyen_mon`, `nhiem_vu`, `nam_cong_tac`, `email`, `sdt`, `bo_mon`, `ma_khoa`, `avatar`, `ngay_tao`) VALUES
('GV003', '123456', 'TS. Nguyễn Thị C', 'Nữ', '1985-07-20', '', 'Đại học', 'Giảng viên chính', 'Kinh tế học', 'Giảng dạy', 12, 'nguyenc@dthu.edu.vn', '0903333444', '123', 'SPTIN', 'https://cdn-icons-png.flaticon.com/512/2202/2202112.png', '2025-10-08 09:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `tb_khoa`
--

CREATE TABLE `tb_khoa` (
  `ma_khoa` varchar(10) NOT NULL,
  `ten_khoa` varchar(100) NOT NULL,
  `truong_khoa` varchar(100) NOT NULL,
  `pho_khoa` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_khoa`
--

INSERT INTO `tb_khoa` (`ma_khoa`, `ten_khoa`, `truong_khoa`, `pho_khoa`, `email`, `sdt`, `dia_chi`, `website`, `ngay_tao`) VALUES
('SPTIN', 'Sư phạm Tin học', 'TS. Nguyễn Văn A', 'ThS. Trần Thị B', 'vpk.sptin@edu.vn', '02838123456', 'Nhà A1, Khu Đại học', 'sptin.edu.vn', '2025-10-08 09:28:12'),
('SPTOAN', 'Sư phạm Toán', 'PGS.TS. Lê Văn C', 'TS. Phạm Thị D', 'vpk.sptoan@edu.vn', '02838123457', 'Nhà A2, Khu Đại học', 'sptoan.edu.vn', '2025-11-02 16:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `tb_lop`
--

CREATE TABLE `tb_lop` (
  `ma_lop` varchar(20) NOT NULL,
  `ten_lop` varchar(255) NOT NULL,
  `nien_khoa` varchar(50) NOT NULL,
  `khoa_hoc` varchar(20) NOT NULL,
  `he_dao_tao` varchar(50) NOT NULL DEFAULT 'Chính quy',
  `bac_dao_tao` varchar(50) NOT NULL DEFAULT 'Đại học',
  `nganh_hoc` varchar(255) NOT NULL,
  `si_so` int(11) DEFAULT 0,
  `ma_gv` varchar(20) DEFAULT NULL,
  `ma_khoa` varchar(20) DEFAULT NULL,
  `mat_khau_lop` varchar(255) NOT NULL,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_lop`
--

INSERT INTO `tb_lop` (`ma_lop`, `ten_lop`, `nien_khoa`, `khoa_hoc`, `he_dao_tao`, `bac_dao_tao`, `nganh_hoc`, `si_so`, `ma_gv`, `ma_khoa`, `mat_khau_lop`, `ghi_chu`) VALUES
('TIN22A', 'DHSTIN22A', '2022-2026', '22', 'Chính quy', 'Đại học', 'Sư phạm Tin học', 50, 'GV003', 'SPTIN', 'tin22a', ''),
('TIN23A', 'DHSTIN23A', '2023-2027', '23', 'Chính quy', 'Đại học', 'Sư phạm Tin Học', 50, 'GV003', 'SPTIN', 'tin23a', ''),
('TIN24A', 'DHSTIN24A', '2024-2028', '24', 'Chính quy', 'Đại học', 'Sư phạm Tin học', 50, 'GV003', 'SPTIN', 'tin24a', '');

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `ma_thong_bao` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `lien_ket` varchar(255) DEFAULT NULL,
  `ngay` date NOT NULL,
  `tab` enum('tab1','tab2') DEFAULT 'tab1',
  `moi` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thong_bao`
--

INSERT INTO `thong_bao` (`ma_thong_bao`, `tieu_de`, `noi_dung`, `lien_ket`, `ngay`, `tab`, `moi`) VALUES
(1, 'Hướng dẫn sinh viên tự đánh giá điểm rèn luyện học kỳ I năm học 2025–2026', 'Sinh viên truy cập hệ thống để tự đánh giá điểm rèn luyện, hoàn tất trước ngày 15/10/2025.', 'https://drive.google.com/file/d/1f7Ws7sA01nOEmc_jWtAv-rKjjxjAqfFJ/view?usp=sharing', '2025-09-15', 'tab1', 1),
(2, 'Biểu mẫu đánh giá điểm rèn luyện sinh viên', 'Tải mẫu phiếu đánh giá điểm rèn luyện do Phòng Công tác Sinh viên ban hành.', 'https://drive.google.com/file/d/1f7Ws7sA01nOEmc_jWtAv-rKjjxjAqfFJ/view?usp=sharing', '2025-08-01', 'tab2', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `phieu_ren_luyen`
--
ALTER TABLE `phieu_ren_luyen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mssv` (`mssv`);

--
-- Indexes for table `phieu_ren_luyen_sptin_tin22a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin22a`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mssv` (`mssv`);

--
-- Indexes for table `phieu_ren_luyen_sptin_tin23a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin23a`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mssv` (`mssv`);

--
-- Indexes for table `phieu_ren_luyen_sptin_tin24a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin24a`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mssv` (`mssv`);

--
-- Indexes for table `sv_sptin_tin22a`
--
ALTER TABLE `sv_sptin_tin22a`
  ADD PRIMARY KEY (`mssv`);

--
-- Indexes for table `sv_sptin_tin23a`
--
ALTER TABLE `sv_sptin_tin23a`
  ADD PRIMARY KEY (`mssv`);

--
-- Indexes for table `sv_sptin_tin24a`
--
ALTER TABLE `sv_sptin_tin24a`
  ADD PRIMARY KEY (`mssv`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`ma_ad`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_giangvien`
--
ALTER TABLE `tb_giangvien`
  ADD PRIMARY KEY (`ma_gv`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ma_khoa` (`ma_khoa`);

--
-- Indexes for table `tb_khoa`
--
ALTER TABLE `tb_khoa`
  ADD PRIMARY KEY (`ma_khoa`);

--
-- Indexes for table `tb_lop`
--
ALTER TABLE `tb_lop`
  ADD PRIMARY KEY (`ma_lop`),
  ADD KEY `fk_lop_gv` (`ma_gv`),
  ADD KEY `fk_lop_khoa` (`ma_khoa`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`ma_thong_bao`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phieu_ren_luyen`
--
ALTER TABLE `phieu_ren_luyen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `phieu_ren_luyen_sptin_tin22a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin22a`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phieu_ren_luyen_sptin_tin23a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin23a`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phieu_ren_luyen_sptin_tin24a`
--
ALTER TABLE `phieu_ren_luyen_sptin_tin24a`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `ma_thong_bao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_giangvien`
--
ALTER TABLE `tb_giangvien`
  ADD CONSTRAINT `tb_giangvien_ibfk_1` FOREIGN KEY (`ma_khoa`) REFERENCES `tb_khoa` (`ma_khoa`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tb_lop`
--
ALTER TABLE `tb_lop`
  ADD CONSTRAINT `fk_lop_gv` FOREIGN KEY (`ma_gv`) REFERENCES `tb_giangvien` (`ma_gv`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lop_khoa` FOREIGN KEY (`ma_khoa`) REFERENCES `tb_khoa` (`ma_khoa`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
