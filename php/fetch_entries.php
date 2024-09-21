<?php
include 'db-connection.php';

header('Content-Type: application/json');

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch all entries for the user
$sql = "SELECT type, amount, category FROM transactions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Only bind the user_id

$stmt->execute();
$result = $stmt->get_result();

$entries = [];
while ($row = $result->fetch_assoc()) {
    $entries[] = $row;
}

echo json_encode($entries);
$conn->close();
?>
