<?php
session_start();
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit();
}

$user = $_SESSION['username'];

$month = $_GET['month'];
$year = $_GET['year'];

$sql = "SELECT start_date, period_length FROM cycles WHERE username='$user' AND MONTH(start_date) = $month AND YEAR(start_date) = $year";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['start_date' => "$year-$month-01", 'period_length' => 0]);
}

$conn->close();
?>
