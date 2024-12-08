<?php
session_start();

$response = array();

if (isset($_SESSION['username'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    $response['logged_out'] = true;
} else {
    $response['logged_out'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
