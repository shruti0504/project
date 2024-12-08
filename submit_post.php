
<?php
//community form sathi php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to submit a post.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['postTitle']);
    $content = $conn->real_escape_string($_POST['postContent']);
    $username = $_SESSION['username'];

    $sql = "INSERT INTO posts (title, content, username) VALUES ('$title', '$content', '$username')";

    if ($conn->query($sql) === TRUE) {
        echo "New post created successfully";
        // Redirect or handle success
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
