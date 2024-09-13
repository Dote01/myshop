<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$transactionId = $_GET['id'] ?? 0;

// Fetch transaction details
$sql = "SELECT * FROM transactions WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $transactionId, $user_id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

if (!$transaction) {
    $_SESSION['transaction_message'] = "Transaction not found.";
    $_SESSION['transaction_type'] = "error";
    header('Location: transaction.php');
    exit();
}

// Get user balance
$sql = "SELECT balance FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$balance = $stmt->get_result()->fetch_assoc()['balance'];

// Process deposit or withdrawal
if ($transaction['action'] === 'deposit') {
    $sql = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $transaction['amount'], $user_id);
    $stmt->execute();
    $message = "Deposit successful!";
} elseif ($transaction['action'] === 'withdraw') {
    if ($transaction['amount'] > $balance) {
        $message = "Insufficient funds!";
        $_SESSION['transaction_type'] = "error";
    } else {
        $sql = "UPDATE users SET balance = balance - ? WHERE user_id = ?";
        $stmt->bind_param("di", $transaction['amount'], $user_id);
        $stmt->execute();
        $message = "Withdrawal successful!";
    }
}

// Update transaction status
$sql = "UPDATE transactions SET status = 'completed' WHERE id = ?";
$stmt->prepare($sql);
$stmt->bind_param("i", $transactionId);
$stmt->execute();

// Redirect with success message
$_SESSION['transaction_message'] = $message;
$_SESSION['transaction_type'] = "success";
header('Location: transaction.php');
exit();
?>
