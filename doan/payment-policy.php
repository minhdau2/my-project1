<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chính sách Thanh toán | Minhouse.id.vn</title>
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
    <h1 class="text-3xl font-bold text-gray-900 mb-6">💳 CHÍNH SÁCH THANH TOÁN</h1>

    <p class="text-gray-700 leading-relaxed mb-4">
        Chính sách này quy định các phương thức, quy trình thanh toán và các vấn đề liên quan đến việc giao dịch 
        đặt vé trên nền tảng **minhouse.id.vn**.
    </p>

    <hr class="my-6">

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">1. PHƯƠNG THỨC THANH TOÁN CHẤP NHẬN</h2>
    <p class="text-gray-700 mb-2">Minhouse.id.vn hiện đang áp dụng các phương thức thanh toán trực tuyến sau:</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Thẻ Ngân Hàng (Nội địa & Quốc tế):</strong> Thẻ ATM/Thanh toán nội địa có đăng ký Internet Banking, Thẻ Visa/Mastercard/JCB.</li>
        <li><strong>Ví Điện Tử:</strong> Momo, ZaloPay, VNPay và các ví điện tử phổ biến khác.</li>
        <li><strong>Chuyển Khoản Ngân Hàng:</strong> Chỉ áp dụng cho một số trường hợp đặc biệt và có hướng dẫn chi tiết trên trang thanh toán.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">2. QUY TRÌNH XỬ LÝ THANH TOÁN</h2>
    <ul class="list-decimal ml-6 text-gray-700 mb-4 space-y-2">
        <li><strong>Chọn Lựa:</strong> Khách hàng chọn phim, suất chiếu, ghế ngồi và xác nhận tổng tiền thanh toán.</li>
        <li><strong>Thanh Toán:</strong> Khách hàng được chuyển đến cổng thanh toán của bên thứ ba (ngân hàng hoặc ví điện tử).</li>
        <li><strong>Xác Nhận:</strong> Sau khi thanh toán thành công, hệ thống Minhouse.id.vn sẽ gửi **Mã Vé/QR Code** qua email và hiển thị trên giao diện người dùng. Đây là bằng chứng giao dịch hợp lệ.</li>
        <li><strong>Hoàn Tất:</strong> Giao dịch được coi là hoàn tất khi khách hàng nhận được mã vé.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">3. BẢO MẬT THANH TOÁN</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Minhouse.id.vn cam kết **không lưu trữ** trực tiếp thông tin thẻ ngân hàng hoặc mật khẩu của khách hàng.</li>
        <li>Tất cả các giao dịch thanh toán đều được thực hiện thông qua **cổng thanh toán an toàn (PCI DSS compliant)** với công nghệ mã hóa SSL/TLS tiên tiến.</li>
        <li>Khách hàng có trách nhiệm bảo mật thông tin tài khoản và mã OTP cá nhân.</li>
    </ul>
    
    <h2 class="text-xl font-semibold mb-3 text-indigo-700">4. XỬ LÝ SỰ CỐ THANH TOÁN</h2>
    
    <h3 class="text-lg font-medium mt-4 mb-2">4.1. Lỗi Thanh toán Thất bại</h3>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Nếu giao dịch báo **thất bại** nhưng tài khoản ngân hàng của khách hàng đã bị trừ tiền, vui lòng liên hệ Bộ phận Hỗ trợ của Minhouse ngay lập tức.</li>
        <li>Minhouse sẽ kiểm tra với cổng thanh toán. Trong trường hợp xác nhận tiền đã trừ nhưng vé chưa được cấp, chúng tôi sẽ thực hiện hoàn tiền trong vòng **7-15 ngày làm việc** (không tính thứ 7, Chủ Nhật và ngày lễ).</li>
    </ul>

    <h3 class="text-lg font-medium mt-4 mb-2">4.2. Thanh toán trùng lặp</h3>
    <p class="text-gray-700 mb-4 ml-6">Nếu khách hàng vô tình thanh toán cho cùng một đơn hàng nhiều lần, Minhouse sẽ hoàn trả các khoản tiền thanh toán trùng lặp sau khi xác minh.</p>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">5. GIÁ VÉ VÀ CÁC LOẠI PHÍ</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Giá vé cơ bản:</strong> Giá niêm yết trên website được tính bằng VNĐ (Việt Nam Đồng).</li>
        <li><strong>Phí giao dịch:</strong> Một số phương thức thanh toán có thể áp dụng phí xử lý giao dịch nhỏ. Phí này (nếu có) sẽ được thông báo rõ ràng trước khi khách hàng xác nhận thanh toán.</li>
        <li><strong>VAT:</strong> Giá vé đã bao gồm thuế Giá trị gia tăng (VAT) theo quy định hiện hành.</li>
    </ul>

    <hr class="my-6 border-accent">
    <p class="text-center font-semibold text-gray-800">
        Mọi thắc mắc về chính sách thanh toán, vui lòng liên hệ Bộ phận Chăm sóc khách hàng của Minhouse.id.vn.
    </p>
</section>

<footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">
        © 2025 Minhouse.id.vn – All rights reserved.
    </div>
</footer>

</body>
</html>