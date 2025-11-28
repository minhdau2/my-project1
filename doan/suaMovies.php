<?php
session_start();
require 'config/db.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = intval($_POST['duration'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $posterPath = $_POST['old_poster'] ?? null;

    $errors = [];
    if ($id <= 0) $errors[] = "Thiếu ID phim.";
    if ($title === '') $errors[] = "Tên phim bắt buộc.";
    if ($genre === '') $errors[] = "Thể loại bắt buộc.";
    if ($duration <= 0) $errors[] = "Thời lượng không hợp lệ.";
    if ($price <= 0) $errors[] = "Giá vé không hợp lệ.";

    if (!empty($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['poster'];

        if ($f['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $f['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed, true)) {
                $errors[] = "Poster phải là file ảnh (jpg/png/webp/gif).";
            } else {
                $uploadDir = __DIR__ . '/img/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $safeName = uniqid('poster_') . '.' . $ext;
                $target = $uploadDir . $safeName;

                if (move_uploaded_file($f['tmp_name'], $target)) {
                    $posterPath = 'img/' . $safeName;
                } else {
                    $errors[] = "Không thể lưu poster lên server.";
                }
            }
        } else {
            $errors[] = "Lỗi upload poster: " . $f['error'];
        }
    }

    if ($errors) {
        $_SESSION['error'] = 'Cập nhật thất bại: ' . implode(' | ', $errors);
        header("Location: dashboard.php");
        exit;
    }

    if (!empty($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
    $sql = "UPDATE movies 
            SET title=?, genre=?, duration=?, price=?, description=?, poster=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Lỗi prepare statement: " . $conn->error;
        header("Location: dashboard.php");
        exit;
    }
    $stmt->bind_param('ssidssi', $title, $genre, $duration, $price, $description, $posterPath, $id);
} else {

    $sql = "UPDATE movies 
            SET title=?, genre=?, duration=?, price=?, description=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Lỗi prepare statement: " . $conn->error;
        header("Location: dashboard.php");
        exit;
    }
    $stmt->bind_param('ssidsi', $title, $genre, $duration, $price, $description, $id);
}


    

    

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Phim '{$title}' đã được cập nhật thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi cập nhật: " . $stmt->error;
    }

    header("Location: dashboard.php");
    exit;
} else {
    echo "Phải gửi POST để sửa phim.";
}
?>
