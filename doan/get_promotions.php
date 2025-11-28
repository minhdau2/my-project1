<?php
require_once 'config/db.php';
session_start(); 
header('Content-Type: application/json');

$movieId = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
$userId = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
$currentDate = date('Y-m-d H:i:s');

$sql = "
    SELECT p.code, p.discount_type, p.discount_value, p.min_order_value, p.usage_limit_per_user,
           COUNT(b.id) as used_count
    FROM promotions p
    LEFT JOIN booking_history b ON p.id = b.promotion_id AND b.user_id = ?
    WHERE p.is_active = 1 
      AND p.is_public = 1
      AND (p.start_date IS NULL OR p.start_date <= ?)
      AND (p.end_date IS NULL OR p.end_date >= ?)
      AND (p.applicable_movie_id IS NULL OR p.applicable_movie_id = ?)
    GROUP BY p.id
    HAVING used_count < p.usage_limit_per_user
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("issi", $userId, $currentDate, $currentDate, $movieId);
$stmt->execute();
$result = $stmt->get_result();

$promotions = [];
while ($row = $result->fetch_assoc()) {
    $promotions[] = [
        'code' => $row['code'],
        'discount_type' => $row['discount_type'],
        'discount_value' => $row['discount_value'],
        'min_order_value' => $row['min_order_value']
    ];
}

echo json_encode($promotions);
?>