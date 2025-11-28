<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (is_array($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
} else {
    $user_id = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'];
    $new_pass     = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
        header("Location: profile.php");
        exit();
    }

    if ($new_pass !== $confirm_pass) {
        $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
        header("Location: profile.php");
        exit();
    }

    
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $new_pass)) {
        $_SESSION['error'] = "Mật khẩu mới không đủ mạnh! Yêu cầu: Ít nhất 6 ký tự, gồm chữ hoa, chữ thường và số.";
        header("Location: profile.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {

        if (password_verify($current_pass, $user['password'])) {

            $new_hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_hashed_pass, $user_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại sau.";
            }
            $update_stmt->close();

        } else {
            $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
        }
    } else {
        $_SESSION['error'] = "Tài khoản không tồn tại!";
    }

    $conn->close();
    header("Location: profile.php");
    exit();
}
?>