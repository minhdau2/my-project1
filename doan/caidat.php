<?php
session_start();
include_once("config/db.php");

// Giả lập lấy dữ liệu cài đặt hiện tại từ DB
// (bạn có thể thay bằng truy vấn thực tế từ bảng settings)
$currentSettings = [
    "site_name" => "CineStar Booking",
    "email" => "support@cinestar.vn",
    "phone" => "0909 123 456",
    "address" => "123 Nguyễn Huệ, Q1, TP.HCM",
    "timezone" => "Asia/Ho_Chi_Minh",
    "theme_color" => "indigo",
    "dark_mode" => false,
    "email_notification" => true
];

// Xử lý lưu cài đặt
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Cập nhật dữ liệu (giả lập)
    $currentSettings = array_merge($currentSettings, $_POST);
    // Nếu bạn có DB thật, ở đây bạn UPDATE bảng settings
    $message = "Cập nhật cài đặt thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cài đặt hệ thống</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-600">⚙️ Cài đặt hệ thống</h1>
        <a href="dashboard.php" class="text-sm text-indigo-500 hover:underline">← Quay lại Dashboard</a>
      </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto px-6 py-8 space-y-8">
      
      <!-- Thông báo -->
      <?php if (!empty($message)): ?>
      <div class="bg-emerald-100 text-emerald-700 px-4 py-3 rounded-lg shadow">
        ✅ <?= htmlspecialchars($message) ?>
      </div>
      <?php endif; ?>

      <!-- Biểu mẫu cài đặt -->
      <form method="POST" class="bg-white p-6 rounded-2xl shadow space-y-8">
        
        <!-- THÔNG TIN CHUNG -->
        <section>
          <h2 class="text-xl font-semibold text-indigo-600 mb-4">Thông tin chung</h2>
          <div class="grid md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm text-gray-600 mb-1">Tên website</label>
              <input name="site_name" value="<?= $currentSettings['site_name'] ?>" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400" />
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Email liên hệ</label>
              <input name="email" value="<?= $currentSettings['email'] ?>" type="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400" />
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Số điện thoại</label>
              <input name="phone" value="<?= $currentSettings['phone'] ?>" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400" />
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Địa chỉ</label>
              <input name="address" value="<?= $currentSettings['address'] ?>" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400" />
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Múi giờ</label>
              <select name="timezone" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
                <option value="Asia/Ho_Chi_Minh" <?= $currentSettings['timezone'] == "Asia/Ho_Chi_Minh" ? "selected" : "" ?>>Asia/Ho_Chi_Minh (VN)</option>
                <option value="Asia/Tokyo">Asia/Tokyo</option>
                <option value="Asia/Singapore">Asia/Singapore</option>
              </select>
            </div>
          </div>
        </section>

        <hr class="border-gray-200">

        <!-- GIAO DIỆN -->
        <section>
          <h2 class="text-xl font-semibold text-indigo-600 mb-4">Cài đặt giao diện</h2>
          <div class="grid md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm text-gray-600 mb-1">Màu chủ đạo</label>
              <select name="theme_color" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
                <option value="indigo" <?= $currentSettings['theme_color'] == "indigo" ? "selected" : "" ?>>Xanh Indigo</option>
                <option value="emerald" <?= $currentSettings['theme_color'] == "emerald" ? "selected" : "" ?>>Xanh Ngọc</option>
                <option value="rose" <?= $currentSettings['theme_color'] == "rose" ? "selected" : "" ?>>Hồng Đậm</option>
                <option value="amber" <?= $currentSettings['theme_color'] == "amber" ? "selected" : "" ?>>Vàng Cam</option>
              </select>
            </div>
            <div class="flex items-center space-x-3">
              <input type="checkbox" name="dark_mode" id="dark_mode" value="1" <?= $currentSettings['dark_mode'] ? "checked" : "" ?> class="h-5 w-5 text-indigo-600 rounded">
              <label for="dark_mode" class="text-sm text-gray-700">Bật chế độ tối</label>
            </div>
          </div>
        </section>

        <hr class="border-gray-200">

        <!-- TÀI KHOẢN ADMIN -->
        <section>
          <h2 class="text-xl font-semibold text-indigo-600 mb-4">Tài khoản quản trị</h2>
          <div class="grid md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm text-gray-600 mb-1">Mật khẩu mới</label>
              <input type="password" name="new_password" placeholder="Nhập mật khẩu mới..." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Xác nhận mật khẩu</label>
              <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu..." class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400">
            </div>
          </div>
        </section>

        <hr class="border-gray-200">

        <!-- THÔNG BÁO -->
        <section>
          <h2 class="text-xl font-semibold text-indigo-600 mb-4">Cài đặt thông báo & email</h2>
          <div class="flex items-center space-x-3">
            <input type="checkbox" name="email_notification" id="email_notification" value="1" <?= $currentSettings['email_notification'] ? "checked" : "" ?> class="h-5 w-5 text-indigo-600 rounded">
            <label for="email_notification" class="text-sm text-gray-700">Gửi email tự động khi có người đặt vé</label>
          </div>
        </section>

        <div class="text-right">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg shadow transition">
            💾 Lưu cài đặt
          </button>
        </div>
      </form>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-center py-4 text-sm text-gray-500">
      © <?= date("Y") ?> Movie Admin Dashboard — Thiết kế với <span class="font-semibold text-indigo-600">Tailwind CSS</span>
    </footer>
  </div>
</body>
</html>
