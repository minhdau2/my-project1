<?php

ob_start();

require_once 'config/db.php';

$conn->set_charset("utf8mb4");

if (ob_get_length()) ob_end_clean();

$filename = "DanhSachNguoiDung_" . date('Y-m-d') . ".csv";

header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputs($output, "\xEF\xBB\xBF");

fputcsv($output, array('ID', 'Tên người dùng', 'Email', 'Số điện thoại', 'Trạng thái'));

$sql = "SELECT id, name, email, phone, status FROM users ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $statusText = ($row['status'] == 1) ? 'Hoạt động' : 'Đã khóa';

        $phone = $row['phone']; 

        $lineData = array(
            $row['id'], 
            $row['name'], 
            $row['email'], 
            $phone, 
            $statusText
        );
        fputcsv($output, $lineData);
    }
}

fclose($output);
exit();
?>