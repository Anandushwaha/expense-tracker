<?php
header('Content-Type: application/json'); // Set the content type to JSON

include('db-connection.php'); // Modify according to your DB connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reset_code = $_POST['reset_code'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the new password

    // Validate the reset code
    $query = "SELECT email, reset_code_expiry FROM users WHERE reset_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $reset_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the reset code has expired
        if (strtotime($user['reset_code_expiry']) < time()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Reset code has expired.'
            ]);
            exit();
        }

        // Update the password
        $update_query = "UPDATE users SET password = ?, reset_code = NULL, reset_code_expiry = NULL WHERE reset_code = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $hashed_password, $reset_code);
        if ($update_stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Password has been reset successfully.',
                'redirect' => 'login.html' 
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update password.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid reset code.'
        ]);
    }
}
?>
