<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost"; 
$username   = "uloehxcu_cinema"; 
$password   = "Minhdau22@"; 
$dbname     = "uloehxcu_cinema"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

// Đặt charset
$conn->set_charset("utf8mb4");
?> 