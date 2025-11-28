<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $movie_id = intval($_POST['movie_id']);
    $stars = intval($_POST['stars']); 
    $comment = trim($_POST['comment']);

    $check = $conn->prepare("SELECT id FROM booking_history WHERE user_id = ? AND movie_id = ? LIMIT 1");
    $check->bind_param("ii", $user_id, $movie_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
 

        $stmt = $conn->prepare("INSERT INTO ratings (movie_id, user_id, stars, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $movie_id, $user_id, $stars, $comment);
        
        if ($stmt->execute()) {

             header("Location: chitietphim.php?id=" . $movie_id); 
             exit;
        } else {
             echo "Lỗi lưu đánh giá: " . $conn->error;
        }
    } else {
        echo "Bạn chưa mua vé phim này!";
    }
} else {
    header("Location: index.php");
}
?>