<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều Khoản Sử Dụng | CinemaBooking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css" />
</head>

<body class="bg-gray-100 text-gray-800">

<!-- =============== HEADER =============== -->
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

<!-- =============== CONTENT =============== -->
<section class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8 my-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">📄 Điều Khoản Sử Dụng</h1>

    <p class="text-gray-700 leading-relaxed mb-4">
        Chào mừng bạn đến với nền tảng đặt vé xem phim trực tuyến <strong>CinemaBooking</strong>.
        Khi sử dụng website hoặc các dịch vụ liên quan, bạn đã đồng ý tuân thủ các điều khoản dưới đây.
        Vui lòng đọc kỹ để đảm bảo quyền lợi của bạn.
    </p>

    <hr class="my-6">

    <!-- 1 -->
    <h2 class="text-xl font-semibold mb-3">1. Chấp Nhận Điều Khoản</h2>
    <p class="text-gray-700 mb-4">
        Khi truy cập vào website, bạn đồng ý tuân theo các điều khoản sử dụng.
        Nếu bạn không đồng ý, vui lòng dừng sử dụng dịch vụ của chúng tôi.
    </p>

    <!-- 2 -->
    <h2 class="text-xl font-semibold mb-3">2. Tài Khoản và Bảo Mật</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4">
        <li>Bạn chịu trách nhiệm bảo mật tài khoản và mật khẩu của mình.</li>
        <li>Mọi hành động từ tài khoản được xem như của chính bạn.</li>
        <li>Không chia sẻ tài khoản cho người khác sử dụng.</li>
    </ul>

    <!-- 3 -->
    <h2 class="text-xl font-semibold mb-3">3. Đặt Vé và Thanh Toán</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4">
        <li>Vé đã đặt <strong>không thể hủy hoặc hoàn tiền</strong>, trừ khi hệ thống gặp sự cố hoặc theo chính sách hoàn vé.</li>
        <li>Bạn cần kiểm tra kỹ thông tin phim, suất chiếu, ghế ngồi trước khi thanh toán.</li>
        <li>Chúng tôi không chịu trách nhiệm cho sai sót do bạn nhập sai thông tin.</li>
    </ul>

    <!-- 4 -->
    <h2 class="text-xl font-semibold mb-3">4. Quy Định Rạp Phim</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4">
        <li>Xuất trình mã vé hoặc QR tại quầy để vào rạp.</li>
        <li>Không quay phim, chụp hình hoặc thu âm trong rạp.</li>
        <li>Không mang thức ăn nặng mùi vào rạp.</li>
        <li>Không gây ồn ào hoặc làm ảnh hưởng người xem khác.</li>
    </ul>

    <!-- 5 -->
    <h2 class="text-xl font-semibold mb-3">5. Quyền Thay Đổi Nội Dung</h2>
    <p class="text-gray-700 mb-4">
        CinemaBooking có quyền chỉnh sửa điều khoản, nội dung phim hoặc tính năng mà không cần thông báo trước.
        Mọi thay đổi sẽ được cập nhật trực tiếp trên website.
    </p>

    <!-- 6 -->
    <h2 class="text-xl font-semibold mb-3">6. Quyền Sở Hữu Trí Tuệ</h2>
    <p class="text-gray-700 mb-4">
        Tất cả hình ảnh, nội dung, thiết kế và mã nguồn thuộc quyền sở hữu của CinemaBooking.
        Nghiêm cấm sao chép hoặc sử dụng trái phép dưới mọi hình thức.
    </p>

    <!-- 7 -->
    <h2 class="text-xl font-semibold mb-3">7. Giới Hạn Trách Nhiệm</h2>
    <p class="text-gray-700 mb-4">
        Chúng tôi không chịu trách nhiệm cho mọi sự cố phát sinh từ:
    </p>
    <ul class="list-disc ml-6 text-gray-700 mb-4">
        <li>Lỗi mạng, lỗi kết nối Internet</li>
        <li>Thiết bị của người dùng không tương thích</li>
        <li>Thông tin sai do khách hàng nhập</li>
        <li>Sự cố bất khả kháng (sự cố hệ thống, thiên tai…)</li>
    </ul>

    <!-- 8 -->
    <h2 class="text-xl font-semibold mb-3">8. Liên Hệ Hỗ Trợ</h2>
    <p class="text-gray-700 mb-4">
        Nếu bạn có câu hỏi hoặc cần hỗ trợ, vui lòng liên hệ chúng tôi qua:
    </p>
    <ul class="list-disc ml-6 text-gray-700">
        <li>Email: support@cinemabooking.vn</li>
        <li>Hotline: 0123 456 789</li>
        <li>Địa chỉ: 12 Nguyễn Văn Bảo, Gò Vấp, TP.HCM</li>
    </ul>
</section>

<!-- =============== FOOTER =============== -->
<footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">
        © 2025 CinemaBooking – All rights reserved.
    </div>
</footer>

</body>
</html>
