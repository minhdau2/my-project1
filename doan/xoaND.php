<?php
// File: xoaND.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $check_booking = $conn->prepare("SELECT id FROM booking_history WHERE user_id = ? LIMIT 1");
        $check_booking->bind_param("i", $id);
        $check_booking->execute();
        $check_booking->store_result();

        if ($check_booking->num_rows > 0) {
            $_SESSION['error'] = "Không thể xóa: Người dùng này đã có lịch sử giao dịch! Hãy sử dụng chức năng 'Khóa tài khoản' thay thế.";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Đã xóa người dùng vĩnh viễn.";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_booking->close();
    } else {
        $_SESSION['error'] = "ID người dùng không hợp lệ.";
    }
}
header("Location: dashboard.php#");
exit();
?>