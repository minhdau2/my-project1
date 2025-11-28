<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều Khoản Giao Dịch | Minhouse.id.vn</title>
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
    <h1 class="text-3xl font-bold text-gray-900 mb-6">📄 ĐIỀU KHOẢN GIAO DỊCH</h1>

    <p class="text-gray-700 leading-relaxed mb-4">
        Việc truy cập, sử dụng trang web, hoặc đặt vé xem phim qua <strong>minhouse.id.vn</strong> đồng nghĩa với việc bạn 
        <strong>hoàn toàn chấp nhận</strong> và đồng ý tuân thủ tất cả các điều khoản giao dịch dưới đây.
    </p>

    <hr class="my-6">

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">1. 🌐 CHẤP THUẬN ĐIỀU KHOẢN</h2>
    <p class="text-gray-700 mb-2">Chào mừng bạn đến với CinemaBooking.</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Nguyên tắc:</strong> Bạn phải chấp nhận toàn bộ các điều khoản này để sử dụng dịch vụ.</li>
        <li><strong>Cập nhật:</strong> Minhouse có quyền thay đổi, bổ sung hoặc loại bỏ bất kỳ điều khoản nào bất cứ lúc nào mà không cần báo trước.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">2. 🎟️ QUY TRÌNH ĐẶT VÉ VÀ THANH TOÁN</h2>
    
    <h3 class="text-lg font-medium mt-4 mb-2">2.1. Đặt vé</h3>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Khách hàng có trách nhiệm **kiểm tra kỹ lưỡng** các thông tin: Tên phim, Suất chiếu, Giờ chiếu, Địa điểm, Số lượng vé và Vị trí ghế ngồi.</li>
        <li>Giao dịch đã hoàn tất và được xác nhận là **cuối cùng** và không thể thay đổi hoặc hủy bỏ (Trừ các trường hợp đặc biệt tại Mục 3).</li>
    </ul>
    
    <h3 class="text-lg font-medium mt-4 mb-2">2.2. Giá vé và Phụ phí</h3>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Giá vé có thể thay đổi tùy theo thời điểm, loại rạp và chương trình khuyến mãi.</li>
        <li>Giá vé ưu đãi (Học sinh/Sinh viên/Người cao tuổi) chỉ áp dụng khi **xuất trình giấy tờ tùy thân hợp lệ** tại quầy soát vé.</li>
    </ul>
    
    <h3 class="text-lg font-medium mt-4 mb-2">2.3. Thanh toán</h3>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Hỗ trợ đa dạng phương thức thanh toán (Thẻ, Ví điện tử...).</li>
        <li>Nếu giao dịch **bị lỗi** nhưng tài khoản bị trừ tiền, CinemaBooking sẽ hoàn tiền trong vòng **7-15 ngày làm việc** sau khi xác nhận.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">3. 🔄 CHÍNH SÁCH ĐỔI/TRẢ VÉ VÀ HOÀN TIỀN</h2>
    
    <h3 class="text-lg font-medium mt-4 mb-2">3.1. Hủy bỏ từ Khách hàng</h3>
    <p class="text-red-600 font-bold ml-6 mb-2">**Vé đã mua không được hoàn lại, hủy bỏ, hoặc đổi sang suất chiếu/ghế ngồi khác.**</p>

    <h3 class="text-lg font-medium mt-4 mb-2">3.2. Hủy bỏ từ Rạp chiếu</h3>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Trong trường hợp suất chiếu bị **hủy bỏ** (do lỗi kỹ thuật hoặc sự cố bất khả kháng), CinemaBooking sẽ thông báo cho khách hàng.</li>
        <li>Khách hàng sẽ được **hoàn lại 100% giá trị vé đã thanh toán** hoặc được hỗ trợ đổi sang suất chiếu khác.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">4. ⚠️ TRÁCH NHIỆM VÀ KHUYẾN CÁO</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Phân loại phim:</strong> Khách hàng có trách nhiệm tuân thủ quy định về phân loại phim (T18, T16, T13...) theo pháp luật. Rạp có quyền từ chối cho vào phòng chiếu nếu không đủ tuổi.</li>
        <li><strong>Chất lượng rạp:</strong> CinemaBooking là bên cung cấp dịch vụ đặt vé. Mọi vấn đề về chất lượng dịch vụ tại rạp (âm thanh, chỗ ngồi, vệ sinh...) thuộc trách nhiệm của rạp chiếu phim.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">5. ⚖️ GIẢI QUYẾT TRANH CHẤP</h2>
    <p class="text-gray-700 mb-4">
        Mọi tranh chấp phát sinh sẽ được giải quyết trước hết bằng phương pháp **thương lượng thiện chí**. Nếu không thể giải quyết, tranh chấp sẽ được đưa ra Tòa án có thẩm quyền tại Việt Nam để giải quyết theo quy định của pháp luật.
    </p>

    <hr class="my-6 border-accent">
    <p class="text-center font-semibold text-gray-800">
        Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của CinemaBooking!
    </p>
</section>

<footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">
        © 2025 Minhouse.id.vn – All rights reserved.
    </div>
</footer>

</body>
</html>