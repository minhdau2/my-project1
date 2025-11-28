<?php
session_start();
require_once 'config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $showtimeId = intval($_POST['showtimeId'] ?? 0);
    $movieId    = $_POST['movieId'] ?? 0;
    $seats = $_POST['seats'] ?? '';
    $movieTitle = $_POST['movieTitle'] ?? '';
    $showDate = $_POST['showDate'] ?? '';
    $showTime = $_POST['showTime'] ?? '';
    $ticketQuantity = intval($_POST['ticketQuantity'] ?? 0);
    $ticketPrice = floatval($_POST['ticketPrice'] ?? 0);
    $totalAmount = floatval($_POST['totalAmount'] ?? 0);
    $orderCode = $_POST['orderCode'] ?? '';
    $cinemaName = $_POST['cinemaName'] ?? '';
    $roomName = $_POST['roomName'] ?? '';

    if (
        $showtimeId > 0 && !empty($seats) && !empty($movieTitle) && 
        !empty($showDate) && !empty($showTime) && 
        $ticketQuantity > 0 && $ticketPrice > 0 && $totalAmount > 0
    ) {
        $userId = null;
        $userType = null;

        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $userType = 'user';
        } elseif (isset($_SESSION['admin']['id'])) {
            $userId = $_SESSION['admin']['id'];
            $userType = 'admin';
        } else {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thanh toán']);
            exit;
        }

        $seatArr = array_map('trim', explode(',', $seats));

        $conn->begin_transaction();

        try {
            foreach ($seatArr as $seatId) {
                $check = $conn->prepare("
                    SELECT user_id FROM seat_holds 
                    WHERE seat_id = ? AND showtime_id = ? AND expires_at > NOW()
                ");
                $check->bind_param("si", $seatId, $showtimeId);
                $check->execute();
                $res = $check->get_result()->fetch_assoc();
                $check->close();

                if (!$res || $res['user_id'] != $userId) {
                    throw new Exception("Bạn không có quyền thanh toán cho ghế $seatId");
                }

                $update = $conn->prepare("
                    UPDATE seats 
                    SET occupied = 1 
                    WHERE seat_id = ? AND showtime_id = ?
                ");
                $update->bind_param("si", $seatId, $showtimeId);
                if (!$update->execute()) {
                    throw new Exception("Lỗi khi cập nhật ghế $seatId: " . $update->error);
                }
                $update->close();

                $del = $conn->prepare("DELETE FROM seat_holds WHERE seat_id = ? AND showtime_id = ?");
                $del->bind_param("si", $seatId, $showtimeId);
                $del->execute();
                $del->close();
            }

            $seatsText = implode(", ", $seatArr);
            $insert = $conn->prepare("
                INSERT INTO booking_history 
                (user_id,movie_id, movie_title,cinema_name,room_name, show_date, show_time, seats, ticket_quantity, ticket_price, total_amount, order_code, user_type)
                VALUES (?,?, ?,?,?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->bind_param(
                "iissssssiddss",
                $userId,
                $movieId,
                $movieTitle,
                $cinemaName,
                $roomName,
                $showDate,
                $showTime,
                $seatsText,
                $ticketQuantity,
                $ticketPrice,
                $totalAmount,
                $orderCode,
                $userType
            );
            if (!$insert->execute()) {
                throw new Exception("Lỗi khi lưu lịch sử đặt vé: " . $insert->error);
            }
            $insert->close();

            $stmt = $conn->prepare("
                UPDATE transactions 
                SET status = 'completed'
                WHERE order_code = ?
            ");
            $stmt->bind_param("s", $orderCode);
            if (!$stmt->execute()) {
                throw new Exception("Lỗi khi cập nhật giao dịch: " . $stmt->error);
            }
            $stmt->close();

            $conn->commit();
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}

$conn->close();
?>
