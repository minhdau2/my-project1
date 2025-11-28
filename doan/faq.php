<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Câu Hỏi Thường Gặp | Minhouse.id.vn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* Tùy chỉnh màu sắc để mô phỏng "màu riêng" của bạn */
        .header-bg {
            background-color: #312E81; /* Màu Tím/Xanh Navy đậm */
        }
        .text-accent {
            color: #FBBF24; /* Màu Vàng Gold/Cam nhấn */
        }
        .hover\:text-accent:hover {
            color: #FBBF24;
        }
        /* Style cho trạng thái accordion được mở (tùy chọn) */
        .faq-question[aria-expanded="true"] {
            background-color: #EDE9FE; /* Màu Tím nhạt */
            color: #4C51BF; /* Màu Tím đậm hơn */
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

<nav class="gradient-bg text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold">🎬 CinemaBooking</h1>
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a href="index.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium bg-white bg-opacity-20">Trang Chủ</a>
              <a href="movies.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Phim</a>
              <a href="booking.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Đặt Vé</a>
              <a href="history.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Lịch Sử</a>
              <div class="relative">
                <input type="text" id="searchInput" name="q" placeholder="Tìm kiếm phim..." class="pl-10 pr-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- 👤 LOGIN/LOGOUT -->
          <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="relative group">
                    <button class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium">
                        Xin chào, <?= htmlspecialchars($_SESSION['user']['name']) ?> ▼
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                        <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Thông tin cá nhân</a>
                        <a href="history.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Lịch sử đặt vé</a>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php elseif (isset($_SESSION['admin'])): ?>
                <div class="relative group">
                    <button class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium">
                        Xin chào, Admin <?= htmlspecialchars($_SESSION['admin']['name']) ?> ▼
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Quản trị</a>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <button onclick="showLoginModal()" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                  Đăng Nhập
                </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>

<section class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8 my-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">❓ CÂU HỎI THƯỜNG GẶP (FAQ)</h1>

    <div class="space-y-4" id="faq-accordion">

        <h2 class="text-2xl font-bold text-indigo-700 mt-8 mb-4 border-b pb-2">1. Về Quy Trình Đặt Vé</h2>

        <?php echo render_faq_item("Tôi có cần đăng ký tài khoản để đặt vé không?", "Bạn có thể xem lịch chiếu mà không cần đăng ký. Tuy nhiên, bạn cần có tài khoản để thực hiện thanh toán và quản lý mã vé/lịch sử đặt vé một cách dễ dàng nhất."); ?>
        <?php echo render_faq_item("Làm thế nào để chắc chắn rằng tôi đã đặt vé thành công?", "Ngay sau khi thanh toán thành công, bạn sẽ nhận được một **Mã Vé/QR Code** qua email và mã này cũng hiển thị trong mục 'Lịch Sử Đặt Vé' trên trang web. Đây là bằng chứng xác nhận đặt vé."); ?>
        <?php echo render_faq_item("Tôi có thể chọn ghế ngồi của mình không?", "Có. Trong quá trình đặt vé, sau khi chọn suất chiếu, bạn sẽ được chuyển đến sơ đồ rạp để tự chọn vị trí ghế mong muốn (tùy thuộc vào rạp chiếu)."); ?>

        <h2 class="text-2xl font-bold text-indigo-700 mt-8 mb-4 border-b pb-2">2. Về Thanh Toán và Hoàn Tiền</h2>

        <?php echo render_faq_item("Minhouse.id.vn chấp nhận những hình thức thanh toán nào?", "Chúng tôi chấp nhận thanh toán qua các loại thẻ ngân hàng (Visa, Mastercard, ATM nội địa) và các ví điện tử phổ biến như Momo, ZaloPay, VNPay. Vui lòng kiểm tra chi tiết trên trang thanh toán."); ?>
        <?php echo render_faq_item("Nếu tôi thanh toán thành công nhưng chưa nhận được mã vé thì phải làm sao?", "Đầu tiên, hãy kiểm tra thư mục Spam/Quảng cáo trong email của bạn. Nếu vẫn không thấy, vui lòng liên hệ ngay với chúng tôi qua Hotline hoặc Email hỗ trợ (support@minhouse.id.vn) kèm theo bằng chứng giao dịch để được cấp lại vé."); ?>
        <?php echo render_faq_item("Vé đã mua có được hoàn tiền hay hủy không?", "Theo **Điều Khoản Giao Dịch**, vé đã mua **không thể hủy, hoàn tiền hoặc đổi suất chiếu**. Chính sách này chỉ ngoại lệ khi suất chiếu bị rạp hủy bỏ do sự cố bất khả kháng."); ?>
        
        <h2 class="text-2xl font-bold text-indigo-700 mt-8 mb-4 border-b pb-2">3. Quy Định tại Rạp</h2>

        <?php echo render_faq_item("Tôi có cần in vé ra giấy không?", "Không cần. Bạn chỉ cần xuất trình **Mã QR code** hoặc **Mã số vé** trên điện thoại di động tại quầy soát vé để vào rạp."); ?>
        <?php echo render_faq_item("Tôi có thể mang thức ăn, nước uống từ bên ngoài vào rạp không?", "Quy định này phụ thuộc vào rạp chiếu phim cụ thể. Vui lòng tham khảo quy định của rạp chiếu trước khi vào phòng."); ?>
        <?php echo render_faq_item("Quy định về độ tuổi (T18, T16, P) được áp dụng như thế nào?", "Rạp chiếu phim sẽ kiểm tra giấy tờ tùy thân (CCCD/Giấy khai sinh) để đảm bảo người xem tuân thủ phân loại phim theo quy định của Cục Điện ảnh. Người xem không đủ tuổi quy định sẽ bị từ chối vào phòng chiếu, kể cả khi đã mua vé."); ?>

    </div>

    <div class="mt-10 p-6 bg-indigo-50 rounded-lg text-center">
        <h3 class="text-xl font-semibold text-indigo-800 mb-2">Bạn có câu hỏi khác?</h3>
        <p class="text-gray-600 mb-4">Vui lòng liên hệ với đội ngũ hỗ trợ của chúng tôi để được giải đáp trực tiếp.</p>
        <a href="mailto:support@minhouse.id.vn" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
            Gửi Email Hỗ Trợ
        </a>
    </div>
</section>

<footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">
        © 2025 Minhouse.id.vn – All rights reserved.
    </div>
</footer>

<script>
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        // Đóng tất cả các accordion khác
        document.querySelectorAll('.faq-question').forEach(btn => {
            if (btn !== button && btn.getAttribute('aria-expanded') === 'true') {
                btn.setAttribute('aria-expanded', 'false');
                btn.nextElementSibling.style.maxHeight = null;
            }
        });

        // Mở/Đóng accordion hiện tại
        button.setAttribute('aria-expanded', String(!isExpanded));
        if (!isExpanded) {
            content.style.maxHeight = content.scrollHeight + "px";
        } else {
            content.style.maxHeight = null;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Thiết lập sự kiện cho tất cả các nút FAQ
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => toggleAccordion(button));
        });
    });
</script>

<?php
// Định nghĩa hàm PHP để dễ dàng thêm các câu hỏi và câu trả lời
function render_faq_item($question, $answer) {
    return '
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <button class="faq-question w-full flex justify-between items-center text-left px-6 py-4 font-semibold text-lg text-gray-800 transition duration-300 hover:bg-gray-50 focus:outline-none" 
                    aria-expanded="false" onclick="toggleAccordion(this)">
                <span>' . htmlspecialchars($question) . '</span>
                <svg class="w-5 h-5 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                <p class="px-6 py-4 text-gray-600 border-t border-gray-100">' . htmlspecialchars($answer) . '</p>
            </div>
        </div>
    ';
}
?>

</body>
</html>