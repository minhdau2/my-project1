<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


if (is_array($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'] ?? 0;
} else {
    $user_id = $_SESSION['user'];
}

if ($user_id == 0) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $msg_type = "success";
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $msg_type = "error";
    unset($_SESSION['error']);
}

$stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hồ sơ cá nhân | CinemaBooking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="styles.css" />
</head>

<body class="bg-gray-50 min-h-screen font-[Poppins] flex flex-col overflow-x-hidden">

  <nav class="gradient-bg text-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
          <div class="flex items-center">
            <h1 class="text-xl md:text-2xl font-bold">🎬 CinemaBooking</h1>
          </div>

          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a href="index.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Trang Chủ</a>
              <a href="movies.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Phim</a>
              <a href="booking.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Đặt Vé</a>
              <a href="history.php" class="nav-link px-3 py-2 rounded-md text-sm font-medium hover:bg-white hover:bg-opacity-20 transition-colors">Lịch Sử</a>
            </div>
          </div>

          <div class="hidden md:flex items-center space-x-4">
              <div class="relative group">
                <button class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    Xin chào, <?= htmlspecialchars($user['name']) ?> <i class="fa-solid fa-caret-down"></i>
                </button>
                <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50 text-gray-800">
                    <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100 font-bold text-purple-600">Thông tin cá nhân</a>
                    <a href="history.php" class="block px-4 py-2 hover:bg-gray-100">Lịch sử đặt vé</a>
                    <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Đăng xuất</a>
                </div>
              </div>
          </div>

          <div class="-mr-2 flex md:hidden">
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" type="button" class="bg-purple-600 inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-purple-700 focus:outline-none">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div id="mobile-menu" class="hidden md:hidden bg-white text-gray-800 border-t shadow-xl">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Trang Chủ</a>
          <a href="movies.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Phim</a>
          <a href="booking.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Đặt Vé</a>
          <a href="history.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100">Lịch Sử</a>
        </div>
        <div class="pt-4 pb-4 border-t border-gray-200">
            <div class="px-5 flex items-center">
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-purple-700"><?= htmlspecialchars($user['name']) ?></div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-100">Đăng xuất</a>
            </div>
        </div>
      </div>
  </nav>

  <main class="flex-1 px-4 pb-10"> <section class="max-w-4xl mx-auto mt-8 md:mt-16 bg-white rounded-2xl shadow-lg overflow-hidden">
      
      <div class="bg-gradient-to-r from-purple-500 to-blue-500 p-6 md:p-10 text-center text-white relative">
          <div class="w-24 h-24 md:w-32 md:h-32 mx-auto bg-white rounded-full p-1 shadow-lg relative z-10">
             <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=random&size=128" 
                  alt="Avatar" class="w-full h-full rounded-full object-cover">
          </div>
          <h2 class="text-2xl md:text-3xl font-bold mt-4"><?= htmlspecialchars($user['name']) ?></h2>
          <p class="opacity-90 text-sm md:text-base"><?= htmlspecialchars($user['email']) ?></p>
      </div>

      <div class="p-6 md:p-10">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            <h2 class="text-xl font-bold text-gray-700 hidden md:block">Thông tin chi tiết</h2>
            
            <div class="flex w-full md:w-auto gap-2">
                <button type="button" onclick="openChangePassModal()" 
                        class="flex-1 md:flex-none justify-center text-white bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg shadow transition flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-key"></i> <span class="hidden sm:inline">Đổi mật khẩu</span><span class="sm:hidden">Đổi MK</span>
                </button>

                <button type="button" id="btnEnableEdit" onclick="enableEditing()" 
                        class="flex-1 md:flex-none justify-center text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg border border-blue-200 transition flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa
                </button>
            </div>
        </div>

        <?php if ($msg != ""): ?>
            <div class="<?= $msg_type == 'success' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' ?> border px-4 py-3 rounded-lg mb-6 text-center text-sm">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="id" value="<?= $user_id ?>">

            <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Họ và Tên</label>
            <input type="text" name="name" id="inputName" 
                    value="<?= htmlspecialchars($user['name']) ?>"
                    readonly
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none transition" />
            </div>

            <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email</label>
            <input type="email" name="email" 
                    value="<?= htmlspecialchars($user['email']) ?>"
                    readonly disabled
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" />
            </div>

            <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Số điện thoại</label>
            <input type="tel" name="phone" id="inputPhone" 
                    value="<?= htmlspecialchars($user['phone']) ?>"
                    readonly
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 focus:outline-none transition" />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Ngày tham gia</label>
                <input type="text" value="<?= date('d/m/Y', strtotime($user['created_at'])) ?>" readonly disabled
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" />
            </div>

            <div id="actionButtons" class="md:col-span-2 flex flex-col-reverse md:flex-row justify-end gap-3 hidden pt-4 border-t border-gray-100">
            <button type="button" onclick="cancelEditing()" 
                    class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg transition">
                Hủy bỏ
            </button>
            <button type="submit" 
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
            </div>

        </form>
      </div>
    </section>
  </main>

  <div id="changePassModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl p-6 md:p-8 max-w-md w-full shadow-2xl relative transform transition-all scale-100">
        
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h3 class="text-lg md:text-xl font-bold text-gray-800">🔒 Đổi Mật Khẩu</h3>
            <button onclick="closeChangePassModal()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
        </div>

        <form action="change_password.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" required placeholder="••••••"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                <input type="password" name="new_password" required placeholder="••••••" minlength="6"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" required placeholder="••••••" minlength="6"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none transition">
            </div>

            <div class="flex flex-col md:flex-row justify-end gap-3 pt-4">
                <button type="button" onclick="closeChangePassModal()" class="w-full md:w-auto px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">Hủy</button>
                <button type="submit" class="w-full md:w-auto px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg shadow transition">Cập nhật</button>
            </div>
        </form>
    </div>
  </div>

  <footer class="text-center text-sm text-gray-500 py-8 border-t bg-white">
    © 2025 CinemaBooking.
  </footer>

  <script>
    function openChangePassModal() {
        const modal = document.getElementById('changePassModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeChangePassModal() {
        const modal = document.getElementById('changePassModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    const btnEnable = document.getElementById('btnEnableEdit');
    const actionBtns = document.getElementById('actionButtons');
    const editableInputs = [
        document.getElementById('inputName'),
        document.getElementById('inputPhone')
    ];

    function enableEditing() {
        btnEnable.classList.add('hidden');
        actionBtns.classList.remove('hidden');
        editableInputs.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.remove('bg-gray-50', 'text-gray-500', 'border-gray-200'); 
            input.classList.add('bg-white', 'text-gray-900', 'border-blue-300', 'focus:ring-2', 'focus:ring-blue-400'); 
        });
        editableInputs[0].focus();
    }

    function cancelEditing() {
        window.location.reload();
    }
  </script>
</body>
</html>