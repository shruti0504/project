<?php
// Add this at the beginning of your PHP files for error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$response = array();

if (isset($_SESSION['username'])) {
    $response['username'] = $_SESSION['username'];
} else {
    $response['username'] = null;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
