<?php
// fetch_data.php

// Start session
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "symptom_checker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the session is set
if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit();
}

$user = $conn->real_escape_string($_SESSION['username']);

// Fetch blood flow data from the database
$sql = "SELECT flow, color, MONTHNAME(created_at) as month FROM user_symptoms WHERE username=? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'month' => $row['month'],
            'flow' => (int)$row['flow'],
            'color' => $row['color']
        ];
    }
}

$stmt->close();
$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
