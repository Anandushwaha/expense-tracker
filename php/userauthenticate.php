<?php
// user_auth.php

// Include the database connection file
include 'db-connection.php';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sign Up
    if (isset($_POST['signUp'])) {
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $password = password_hash(sanitize_input($_POST['password']), PASSWORD_BCRYPT);

        if (!empty($name) && !empty($email) && !empty($password)) {
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Email already in use.";
            } else {
                $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $name, $email, $password);

                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    header("Location: ../html/dashboard.html");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            $stmt->close();
        } else {
            echo "Please fill in all fields.";
        }
    }

    // Login
    if (isset($_POST['login'])) {
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $sql = "SELECT id, name, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $name, $hashed_password);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    // Password is correct, login successful
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    header("Location: ../html/dashboard.html");
                    exit();
                } else {
                    // Redirect with error message for wrong password
                    header("Location: ../html/login.html?error=Invalid+password");
                    exit();
                }
            } else {
                // Redirect with error message for wrong email
                header("Location: ../html/login.html?error=No+account+found+with+that+email");
                exit();
            }
            $stmt->close();
        } else {
            // Redirect with error message for missing fields
            header("Location: ../html/login.html?error=Please+fill+in+all+fields");
            exit();
        }
    }
}
$conn->close();
?>
