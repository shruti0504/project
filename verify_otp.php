<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Combine OTP inputs into a single string
    $entered_otp = implode('', $_POST['otp']);

    if ($entered_otp == $_SESSION['otp']) {
        // If OTP is correct, redirect to the reset password page
        header("Location: reset_password.html");
        exit();
    } else {
        // If OTP is incorrect, show an error message
        echo "Invalid OTP. Please try again.";
    }
}
?>
