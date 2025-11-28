<?php
// File: xoaNV.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $current_admin_id = isset($_SESSION['admin']) ? $_SESSION['admin'] : 0; 

    if ($id > 0) {
        if ($id == $current_admin_id) {
            $_SESSION['error'] = "Bạn không thể tự xóa tài khoản của chính mình đang đăng nhập!";
        } else {
            $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Đã xóa nhân viên thành công.";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error'] = "ID nhân viên không hợp lệ.";
    }
}


header("Location: dashboard.php");
exit();
?>