<?php
// File: cinema_actions.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add_cinema') {
        $name = trim($_POST['name']);
        $addr = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        
        $stmt = $conn->prepare("INSERT INTO cinemas (name, address, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $addr, $phone);
        
        if ($stmt->execute()) $_SESSION['success'] = "Đã thêm rạp mới!";
        else $_SESSION['error'] = "Lỗi: " . $stmt->error;
    }

    elseif ($action == 'edit_cinema') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $addr = trim($_POST['address']);
        $phone = trim($_POST['phone']);

        $stmt = $conn->prepare("UPDATE cinemas SET name=?, address=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $addr, $phone, $id);
        
        if ($stmt->execute()) $_SESSION['success'] = "Đã cập nhật thông tin rạp!";
        else $_SESSION['error'] = "Lỗi: " . $stmt->error;
    }

    elseif ($action == 'delete_cinema') {
        $id = intval($_POST['id']);

        $check_show = $conn->query("
            SELECT s.id FROM showtimes s 
            JOIN rooms r ON s.room_id = r.id 
            WHERE r.cinema_id = $id 
            LIMIT 1
        ");

        if ($check_show->num_rows > 0) {
            $_SESSION['error'] = "KHÔNG THỂ XÓA: Rạp này đang có các suất chiếu hoạt động hoặc lịch sử đặt vé. Vui lòng xóa các suất chiếu trước!";
        } else {
            
            $conn->query("DELETE FROM rooms WHERE cinema_id=$id");
            
            if ($conn->query("DELETE FROM cinemas WHERE id=$id")) {
                $_SESSION['success'] = "Đã xóa rạp và các phòng trống liên quan!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa rạp.";
            }
        }
    }


    elseif ($action == 'add_room') {
        $name = trim($_POST['name']);
        $cinema_id = intval($_POST['cinema_id']);

        $stmt = $conn->prepare("INSERT INTO rooms (name, cinema_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $cinema_id);
        
        if ($stmt->execute()) $_SESSION['success'] = "Đã thêm phòng chiếu mới!";
        else $_SESSION['error'] = "Lỗi: " . $stmt->error;
    }

    elseif ($action == 'edit_room') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $cinema_id = intval($_POST['cinema_id']);

        $stmt = $conn->prepare("UPDATE rooms SET name=?, cinema_id=? WHERE id=?");
        $stmt->bind_param("sii", $name, $cinema_id, $id);
        
        if ($stmt->execute()) $_SESSION['success'] = "Đã cập nhật phòng chiếu!";
        else $_SESSION['error'] = "Lỗi: " . $stmt->error;
    }

    elseif ($action == 'delete_room') {
        $id = intval($_POST['id']);

        $check_room_show = $conn->query("SELECT id FROM showtimes WHERE room_id = $id LIMIT 1");

        if ($check_room_show->num_rows > 0) {
            $_SESSION['error'] = "KHÔNG THỂ XÓA: Phòng này đang có suất chiếu (lịch sử hoặc sắp chiếu). Bạn không thể xóa để bảo toàn dữ liệu!";
        } else {
            if ($conn->query("DELETE FROM rooms WHERE id=$id")) {
                $_SESSION['success'] = "Đã xóa phòng chiếu thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa phòng.";
            }
        }
    }
}

header("Location: dashboard.php");
exit();
?>