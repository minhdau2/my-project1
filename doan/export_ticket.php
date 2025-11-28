<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    die("Vui lòng đăng nhập để xem vé.");
}

$ticketId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userId = $_SESSION['user']['id'] ?? $_SESSION['admin']['id'];

$sql = "SELECT * FROM booking_history WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ticketId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    die("Không tìm thấy vé hoặc bạn không có quyền xem vé này.");
}

$qrData = "TICKET-" . $ticket['order_code'];
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé xem phim - <?= htmlspecialchars($ticket['movie_title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inconsolata', monospace; background: #e2e8f0; }
        .ticket {
            background: white;
            width: 350px;
            margin: 40px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }
        .ticket::before, .ticket::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: #e2e8f0;
            border-radius: 50%;
            top: 70%;
            margin-top: -20px;
        }
        .ticket::before { left: -20px; }
        .ticket::after { right: -20px; }
        .dashed-line {
            border-bottom: 2px dashed #cbd5e1;
            margin: 20px 0;
        }
        @media print {
            body { background: white; }
            .no-print { display: none; }
            .ticket { box-shadow: none; border: 1px solid #ccc; margin: 0 auto; }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="bg-gray-900 text-white p-5 text-center">
            <h2 class="text-xl font-bold uppercase tracking-widest">CINEMABOOKING</h2>
            <p class="text-xs text-gray-400 mt-1">VÉ XEM PHIM ĐIỆN TỬ</p>
        </div>

        <div class="p-6">
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800 leading-tight"><?= htmlspecialchars($ticket['movie_title']) ?></h1>
                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($ticket['order_code']) ?></p>
            </div>

            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-400">Rạp</span>
                    <span class="font-bold text-right"><?= htmlspecialchars($ticket['cinema_name'] ?? 'N/A') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Phòng</span>
                    <span class="font-bold"><?= htmlspecialchars($ticket['room_name'] ?? 'N/A') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Ngày</span>
                    <span class="font-bold"><?= date('d/m/Y', strtotime($ticket['show_date'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Giờ chiếu</span>
                    <span class="font-bold text-lg"><?= date('H:i', strtotime($ticket['show_time'])) ?></span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-2 mt-2">
                    <span class="text-gray-400">Ghế</span>
                    <span class="font-bold text-xl text-purple-600"><?= htmlspecialchars($ticket['seats']) ?></span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-400">Tổng tiền</p>
                    <p class="font-bold text-xl"><?= number_format($ticket['total_amount']) ?> đ</p>
                </div>
                <img src="<?= $qrUrl ?>" alt="QR Code" class="w-20 h-20">
            </div>
        </div>

        <div class="bg-gray-100 p-4 text-center text-xs text-gray-500">
            Vui lòng đưa mã QR này cho nhân viên soát vé.
        </div>
    </div>

    <div class="text-center mt-6 no-print space-x-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-full shadow hover:bg-blue-700 transition font-bold">
            <i class="fa-solid fa-print"></i> In Vé
        </button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-6 py-2 rounded-full shadow hover:bg-gray-600 transition font-bold">
            Đóng
        </button>
    </div>

</body>
</html>