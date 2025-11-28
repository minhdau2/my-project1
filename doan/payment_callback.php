<?php
// LOG
file_put_contents('webhook_debug.log', 
    "[" . date('Y-m-d H:i:s') . "] " . file_get_contents('php://input') . "\n", 
    FILE_APPEND | LOCK_EX
);

require 'config/db.php';


if ($conn->connect_error) {
    http_response_code(500);
    echo "DB Connection failed: " . $conn->connect_error;
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['description'])) {
    http_response_code(400);
    echo "No data";
    exit;
}

$description = $data['description'];

if (!preg_match('/([a-f0-9]{13})/i', $description, $matches)) {
    http_response_code(400);
    echo "Order code not found";
    exit;
}

$orderCode = $matches[1];

$stmt = $conn->prepare("SELECT id FROM transactions WHERE order_code = ? AND status = 'pending'");
if (!$stmt) {
    http_response_code(500);
    echo "Prepare failed (SELECT): " . $conn->error;
    exit;
}

$stmt->bind_param("s", $orderCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    http_response_code(400);
    echo "Order not found or already paid";
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE transactions SET status = 'completed', transaction_time = NOW() WHERE order_code = ?");
if (!$stmt) {
    http_response_code(500);
    echo "Prepare failed (UPDATE): " . $conn->error;
    exit;
}

$stmt->bind_param("s", $orderCode);
$success = $stmt->execute();

if ($success && $stmt->affected_rows > 0) {
    $stmt->close();
    http_response_code(200);
    echo "OK: $orderCode";
} else {
    $stmt->close();
    http_response_code(500);
    echo "Update failed: " . $conn->error;
}