<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, email, password,created_at, role FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_name, $db_email, $db_password,$db_created, $db_role);
            $stmt->fetch();
            
            if (password_verify($password, $db_password)) {
                $_SESSION['admin'] = [
                    'id' => $id, 
                    'name' => $db_name, 
                    'email' => $db_email,
                    'created_at'=>$db_created,
                    'role' => $db_role 
                ];
                $_SESSION['admin_role'] = $db_role; 
                header("Location: dashboard.php");
                exit();
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_name, $db_email, $db_password);
            $stmt->fetch();
            
            if (password_verify($password, $db_password)) {
                $_SESSION['user'] = [
                    'id' => $id, 
                    'name' => $db_name, 
                    'email' => $db_email
                ];
                
                header("Location: index.php");
                exit();
            }
        }
        $stmt->close();

        $_SESSION['error'] = "Email hoặc mật khẩu không đúng.";
    } else {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin.";
    }
}

header("Location: login.php");
exit();
?>