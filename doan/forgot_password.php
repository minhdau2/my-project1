<?php
require 'config/db.php'; 
require 'vendor1/autoload.php'; 

use SendGrid\Mail\Mail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $stmt_del = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt_del->bind_param("s", $email);
        $stmt_del->execute();

        $stmt_ins = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt_ins->bind_param("sss", $email, $token, $expires);
        $stmt_ins->execute();

        $resetLink = "https://minhouse.id.vn/reset_password.php?token=$token";
        $APIKey="";

        $sendgrid = new \SendGrid(''); // Thay bằng API key của bạn
        $mail = new Mail();
        $mail->setFrom("no-reply@minhouse.id.vn", "Minhouse Support");
        $mail->setSubject("Đặt lại mật khẩu của bạn");
        $mail->addTo($email);
        $mail->addContent(
            "text/html",
            "
            <p>Xin chào,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu. Nhấn vào liên kết sau để đổi mật khẩu:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p><b>Lưu ý:</b> Liên kết này sẽ hết hạn sau 30 phút.</p>
            <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
            "
        );

        try {
            $response = $sendgrid->send($mail);
        } catch (Exception $e) {
            echo "❌ Lỗi khi gửi email: " . $e->getMessage();
        }
    } else {
        echo "⚠️ Không tìm thấy tài khoản nào với email này.";
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quên mật khẩu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">

  <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 w-[90%] max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-3 text-center">🔒 Quên mật khẩu</h2>
    <p class="text-gray-600 text-sm text-center mb-6">
      Nhập email bạn đã dùng để đăng ký. Chúng tôi sẽ gửi liên kết đặt lại mật khẩu đến email của bạn.
    </p>

    <form  method="POST" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ email</label>
        <input type="email" id="email" name="email" required
               placeholder="example@gmail.com"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
      </div>

      <button type="submit"
              class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300 shadow-md">
        Gửi liên kết đặt lại
      </button>
    </form>

    <div class="text-center mt-6">
      <a href="index.php"
         class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
         ← Quay lại trang đăng nhập
      </a>
    </div>
  </div>

  <script>
    document.body.classList.add("opacity-0", "transition", "duration-700");
    window.addEventListener("load", () => {
      document.body.classList.remove("opacity-0");
    });
  </script>

</body>
</html>
