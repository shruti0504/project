<?php
// fetch_reviews.php

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare product_id from GET parameter
$product_id = $_GET['product_id'];

// SQL query to fetch reviews for a specific product_id
$sql = "SELECT * FROM reviews WHERE product_id = '$product_id' ORDER BY created_at DESC";

$result = $conn->query($sql);

$reviews = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(array('reviews' => $reviews));
?>
