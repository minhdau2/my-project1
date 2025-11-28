<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chính sách Bảo mật | Minhouse.id.vn</title>
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
    <h1 class="text-3xl font-bold text-gray-900 mb-6">🔒 CHÍNH SÁCH BẢO MẬT THÔNG TIN</h1>

    <p class="text-gray-700 leading-relaxed mb-4">
        Minhouse.id.vn cam kết bảo vệ tuyệt đối quyền riêng tư và thông tin cá nhân của khách hàng. 
        Chính sách này mô tả cách chúng tôi thu thập, sử dụng và bảo mật thông tin của bạn.
    </p>

    <hr class="my-6">

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">1. LOẠI THÔNG TIN CHÚNG TÔI THU THẬP</h2>
    <p class="text-gray-700 mb-2">Chúng tôi thu thập các loại thông tin sau từ người dùng khi đăng ký hoặc thực hiện giao dịch:</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Thông tin cá nhân:</strong> Tên đầy đủ, địa chỉ email, số điện thoại, ngày sinh (dùng để xác minh và quản lý tài khoản).</li>
        <li><strong>Thông tin giao dịch:</strong> Lịch sử đặt vé, mã vé, số tiền giao dịch, phương thức thanh toán đã chọn (lưu ý: chúng tôi **không lưu trữ** chi tiết thẻ tín dụng/thẻ ngân hàng của bạn).</li>
        <li><strong>Thông tin kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt, thời gian truy cập, dữ liệu sử dụng qua Cookies (dùng để cải thiện trải nghiệm người dùng).</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">2. MỤC ĐÍCH SỬ DỤNG THÔNG TIN</h2>
    <p class="text-gray-700 mb-2">Thông tin của bạn được sử dụng cho các mục đích chính sau:</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Thực hiện và xác nhận các đơn hàng đặt vé xem phim của bạn.</li>
        <li>Gửi mã vé, hóa đơn và các thông báo quan trọng liên quan đến giao dịch.</li>
        <li>Quản lý tài khoản khách hàng, tích điểm thành viên, và lịch sử đặt vé.</li>
        <li>Cung cấp thông tin về các chương trình khuyến mãi, phim mới, hoặc ưu đãi đặc biệt (chỉ khi bạn đồng ý nhận).</li>
        <li>Nâng cao chất lượng dịch vụ, tùy chỉnh giao diện và nội dung trang web.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">3. BẢO MẬT VÀ THỜI GIAN LƯU TRỮ</h2>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li><strong>Bảo mật:</strong> Minhouse.id.vn áp dụng các biện pháp bảo mật tiêu chuẩn (Mã hóa SSL, tường lửa) để bảo vệ thông tin cá nhân khỏi truy cập trái phép, sử dụng hoặc tiết lộ.</li>
        <li><strong>Mật khẩu:</strong> Mật khẩu của bạn được mã hóa một chiều (hashing) và chúng tôi không thể truy cập được mật khẩu gốc.</li>
        <li><strong>Lưu trữ:</strong> Thông tin cá nhân sẽ được lưu trữ cho đến khi khách hàng yêu cầu hủy bỏ hoặc tự hủy tài khoản. Chúng tôi có thể giữ lại dữ liệu giao dịch trong một khoảng thời gian nhất định để phục vụ mục đích thuế và pháp lý.</li>
    </ul>
    
    <h2 class="text-xl font-semibold mb-3 text-indigo-700">4. CHIA SẺ THÔNG TIN CÁ NHÂN</h2>
    <p class="text-gray-700 mb-2">Chúng tôi cam kết **không bán, cho thuê hoặc tiết lộ** thông tin cá nhân của bạn cho bên thứ ba, ngoại trừ các trường hợp sau:</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Chia sẻ với **đối tác rạp chiếu phim** để xác nhận và cấp vé cho bạn.</li>
        <li>Chia sẻ với **cổng thanh toán** (ví điện tử, ngân hàng) để xử lý giao dịch.</li>
        <li>Khi có yêu cầu hợp pháp từ cơ quan nhà nước có thẩm quyền.</li>
    </ul>

    <h2 class="text-xl font-semibold mb-3 text-indigo-700">5. QUYỀN CỦA KHÁCH HÀNG</h2>
    <p class="text-gray-700 mb-2">Bạn có quyền:</p>
    <ul class="list-disc ml-6 text-gray-700 mb-4 space-y-1">
        <li>Truy cập và tự chỉnh sửa thông tin cá nhân của mình bất cứ lúc nào qua trang hồ sơ (Profile).</li>
        <li>Yêu cầu Minhouse.id.vn cung cấp bản sao thông tin cá nhân mà chúng tôi đang lưu trữ.</li>
        <li>Yêu cầu xóa tài khoản và thông tin cá nhân (phải tuân thủ các quy định pháp lý về dữ liệu giao dịch).</li>
        <li>Từ chối nhận các email hoặc tin nhắn quảng cáo/tiếp thị.</li>
    </ul>

    <hr class="my-6 border-accent">
    <p class="text-center font-semibold text-gray-800">
        Nếu có bất kỳ thắc mắc hoặc cần hỗ trợ về Chính sách Bảo mật, vui lòng liên hệ với chúng tôi qua email: support@minhouse.id.vn.
    </p>
</section>

<footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center">
        © 2025 Minhouse.id.vn – All rights reserved.
    </div>
</footer>

</body>
</html>