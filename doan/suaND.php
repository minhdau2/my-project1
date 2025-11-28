<?php
// File: suaND.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

    if ($id && $name && $email && $phone) {

        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, status=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $email, $phone, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật người dùng thành công.";
        } else {
            $_SESSION['error'] = "Lỗi khi cập nhật người dùng: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
    }
} else {
    $_SESSION['error'] = "Yêu cầu không hợp lệ.";
}

// Sửa đường dẫn này nếu file chính của bạn tên là index.php
header("Location: dashboard.php"); 
exit;
?>