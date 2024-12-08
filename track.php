<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer libraries
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $period_length = $_POST['period_length'];
    $cycle_length = $_POST['cycle_length'];
    $email = $_POST['email'];
    $ovulation_date = $_POST['ovulation_date'];
    $next_period_date = $_POST['next_period_date'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "menstrual_cycle";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
    }
    // Retrieve username from session
    $session_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    // Prepare and execute SQL insert statement
    $stmt = $conn->prepare("INSERT INTO cycles (start_date, period_length, cycle_length, email, ovulation_date, next_period_date, username) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameters: s = string, i = integer
    $stmt->bind_param("ssissss", $start_date, $period_length, $cycle_length, $email, $ovulation_date, $next_period_date, $session_username);//last modifiesd

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Your data has been recorded"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
