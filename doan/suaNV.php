<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');

    if ($id && $name && $email && $phone && $role) {
        $stmt = $conn->prepare("UPDATE admin SET name=?, email=?, phone=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $role, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật nhân viên thành công.";
        } else {
            $_SESSION['error'] = "Lỗi khi cập nhật nhân viên: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
    }
} else {
    $_SESSION['error'] = "Yêu cầu không hợp lệ.";
}

header("Location: dashboard.php");
exit;
