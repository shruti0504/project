<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    if ($new_password === $confirm_password) {
        // Hash the new password using bcrypt
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the hashed password in the database
        $sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            echo "Password updated successfully.";
            session_destroy();
            header("Location:login.html");  // Redirect to login page
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "Passwords do not match. Please try again.";
    }
}

$conn->close();
?>
