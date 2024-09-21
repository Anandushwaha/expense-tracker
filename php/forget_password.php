<?php
header('Content-Type: application/json'); // Set content to JSON

require 'vendor/autoload.php'; // Load Composer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('db-connection.php'); // Modify as per your DB setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Email exists, generate reset code
        $reset_code = rand(100000, 999999); // Generate a 6-digit random code
        $reset_code_expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Code valid for 1 hour
    
        // Store the reset code and expiry in the database
        $update_query = "UPDATE users SET reset_code = ?, reset_code_expiry = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $reset_code, $reset_code_expiry, $email);
        $update_stmt->execute();
    
        // Now configure and send the email using PHPMailer
        $mail = new PHPMailer(true);
    
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username   = 'expensetracker366@gmail.com'; // Your email address
            $mail->Password   = 'ysue kqhq ybhz pihv'; // App password from Google

            

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable encryption
            $mail->Port = 587; // TCP port for STARTTLS
    
            // Recipients
            $mail->setFrom('expensetracker@gmail.com', 'From expense tracker team'); // Sender's email
            $mail->addAddress($email); // Add recipient's email (user's email)
    
            // Content
            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $reset_link = "http://localhost/expense-tracker/html/reset-password.html?reset_code=" . urlencode($reset_code);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";

            $mail->AltBody = "Your reset code is: " . $reset_code . ". This code will expire in 1 hour."; // Plain text version
    
            $mail->send();
            echo json_encode([
                'status' => 'success',
                'message' => 'A reset code has been sent to your email.',
                'redirect' => $reset_link // Include the reset link for redirection
           
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email not found.'
        ]);
    }
    
}
?>
