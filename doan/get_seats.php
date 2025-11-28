<?php
session_start();
require_once 'config/db.php';

// 1. Xác định ai đang đăng nhập
$userId = $_SESSION['user']['id'] ?? $_SESSION['admin']['id'] ?? 0;
$isAdmin = isset($_SESSION['admin']); // Biến kiểm tra xem có phải Admin không

$showtimeId = intval($_GET['showtimeId'] ?? 0);
if ($showtimeId === 0) exit;

// Lấy trạng thái ghế
$sqlSeats = "SELECT seat_id, occupied FROM seats WHERE showtime_id = $showtimeId";
$resultSeats = $conn->query($sqlSeats);

$seatStatus = [];
while ($seat = $resultSeats->fetch_assoc()) {
    $seatStatus[$seat['seat_id']] = (int)$seat['occupied'];
}

// Lấy ghế đang giữ
$sqlHolds = "SELECT seat_id, user_id FROM seat_holds WHERE showtime_id = $showtimeId AND expires_at > NOW()";
$resultHolds = $conn->query($sqlHolds);

$seatHolds = [];
while ($hold = $resultHolds->fetch_assoc()) {
    $seatHolds[$hold['seat_id']] = $hold['user_id'];
}

$rows = range('A', 'H');
$cols = range(1, 8);

foreach ($rows as $row) {
    echo '<div class="flex items-center space-x-1">';
    echo '<span class="w-6 text-center font-semibold text-gray-600">' . $row . '</span>';

    foreach ($cols as $col) {
        $seatId = $row . $col;
        $currentStatus = isset($seatStatus[$seatId]) ? $seatStatus[$seatId] : 0;

        // Các trạng thái
        $isBroken = ($currentStatus === 3);
        $isOccupied = ($currentStatus === 1);
        $isHeld = isset($seatHolds[$seatId]);
        $heldByOther = $isHeld && $seatHolds[$seatId] != $userId;

        // --- LOGIC HIỂN THỊ ---
        if ($isBroken) {
            // NẾU LÀ GHẾ HỎNG
            if ($isAdmin) {
                // Admin: Cho phép chọn (để sửa)
                $disabled = ''; 
                $checked = '';
                // Thêm viền vàng hoặc hiệu ứng để Admin biết đây là ghế hỏng có thể sửa
                $class = 'seat broken bg-gray-600 cursor-pointer border-2 border-yellow-400 hover:bg-gray-500';
            } else {
                // Khách: Chặn
                $disabled = 'disabled';
                $checked = '';
                $class = 'seat broken bg-gray-600 cursor-not-allowed opacity-50';
            }
            
        } elseif ($isOccupied) {
            $disabled = 'disabled';
            $checked = '';
            $class = 'seat occupied bg-red-500 cursor-not-allowed';

        } elseif ($heldByOther) {
            $disabled = 'disabled';
            $checked = '';
            $class = 'seat held bg-yellow-400 cursor-not-allowed';

        } elseif ($isHeld && $seatHolds[$seatId] == $userId) {
            $disabled = '';
            $checked = 'checked';
            $class = 'seat selected bg-blue-500 cursor-pointer';

        } else {
            $disabled = '';
            $checked = '';
            $class = 'seat available bg-gray-200 cursor-pointer';
        }

        // In ra
        echo "
        <label class='seat-wrapper'>
            <input type='checkbox' name='seats[]' value='$seatId' $disabled $checked class='hidden'>
            <span class='$class w-8 h-8 inline-flex items-center justify-center text-white font-semibold rounded text-xs'>
                " . ($isBroken ? '<i class="fa-solid fa-wrench"></i>' : '') . " 
            </span>
        </label>";
        // Lưu ý: Tôi dùng icon 'fa-wrench' (cờ lê) cho ghế hỏng để Admin dễ nhận biết.
    }

    echo '<span class="w-6 text-center font-semibold text-gray-600">' . $row . '</span>';
    echo '</div>';
}

$conn->close();
?>