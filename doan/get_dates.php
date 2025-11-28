<?php
$conn = new mysqli('localhost', 'uloehxcu_cinema', 'Minhdau22@', 'uloehxcu_cinema');
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

if (isset($_GET['movieId'])) {
    $movieId = intval($_GET['movieId']);
    $sql = "SELECT DISTINCT date 
            FROM showtimes 
            WHERE movie_id = $movieId 
            ORDER BY date";
    $result = $conn->query($sql);

    echo "<option value=''>Chọn ngày chiếu</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['date']}'>{$row['date']}</option>";
    }
}
$conn->close();
?>
