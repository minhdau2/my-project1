<?php


session_start();
require_once 'config/db.php';
header('Content-Type: application/json');



if (!isset($_SESSION['user']['id']) && !isset($_SESSION['admin']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Bạn cần đăng nhập']);
    exit;
}

$user_id = $_SESSION['user']['id'] ?? $_SESSION['admin']['id'];
$showtime_id = (int)($_POST['showtime_id'] ?? 0);
$seat_id = trim($_POST['seat_id'] ?? '');

if (!$seat_id || !$showtime_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Thiếu tham số']);
    exit;
}

$stmt = $conn->prepare("
    DELETE FROM seat_holds 
    WHERE seat_id = ? AND showtime_id = ? AND user_id = ?
");
$stmt->bind_param("sii", $seat_id, $showtime_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Không tìm thấy ghế bạn đang giữ']);
}

$stmt->close();
$conn->close();
?>
