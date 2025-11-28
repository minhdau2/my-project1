<?php
// seat_broken.php
session_start();
require 'config/db.php'; 

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);


$seatId = $_POST['seat_id'] ?? '';
$showtimeId = $_POST['showtime_id'] ?? '';


if (empty($seatId) || empty($showtimeId)) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu gửi lên bị thiếu (seat_id hoặc showtime_id rỗng)']);
    exit;
}


$sqlRoom = "SELECT room_id FROM showtimes WHERE id = ?";
$stmtRoom = $conn->prepare($sqlRoom);

if (!$stmtRoom) {
    echo json_encode(['success' => false, 'error' => 'Lỗi chuẩn bị SQL Room: ' . $conn->error]);
    exit;
}

$stmtRoom->bind_param("i", $showtimeId);
$stmtRoom->execute();
$resRoom = $stmtRoom->get_result();

if ($resRoom->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Không tìm thấy suất chiếu ID: ' . $showtimeId]);
    exit;
}

$rowRoom = $resRoom->fetch_assoc();
$roomId = $rowRoom['room_id'];
$stmtRoom->close();


$checkSql = "SELECT id FROM seats WHERE seat_id = ? AND showtime_id = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("si", $seatId, $showtimeId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $updateSql = "UPDATE seats SET occupied = 3 WHERE id = ?";
    $upStmt = $conn->prepare($updateSql);
    $upStmt->bind_param("i", $row['id']);
    
    if ($upStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã cập nhật thành công']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Lỗi SQL Update: ' . $conn->error]);
    }
} else {

    $insertSql = "INSERT INTO seats (room_id, showtime_id, seat_id, occupied) VALUES (?, ?, ?, 3)";
    $inStmt = $conn->prepare($insertSql);
    if (!$inStmt) {
         echo json_encode(['success' => false, 'error' => 'Lỗi chuẩn bị SQL Insert: ' . $conn->error]);
         exit;
    }
    $inStmt->bind_param("iis", $roomId, $showtimeId, $seatId);
    
    if ($inStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đã thêm mới thành công']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Lỗi SQL Insert: ' . $conn->error]);
    }
}
?>