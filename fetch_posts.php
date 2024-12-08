<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch posts
$sql = "SELECT title, content, username, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($posts);

$conn->close();
?>
