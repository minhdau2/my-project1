<?php
session_start();
?>

<?php
require 'config/db.php';


$movieId = $_GET['movieId'] ?? null;
$date = $_GET['date'] ?? null;
$showtimeId = $_GET['showtimeId'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname   = $_POST['fullname'] ?? '';
    $email      = $_POST['email'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $movieTitle = $_POST['movieTitle'] ?? '';
    $showtime   = $_POST['showtime'] ?? '';

    $movieId    = $_POST['movieId'] ?? $movieId;
    $date       = $_POST['date'] ?? $date;
    $showtimeId = $_POST['showtimeId'] ?? $showtimeId;

    $seats      = isset($_POST['seats']) ? explode(",", $_POST['seats']) : [];
    $total      = $_POST['total'] ?? 0;
    $cinemaName = $_POST['cinemaName'] ?? '';
    $roomName = $_POST['roomName'] ?? '';

}




$orderCode  = uniqid();
$description = "Thanh toan ve xem phim: $movieTitle - $orderCode";

$accountNumber = "0346468252";
$bankCode      = "VPBank"; 


$encodedDescription = urlencode($description);

$qrUrl = "https://qr.sepay.vn/img?acc={$accountNumber}&bank={$bankCode}&amount=2000&des={$encodedDescription}";

$stmt = $conn->prepare("INSERT INTO transactions (order_code, amount, bank_code, description, status) VALUES (?, ?, ?, ?, ?)");
$status = "pending";
$stmt->bind_param("sisss", $orderCode, $total, $bankCode, $description, $status);
$stmt->execute();
$stmt->close();
?>


<?php
$movieTitle = $_POST['movieTitle'] ?? '';
$price      = $_POST['moviePrice'] ?? 0;
$showtime   = $_POST['showtime'] ?? '';
$date       = $_POST['date'] ?? '';
$seats      = isset($_POST['seats']) ? explode(",", $_POST['seats']) : [];
$total      = $_POST['total'] ?? 0;
$showtimeId = $_POST['showtimeId'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaBooking - Thanh Toán</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
    <nav class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold">🎬 CinemaBooking</h1>
                
            </div>
        </div>
        
    </nav>
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-6">Thanh Toán</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-md p-6 scroll-animate-left">
                <h2 class="text-2xl font-bold mb-4">Quét QR Code để thanh toán</h2>
                <img src="<?= $qrUrl ?>" alt="QR Code thanh toán">
                <p class="mt-4 text-gray-600">Quét mã QR bằng ứng dụng ngân hàng để thanh toán.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 scroll-animate-right">
                <h3 class="text-xl font-bold mb-4">Chi Tiết Đơn Hàng</h3>
                <div class="space-y-2 text-sm " style="font-size: 15px;">
                    <p><strong>Rạp:</strong> <?= htmlspecialchars($cinemaName) ?></p>
                    <p><strong>Phòng chiếu:</strong> <?= htmlspecialchars($roomName) ?></p>
                    <p><strong>Phim:</strong> <?= htmlspecialchars($movieTitle) ?></p>
                    <p><strong>Ngày chiếu:</strong> <?= htmlspecialchars($date) ?></p>
                    <p><strong>Suất chiếu:</strong> <?= htmlspecialchars($showtime) ?></p>
                    <p><strong>Ghế đã chọn:</strong> <?= implode(", ", $seats) ?></p>
                    <p><strong>Số lượng vé:</strong> <?= count($seats) ?></p>
                    <p><strong>Giá mỗi vé:</strong> <?= number_format($price) ?> VNĐ</p>
                    <p><strong>Tổng cộng:</strong> <span class="text-purple-600 font-bold"><?= number_format($total) ?> VNĐ</span></p>
                    <p><strong>Suất chiếu:</strong> <?= htmlspecialchars($showtimeId) ?></p>
                    <button 
                        id="confirmPaymentBtn" 
                        onclick="confirmPayment(movieId,cinemaName,roomName,showtimeId, seats, movieTitle, date, showtime, ticketQuantity, ticketPrice, totalAmount, orderCode)"
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold mt-4 hover:bg-green-700 transition-colors hidden">
                        ✅ Xác Nhận Thanh Toán 
                    </button>

    <div id="resultMessage" class="mt-4 text-center"></div>
                </div>
            </div>
        </div>
    </main>
    <?php if (isset($_SESSION['error'])): ?>
          <div class="bg-red-100 text-red-600 px-4 py-2 rounded mt-3 text-center">
            <?= htmlspecialchars($_SESSION['error']) ?>
          </div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="bg-green-100 text-green-600 px-4 py-2 rounded mt-3 text-center">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        
    <script src="common.js"></script>
    
    <?php if (isset($_SESSION['error'])): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          let modal = document.getElementById("login-modal");
          if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
          }
        });
      </script>
  <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['show_register']) && $_SESSION['show_register']): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById("Register-modal");
      if (modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
      }
    });
  </script>
  <?php unset($_SESSION['show_register']); ?>
  <?php endif; ?>

  <script>
  const showtimeId = <?= (int)$showtimeId ?>;
  const seats = <?= json_encode($seats) ?>;

  

  const movieId = <?= isset($movieId) ? (int)$movieId : 'null' ?>;
  const date = '<?= isset($date) ? addslashes($date) : '' ?>';
    const movieTitle   = <?= json_encode($movieTitle) ?>;
    const showtime     = <?= json_encode($showtime) ?>;
    const ticketQuantity = <?= json_encode(count($seats)) ?>;
    const ticketPrice  = <?= json_encode((float)$price) ?>;
    const totalAmount  = <?= json_encode((float)$total) ?>;
    const orderCode   = <?= json_encode($orderCode) ?>;
    const cinemaName   = <?= json_encode($cinemaName) ?>;
    const roomName   = <?= json_encode($roomName )?>;

  function confirmPayment(movieId,cinemaName,roomName,showtimeId, seats, movieTitle, date, showtime, ticketQuantity, ticketPrice, totalAmount, orderCode) {
            if (!showtimeId || !seats || seats.length === 0) {
                alert("Thiếu dữ liệu để thanh toán!");
                return;
            }

            const data = new URLSearchParams();
            data.append('movieId', movieId);
            data.append('cinemaName', cinemaName);
            data.append('roomName', roomName);
            data.append('showtimeId', showtimeId);
            data.append('seats', seats.join(','));
            data.append('movieTitle', movieTitle);
            data.append('showDate', date);
            data.append('showTime', showtime);
            data.append('ticketQuantity', ticketQuantity);
            data.append('ticketPrice', ticketPrice);
            data.append('totalAmount', totalAmount);
            data.append('orderCode', orderCode);

            fetch('test_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: data
            })
            .then(res => res.json())
            .then(data => {
                const resultDiv = document.getElementById('resultMessage');
                if (data.success) {
                    resultDiv.innerHTML = '<p class="text-green-600 font-semibold">✅ Thanh toán thành công!</p>';
                    setTimeout(() => {
                        window.location.href = `history.php`;
                    }, 1000);
                } else {
                    resultDiv.innerHTML = '<p class="text-red-600 font-semibold">❌ Lỗi: ' + (data.message || 'Không rõ') + '</p>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('resultMessage').innerHTML = '<p class="text-red-600 font-semibold">❌ Có lỗi xảy ra!</p>';
            });
        }
</script>

<script>
const confirmBtn = document.getElementById('confirmPaymentBtn');
const resultDiv = document.getElementById('resultMessage');

window.paymentCheckInterval = setInterval(() => {
  fetch('check_payment.php?orderCode=<?= $orderCode ?>')
    .then(res => res.json())
    .then(data => {
      console.log('Trạng thái thanh toán:', data);
      if (data.status === 'paid' || data.status === 'completed' || data.status === 'success') {
        const resultDiv = document.getElementById('resultMessage');
        resultDiv.innerHTML = '<p class="text-green-600 font-semibold">✅ Thanh toán thành công!</p>';
        document.getElementById('confirmPaymentBtn').style.display = 'block';
        clearInterval(window.paymentCheckInterval);
      }
    })
    .catch(err => console.error('Lỗi kiểm tra thanh toán:', err));
}, 3000);
function toggleMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
      } else {
        menu.classList.add('hidden');
      }
    }
</script>


</body>
</html>
