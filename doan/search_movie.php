<?php
require_once 'config/db.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    $sql = "SELECT id, title, genre, duration, price, description, poster 
            FROM movies";
} else {

    $safe_q = $conn->real_escape_string($q);

    $terms = preg_split('/\s+/', $safe_q);

    $like_conditions = [];
    foreach ($terms as $term) {
        $term = $conn->real_escape_string($term);
        $like_conditions[] = "(title LIKE '%$term%' OR description LIKE '%$term%')";
    }

    $like_clause = implode(' AND ', $like_conditions);

    $sql = "
        SELECT id, title, genre, duration, price, description, poster
        FROM movies
        WHERE MATCH(title, description) AGAINST('$safe_q' IN NATURAL LANGUAGE MODE)
           OR $like_clause
        ORDER BY title ASC
    ";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="movie-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer scroll-animate stagger-animation">';
        echo '  <a href="booking.php?movie_id=' . htmlspecialchars($row["id"]) . '" class="block h-48 overflow-hidden transition duration-700 ease-in-out">';
        if (!empty($row["poster"])) {
            echo '    <img src="' . htmlspecialchars($row["poster"]) . '" alt="' . htmlspecialchars($row["title"]) . '" class="object-cover w-full h-full hover:scale-105 transition-transform duration-500">';
        } else {
            echo '    <div class="bg-gradient-to-br from-purple-400 to-blue-500 w-full h-full flex items-center justify-center text-6xl text-white">🎬</div>';
        }
        echo '  </a>';

        echo '  <div class="p-4">';
        echo '    <a href="booking.php?movie_id=' . htmlspecialchars($row["id"]) . '" class="font-bold text-lg mb-2 block text-gray-800 hover:text-purple-600 transition-colors">';
        echo          htmlspecialchars($row["title"]);
        echo '    </a>';
        echo '    <p class="text-gray-600 text-sm mb-2">' . htmlspecialchars($row["description"]) . '</p>';
        echo '    <div class="flex justify-between items-center">';
        echo '      <span class="text-purple-600 font-semibold">' . htmlspecialchars($row["duration"]) . ' phút</span>';
        echo '      <span class="text-green-600 font-bold">' . number_format($row["price"], 0, ",", ".") . ' VNĐ</span>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo "<p class='text-center text-gray-500'>Không tìm thấy phim nào.</p>";
}
?>
