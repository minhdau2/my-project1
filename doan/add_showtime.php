<?php
session_start();
require_once 'config/db.php';

define('CLEANING_TIME', 15);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id   = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
    $movie_id  = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $date_post = isset($_POST['date']) ? $_POST['date'] : ''; // YYYY-MM-DD
    $time_post = isset($_POST['time']) ? $_POST['time'] : ''; // HH:mm

    if ($room_id === 0 || $movie_id === 0 || empty($date_post) || empty($time_post)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
        header("Location: index.php#showtimes");
        exit();
    }

    $new_start_str = $date_post . ' ' . $time_post . ':00';
    $new_start_ts  = strtotime($new_start_str); // Timestamp bắt đầu

    $stmt = $conn->prepare("SELECT duration FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        $_SESSION['error'] = "Phim không hợp lệ!";
        header("Location: index.php#showtimes");
        exit();
    }
    
    $movie = $res->fetch_assoc();
    $duration = intval($movie['duration']);
    $stmt->close();

    $new_end_ts = $new_start_ts + ($duration * 60) + (CLEANING_TIME * 60);

    $check_sql = "
        SELECT s.date, s.time, m.duration 
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.id
        WHERE s.room_id = ? 
        AND s.date = ? 
    ";

    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $room_id, $date_post);
    $stmt->execute();
    $existing_shows = $stmt->get_result();

    $conflict = false;

    while ($row = $existing_shows->fetch_assoc()) {
        $ex_start_str = $row['date'] . ' ' . $row['time'];
        $ex_start_ts  = strtotime($ex_start_str);
        
        $ex_duration  = intval($row['duration']);
        $ex_end_ts    = $ex_start_ts + ($ex_duration * 60) + (CLEANING_TIME * 60);

        if ($new_start_ts < $ex_end_ts && $new_end_ts > $ex_start_ts) {
            $conflict = true;
            break;
        }
    }
    $stmt->close();

    if ($conflict) {
        $_SESSION['error'] = "Lỗi trùng lịch: Khung giờ này bị đè lên phim khác (đã tính " . CLEANING_TIME . "p dọn dẹp).";
        header("Location: dashboard.php");
        exit();
    }

    $sql_insert = "INSERT INTO showtimes (movie_id, room_id, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    
    $time_db = $time_post . ":00"; 

    if ($stmt) {
        $stmt->bind_param("iiss", $movie_id, $room_id, $date_post, $time_db);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm suất chiếu thành công!";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống: Không thể thêm dữ liệu.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Lỗi kết nối Database.";
    }

    $conn->close();
    header("Location: dashboard.php");
    exit();

} else {
    header("Location: dashboard.php");
    exit();
}
?>