<?php
session_start(); 

require 'config/db.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');


    $stmt = $conn->prepare("SELECT id FROM movies WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Tên phim '$title' đã tồn tại trong hệ thống!";
        header("Location: add_movie.php");
        exit;
    }


    $genres = $_POST['genre'] ?? [];
    $genre = !empty($genres) ? implode(',', $genres) : '';

    $duration = intval($_POST['duration'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    $errors = [];
    if ($title === '') $errors[] = "Tên phim bắt buộc.";
    if ($genre === '') $errors[] = "Thể loại bắt buộc.";
    if ($duration <= 0) $errors[] = "Thời lượng không hợp lệ.";
    if ($price <= 0) $errors[] = "Giá vé không hợp lệ.";

    $posterPath = null;
    if (!empty($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['poster'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Lỗi upload poster: " . $f['error'];
        } else {
            $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $f['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed, true)) {
                $errors[] = "Poster phải là file ảnh (jpg/png/webp/gif).";
            } else {
                $uploadDir = __DIR__ . '/img/';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                    $errors[] = "Không tạo được thư mục img/.";
                }

                if (empty($errors)) {
                    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                    $safeName = uniqid('poster_') . '.' . $ext;
                    $target = $uploadDir . $safeName;
                    if (!move_uploaded_file($f['tmp_name'], $target)) {
                        $errors[] = "Không thể lưu poster lên server.";
                    } else {
                        $posterPath = 'img/' . $safeName;
                    }
                }
            }
        }
    }

    if (count($errors) > 0) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: add_movie.php");
        exit;
    }

    $sql = "INSERT INTO movies (title, genre, duration, price, description, poster, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "DB prepare error: " . $conn->error;
        header("Location: add_movie.php");
        exit;
    }

    $status = 'dang_chieu';
    $stmt->bind_param('ssidsss', $title, $genre, $duration, $price, $description, $posterPath, $status);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Phim '$title' đã được thêm thành công!";
        header('Location: dashboard.php'); 
        exit;
    } else {
        $_SESSION['error'] = "Lỗi khi lưu vào CSDL: " . $stmt->error;
        header("Location: add_movie.php");
        exit;
    }

} else {
    $_SESSION['error'] = "Phải gửi POST để thêm phim.";
    header("Location: add_movie.php");
    exit;
}
