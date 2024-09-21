<?php
include 'db-connection.php';
// session_start(); // Ensure this is at the beginning of the file

header('Content-Type: application/json'); // Set content type to JSON

$user_id = $_SESSION['user_id'];

// Query to get total income for the logged-in user
$income_query = "SELECT SUM(amount) AS total_income FROM transactions WHERE type = 'income' AND user_id = ?";
$stmt = $conn->prepare($income_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$income_result = $stmt->get_result();
$total_income = $income_result->fetch_assoc()['total_income'] ?? 0;

// Query to get total expenses for the logged-in user
$expense_query = "SELECT SUM(amount) AS total_expenses FROM transactions WHERE type = 'expense' AND user_id = ?";
$stmt = $conn->prepare($expense_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$expense_result = $stmt->get_result();
$total_expenses = $expense_result->fetch_assoc()['total_expenses'] ?? 0;

// Calculate balance
$total_balance = $total_income - $total_expenses;

// Return data as JSON
$response = [
    'total_income' => $total_income,
    'total_expenses' => $total_expenses,
    'total_balance' => $total_balance
];

echo json_encode($response);
$conn->close();
?>
