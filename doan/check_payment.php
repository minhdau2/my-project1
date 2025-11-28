<?php
require 'config/db.php';
header('Content-Type: application/json');

$orderCode = $_GET['orderCode'] ?? '';

$stmt = $conn->prepare("SELECT status FROM transactions WHERE order_code=?");
$stmt->bind_param("s", $orderCode);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'status' => $result['status'] ?? 'unknown'
]);
