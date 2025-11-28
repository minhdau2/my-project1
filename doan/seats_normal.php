<?php
// File: seats_normal.php
session_start();
require 'config/db.php';

header('Content-Type: application/json');


$userId = $_SESSION['user']['id'] ?? $_SESSION['admin']['id'] ?? null;
$isAdmin = isset($_SESSION['admin']); // Biến kiểm tra xem có phải Admin không

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập']);
    exit;
}

$seatId = $_POST['seat_id'] ?? '';
$showtimeId = $_POST['showtime_id'] ?? ''; 

if (empty($seatId) || empty($showtimeId)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin ghế/suất chiếu']);
    exit;
}

$stmtRoom = $conn->prepare("SELECT room_id FROM showtimes WHERE id = ?");
$stmtRoom->bind_param("i", $showtimeId);
$stmtRoom->execute();
$resRoom = $stmtRoom->get_result();
if ($resRoom->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Suất chiếu không tồn tại']);
    exit;
}
$rowRoom = $resRoom->fetch_assoc();
$roomId = $rowRoom['room_id'];
$stmtRoom->close();

$sqlCheck = "SELECT id, occupied FROM seats WHERE seat_id = ? AND showtime_id = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("si", $seatId, $showtimeId);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    $rowSeat = $resultCheck->fetch_assoc();
    $currentStatus = (int)$rowSeat['occupied'];
    $dbId = $rowSeat['id'];

    if ($currentStatus === 3) {
        if (!$isAdmin) {
            echo json_encode(['success' => false, 'message' => 'Ghế này đang bảo trì, bạn không thể chọn!']);
            exit;
        }
        
    }

    if ($currentStatus === 1) {
        echo json_encode(['success' => false, 'message' => 'Ghế này đã bán, không thể hủy chọn!']);
        exit;
    }

    $updateSql = "UPDATE seats SET occupied = 0 WHERE id = ?";
    $upStmt = $conn->prepare($updateSql);
    $upStmt->bind_param("i", $dbId);
    
    if ($upStmt->execute()) {
        $msg = ($currentStatus === 3) ? 'Đã sửa xong ghế (Admin)' : 'Đã bỏ chọn ghế';
        echo json_encode(['success' => true, 'message' => $msg]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật DB']);
    }

} else {
    
    $insertSql = "INSERT INTO seats (room_id, showtime_id, seat_id, occupied) VALUES (?, ?, ?, 0)";
    $inStmt = $conn->prepare($insertSql);
    $inStmt->bind_param("iis", $roomId, $showtimeId, $seatId);
    
    if ($inStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã thêm trạng thái trống']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi thêm mới DB']);
    }
}
?>