<?php
// submit_review.php

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

// Prepare variables
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

// SQL query to insert review
$sql = "INSERT INTO reviews (product_id, rating, review, created_at) VALUES ('$product_id', '$rating', '$review', NOW())";

if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
