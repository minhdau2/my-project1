<?php
// File: update_profile.php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($phone)) {
        $_SESSION['error'] = "Tên và Số điện thoại không được để trống!";
        header("Location: profile.php");
        exit();
    }

    if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        $_SESSION['error'] = "Số điện thoại không hợp lệ!";
        header("Location: profile.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật hồ sơ thành công!";
        
        
        $_SESSION['user_name'] = $name; 
    } else {
        $_SESSION['error'] = "Lỗi hệ thống: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    
    header("Location: profile.php");
    exit();
}
?>