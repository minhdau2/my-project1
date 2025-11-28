<?php
// api_seat_toggle.php
session_start();
require_once 'config/db.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seatId = $_POST['seat_id'] ?? '';
    $showtimeId = intval($_POST['showtimeId'] ?? 0);
    $roomId = intval($_POST['room_id'] ?? 0);

    if (empty($seatId) || empty($showtimeId) || empty($roomId)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
        exit;
    }

    $sql = "SELECT id, occupied FROM seats WHERE seat_id = ? AND showtime_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $seatId, $showtimeId);
    $stmt->execute();
    $result = $stmt->get_result();

    $newStatus = 0; 

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['occupied'] == 2) {
            $newStatus = 0;

            $updateSql = "UPDATE seats SET occupied = 0 WHERE id = ?";
            $upStmt = $conn->prepare($updateSql);
            $upStmt->bind_param("i", $row['id']);
            $upStmt->execute();
        } else {
            $newStatus = 2;

            $updateSql = "UPDATE seats SET occupied = 2 WHERE id = ?";
            $upStmt = $conn->prepare($updateSql);
            $upStmt->bind_param("i", $row['id']);
            $upStmt->execute();
        }
    } else {
        $newStatus = 2;
        $insertSql = "INSERT INTO seats (room_id, showtime_id, seat_id, occupied) VALUES (?, ?, ?, ?)";
        $inStmt = $conn->prepare($insertSql);
        $inStmt->bind_param("iisi", $roomId, $showtimeId, $seatId, $newStatus);
        $inStmt->execute();
    }

    echo json_encode([
        'success' => true, 
        'new_status' => $newStatus, 
        'seat_id' => $seatId
    ]);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $showtimeId = intval($_GET['showtimeId'] ?? 0);
    $sql = "SELECT seat_id, occupied FROM seats WHERE showtime_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}
?>