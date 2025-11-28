<?php
session_start();
require_once 'config/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = []; 

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm)) {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }

    if (!preg_match('/^[0-9]{9,11}$/', $phone)) {
        $errors[] = "Số điện thoại không hợp lệ (chỉ gồm số, 9-11 ký tự).";
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự, gồm chữ hoa, chữ thường và số.";
    }

    if ($password !== $confirm) {
        $errors[] = "Mật khẩu nhập lại không khớp!";
    }

if (!empty($errors)) {
    $_SESSION['error'] = implode("\n", $errors);
    $_SESSION['show_register'] = true; 
    header("Location: index.php");
    exit;
}

    $sqlCheck = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email đã tồn tại!";
        header("Location: index.php");
        exit;
    }
    $sqlCheck = "SELECT id FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email đã tồn tại!";
        header("Location: index.php");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sqlInsert = "INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user'] = [
            'id'    => $stmt->insert_id,
            'name'  => $name,
            'email' => $email,
            'phone' => $phone
        ];
        $_SESSION['success'] = "Đăng ký thành công! Xin chào $name 🎉";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Lỗi hệ thống: " . $conn->error;
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
