<?php
session_start();

$response = [];

if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
    $response['name'] = $_SESSION['user_name'];
    $response['email'] = $_SESSION['user_email'];
}

echo json_encode($response);
?>
