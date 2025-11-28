<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = intval($_POST['movie_id']);
    $comment = trim($_POST['comment']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (empty($comment)) {
        die("Nội dung bình luận không được để trống!");
    }

    $sql = "INSERT INTO comments (movie_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi SQL: " . $conn->error);
    }

    $stmt->bind_param("iis", $movie_id, $user_id, $comment);
    $stmt->execute();

    header("Location: chitietphim.php?id=" . $movie_id);
    exit();
}
?>
