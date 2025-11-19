-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 13, 2025 at 06:05 AM
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
-- Table structure for table `sv_sptin_tin22`
--

CREATE TABLE `sv_sptin_tin22` (
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
-- Dumping data for table `sv_sptin_tin22`
--

INSERT INTO `sv_sptin_tin22` (`mssv`, `mat_khau`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `noi_sinh`, `lop`, `ma_khoa`, `nien_khoa`, `bac_dao_tao`, `loai_hinh`, `nganh_hoc`, `trang_thai`, `email`, `sdt`, `dia_chi`, `ngay_tao`) VALUES
('0022410321', '0022410321', 'Nguyễn Hồ Ninh Em', 'Nam', '2004-02-01', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410322@dthu.edu.vn', '0912345670', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410322', '0022410322', 'Nguyễn Hồ Ninh Em', 'Nam', '2004-02-01', '99', 'bb', 'SPTIN', 'bb', 'đh', 'cq', 'tyu', 'Đang học', '0022410322@student.dthu.edu.vn', '0384 043 660', 'Đồng Tháp', '2025-10-08 09:36:45'),
('0022410323', '0022410323', 'Trần Văn A', 'Nam', '2004-05-10', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410323@dthu.edu.vn', '0912345671', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410324', '0022410324', 'Lê Thị B', 'Nữ', '2004-03-15', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410324@dthu.edu.vn', '0912345672', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410325', '0022410325', 'Phạm Văn C', 'Nam', '2004-06-20', 'An Giang', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410325@dthu.edu.vn', '0912345673', 'An Giang', '2025-10-08 15:46:10'),
('0022410326', '0022410326', 'Huỳnh Thị D', 'Nữ', '2004-04-22', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410326@dthu.edu.vn', '0912345674', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410327', '0022410327', 'Nguyễn Văn E', 'Nam', '2004-01-30', 'Vĩnh Long', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410327@dthu.edu.vn', '0912345675', 'Vĩnh Long', '2025-10-08 15:46:10'),
('0022410328', '0022410328', 'Trần Thị F', 'Nữ', '2004-08-12', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410328@dthu.edu.vn', '0912345676', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410329', '0022410329', 'Lê Văn G', 'Nam', '2004-07-18', 'Tiền Giang', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410329@dthu.edu.vn', '0912345677', 'Tiền Giang', '2025-10-08 15:46:10'),
('0022410330', '0022410330', 'Phan Thị H', 'Nữ', '2004-11-05', 'Đồng Tháp', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410330@dthu.edu.vn', '0912345678', 'Đồng Tháp', '2025-10-08 15:46:10'),
('0022410331', '0022410331', 'Võ Văn I', 'Nam', '2004-09-25', 'Long An', '002241140210A', 'SPTIN', '2022-2026', 'Đại học', 'Chính quy', 'Sư phạm Toán - Tin', 'Đang học', '0022410331@dthu.edu.vn', '0912345679', 'Long An', '2025-10-08 15:46:10');

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
('GV003', '123456', 'TS. Nguyễn Thị C', 'Nữ', '1985-07-20', '', 'Đại học', 'Giảng viên chính', 'Kinh tế học', 'Giảng dạy', 12, 'nguyenc@dthu.edu.vn', '0903333444', '', 'SPTIN', 'https://cdn-icons-png.flaticon.com/512/2202/2202112.png', '2025-10-08 09:11:37');

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
('SPTIN', 'Sư phạm Toán - Tin', '', '', '', '', '', '', '2025-10-08 09:28:12');

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
('TIN22', 'ĐHSTIN22A', '2022 - 2026', '2022', 'Chính quy', 'Đại học', 'Sư phạm Tin Học', 50, 'GV003', 'SPTIN', 'tin22', '');

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
-- Indexes for table `sv_sptin_tin22`
--
ALTER TABLE `sv_sptin_tin22`
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
