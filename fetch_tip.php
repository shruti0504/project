<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, tip_text FROM exercise_tips";
$result = $conn->query($sql);

$tips = array();
while($row = $result->fetch_assoc()) {
    $tips[] = $row;
}

$conn->close();

echo json_encode($tips);
?>
