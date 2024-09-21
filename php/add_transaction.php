<?php

include 'db-connection.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = sanitize_input($_POST['type']);
    $amount = sanitize_input($_POST['amount']);
    $category = sanitize_input($_POST['category']);
    $description = sanitize_input($_POST['description']);

    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Insert into transactions table with user_id
    $sql = "INSERT INTO transactions (type, amount, category, description, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssi", $type, $amount, $category, $description, $user_id); // Bind the user ID

    if ($stmt->execute()) {
        // Success
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
