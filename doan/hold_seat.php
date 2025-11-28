<?php
// File: seat_hold.php
session_start();
require 'config/db.php';

header('Content-Type: application/json');

$userId = $_SESSION['user']['id'] ?? $_SESSION['admin']['id'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'Bạn cần đăng nhập']);
    exit;
}

$seat_id = $_POST['seat_id'] ?? null;
$showtime_id = $_POST['showtime_id'] ?? null;

if (!$seat_id || !$showtime_id) {
    echo json_encode(['success' => false, 'error' => 'Thiếu tham số']);
    exit;
}


$stmtCheck = $conn->prepare("SELECT occupied FROM seats WHERE seat_id = ? AND showtime_id = ?");
$stmtCheck->bind_param('si', $seat_id, $showtime_id);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result();
$seatInfo = $resCheck->fetch_assoc();
$stmtCheck->close();

if ($seatInfo) {

    if ($seatInfo['occupied'] == 3) {
        echo json_encode(['success' => false, 'error' => 'Ghế này đang bảo trì, vui lòng chọn ghế khác.']);
        exit;
    }
    

    if ($seatInfo['occupied'] == 1) {
        echo json_encode(['success' => false, 'error' => 'Ghế này đã được bán.']);
        exit;
    }
}


$conn->query("DELETE FROM seat_holds WHERE expires_at < NOW()");


$stmt = $conn->prepare("SELECT user_id FROM seat_holds WHERE seat_id=? AND showtime_id=? AND expires_at > NOW()");
$stmt->bind_param('si', $seat_id, $showtime_id);
$stmt->execute();
$hold = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($hold && $hold['user_id'] != $userId) {
    echo json_encode(['success' => false, 'error' => 'Ghế đang được người khác giữ']);
    exit;
}

if ($hold && $hold['user_id'] == $userId) {
    $stmt = $conn->prepare("UPDATE seat_holds SET expires_at=DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE seat_id=? AND showtime_id=? AND user_id=?");
    $stmt->bind_param('sii', $seat_id, $showtime_id, $userId);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("INSERT INTO seat_holds (seat_id, showtime_id, user_id, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
    $stmt->bind_param('sii', $seat_id, $showtime_id, $userId);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['success' => true]);
?>