<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Phương thức không hợp lệ.";
    header("Location: dashboard.php");
    exit;
}

$movieId = $_POST['id'] ?? null;
if (!$movieId) {
    $_SESSION['error'] = "Không xác định phim cần xóa.";
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM showtimes WHERE movie_id = ?");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    $_SESSION['error'] = "Phim đang có suất chiếu, không thể xóa!";
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
$stmt->bind_param("i", $movieId);

if ($stmt->execute()) {
    $_SESSION['success'] = "Phim đã được xóa vĩnh viễn.";
} else {
    $_SESSION['error'] = "Lỗi khi xóa phim: " . htmlspecialchars($stmt->error);
}

$stmt->close();
header("Location: dashboard.php");
exit;
?>
