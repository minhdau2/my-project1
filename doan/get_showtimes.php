<?php
$conn = new mysqli('localhost', 'uloehxcu_cinema', 'Minhdau22@', 'uloehxcu_cinema');
if ($conn->connect_error) die("Lỗi kết nối: " . $conn->connect_error);

if (isset($_GET['movieId']) && isset($_GET['date'])) {
    $movieId = intval($_GET['movieId']);
    $date = $_GET['date'];

    $sql = "SELECT id, time 
            FROM showtimes 
            WHERE movie_id = $movieId 
              AND date = '$date'";
    $result = $conn->query($sql);

    echo "<option value=''>Chọn suất chiếu</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['time']}</option>";
    }
}
$conn->close();
?>
