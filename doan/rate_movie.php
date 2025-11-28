<?php
include 'config/db.php';

$movie_id = intval($_POST['movie_id']);
$stars = intval($_POST['stars']);
$comment = $conn->real_escape_string($_POST['comment']);

if ($stars >= 1 && $stars <= 5) {
    $conn->query("INSERT INTO ratings (movie_id, stars, comment) VALUES ($movie_id, $stars, '$comment')");
}

header("Location: movie_detail.php?id=$movie_id");
exit;
?>
