<?php

session_start();
require_once 'config/db.php';

if (isset($_POST['import_staff'])) {
    
    if ($_FILES['csv_file']['error'] == 0) {
        $name = $_FILES['csv_file']['name'];
        $ext = strtolower(end(explode('.', $name)));
        $tmpName = $_FILES['csv_file']['tmp_name'];

        if ($ext === 'csv') {
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                fgetcsv($handle);

                $success = 0;
                $fail = 0;

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    
                    $name = trim($data[0] ?? '');
                    $email = trim($data[1] ?? '');
                    $phone = trim($data[2] ?? '');
                    $role = trim($data[3] ?? 'Nhân viên'); 
                    $raw_pass = trim($data[4] ?? '123456'); 

                    if (!empty($name) && !empty($email)) {
                        $check = $conn->prepare("SELECT id FROM admin WHERE email = ?");
                        $check->bind_param("s", $email);
                        $check->execute();
                        $check->store_result();

                        if ($check->num_rows == 0) {
                            $hashed_pass = password_hash($raw_pass, PASSWORD_DEFAULT); 

                            $stmt = $conn->prepare("INSERT INTO admin (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssss", $name, $email, $phone, $role, $hashed_pass);
                            
                            if ($stmt->execute()) {
                                $success++;
                            } else {
                                $fail++;
                            }
                            $stmt->close();
                        } else {
                            $fail++; 
                        }
                        $check->close();
                    }
                }
                fclose($handle);
                $_SESSION['success'] = "Đã nhập: $success nhân viên. Bỏ qua: $fail (do lỗi hoặc trùng Email).";
            } else {
                $_SESSION['error'] = "Không mở được file.";
            }
        } else {
            $_SESSION['error'] = "Vui lòng chọn file .CSV";
        }
    } else {
        $_SESSION['error'] = "Lỗi tải file.";
    }
}

header("Location: dashboard.php");
exit();
?>