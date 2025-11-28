<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {

        $sql_info = "
            SELECT s.date, s.time, m.title 
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.id
            WHERE s.id = ?
        ";
        
        $stmt = $conn->prepare($sql_info);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result_info = $stmt->get_result();
        $info = $result_info->fetch_assoc();
        $stmt->close();

        if ($info) {
            
            $check_booking = $conn->prepare("
                SELECT id FROM booking_history 
                WHERE show_date = ? 
                AND show_time = ? 
                AND movie_title = ?
                LIMIT 1
            ");
            
            $check_booking->bind_param("sss", $info['date'], $info['time'], $info['title']);
            $check_booking->execute();
            $has_booking = $check_booking->get_result()->num_rows > 0;
            $check_booking->close();

            if ($has_booking) {
                $_SESSION['error'] = "Không thể xóa: Đã có khách đặt vé cho suất chiếu này (" . htmlspecialchars($info['title']) . " lúc " . $info['time'] . ")!";
            } else {
                $stmt_del = $conn->prepare("DELETE FROM showtimes WHERE id = ?");
                $stmt_del->bind_param("i", $id);

                if ($stmt_del->execute()) {
                    $_SESSION['success'] = "Đã xóa suất chiếu thành công.";
                } else {
                    $_SESSION['error'] = "Lỗi Database: " . $stmt_del->error;
                }
                $stmt_del->close();
            }

        } else {
            $_SESSION['error'] = "Suất chiếu không tồn tại hoặc đã bị xóa trước đó.";
        }
    } else {
        $_SESSION['error'] = "ID suất chiếu không hợp lệ.";
    }
}

$conn->close();

header("Location: dashboard.php");
exit();
?>