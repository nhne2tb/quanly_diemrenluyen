<?php
// models/bodys_login.php
// ===============================================
// Xử lý dữ liệu cho trang bodys_login
// ===============================================

require_once __DIR__ . '/../config/db.php';

// Kết nối CSDL
$conn = Database::connect();

// ================= HÀM LẤY THÔNG BÁO THEO TAB =================
function layThongBao(PDO $conn, string $tab) {
    $sql = "SELECT * FROM thong_bao WHERE tab = :tab ORDER BY ngay DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['tab' => $tab]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy thông báo tab1 & tab2
$thong_bao_tab1 = layThongBao($conn, 'tab1');
$thong_bao_tab2 = layThongBao($conn, 'tab2');
