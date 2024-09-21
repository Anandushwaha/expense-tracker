<?php
// Simulate a POST request
include 'db-connection.php';

$_POST['email'] = 'test@example.com'; // Use an email address you want to test with

// Include your forget_password.php script
include 'forget_password.php';
?>
