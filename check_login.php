<?php
session_start();
header('Content-Type: application/json');

$response = ['loggedIn' => isset($_SESSION['username'])];

echo json_encode($response);
?>
