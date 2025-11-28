<?php

session_start();
require_once 'config/db.php';


define('CLEANING_TIME', 15); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $room_id   = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0; // Cần lấy room_id để check trùng
    $movie_id  = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $date_post = isset($_POST['date']) ? $_POST['date'] : '';
    $time_post = isset($_POST['time']) ? $_POST['time'] : '';


    if ($id === 0 || $room_id === 0 || $movie_id === 0 || empty($date_post) || empty($time_post)) {
        $_SESSION['error'] = "Thiếu dữ liệu cập nhật. Vui lòng thử lại.";
        header("Location: dashboard.php");
        exit();
    }
    $new_start_str = $date_post . ' ' . $time_post . ':00';
    $new_start_ts  = strtotime($new_start_str);

    $stmt = $conn->prepare("SELECT duration FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        $_SESSION['error'] = "Phim không tồn tại!";
        header("Location: dashboard.php");
        exit();
    }
    
    $movie = $res->fetch_assoc();
    $duration = intval($movie['duration']);
    $stmt->close();

    $new_end_ts = $new_start_ts + ($duration * 60) + (CLEANING_TIME * 60);

    $check_sql = "
        SELECT s.id, s.date, s.time, m.duration 
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.id
        WHERE s.room_id = ? 
        AND s.date = ?
        AND s.id != ?  -- KHÔNG check với chính nó
    ";

    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("isi", $room_id, $date_post, $id);
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
        $_SESSION['error'] = "Cập nhật thất bại: Giờ chiếu mới bị trùng với một phim khác!";
        header("Location: dashboard.php");
        exit();
    }


    
    $sql_update = "UPDATE showtimes SET movie_id=?, date=?, time=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    
    $time_db = $time_post . ":00";

    if ($stmt) {
        $stmt->bind_param("issi", $movie_id, $date_post, $time_db, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật suất chiếu thành công!";
        } else {
            $_SESSION['error'] = "Lỗi SQL: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Lỗi chuẩn bị câu lệnh: " . $conn->error;
    }

    $conn->close();
    header("Location: dashboard.php");
    exit();

} else {
    header("Location: dashboard.php");
    exit();
}
?>