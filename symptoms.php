<?php

// Start the session to access session variables
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "symptom_checker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and username is set in session
if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

// Retrieve username from session
$username = $_SESSION['username'];


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected symptoms for each category
    $flow = isset($_POST['flow']) ? implode(',', $_POST['flow']) : '';
    $blood_color = isset($_POST['color']) ? implode(',', $_POST['color']) : '';
    $cravings = isset($_POST['cravings']) ? implode(',', $_POST['cravings']) : '';

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO user_symptoms (username, color, cravings, flow) VALUES (?, ?, ?, ?)");
    
    // Check if prepare was successful
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ssss", $username, $blood_color, $cravings, $flow);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect or show a success message
    echo "Symptoms recorded successfully!";
}
?>
