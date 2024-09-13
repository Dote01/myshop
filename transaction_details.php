<?php
require_once 'config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$amount = $_SESSION['transaction_amount'] ?? 0;
$action = $_SESSION['transaction_action'] ?? '';
$paymentMethod = $_SESSION['transaction_paymentMethod'] ?? '';

// Clear session variables used for the transaction details
unset($_SESSION['transaction_amount'], $_SESSION['transaction_action'], $_SESSION['transaction_paymentMethod']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = $_POST['cardNumber'] ?? '';
    $mobileNumber = $_POST['mobileNumber'] ?? '';

    // Validate card number and mobile number
    if ($paymentMethod === 'Visa' || $paymentMethod === 'MasterCard') {
        if (empty($cardNumber)) {
            $_SESSION['transaction_message'] = "Card number is required.";
            $_SESSION['transaction_type'] = "error";
            header('Location: transaction_details.php');
            exit();
        }
    } elseif ($paymentMethod === 'Mobile Payment') {
        if (empty($mobileNumber)) {
            $_SESSION['transaction_message'] = "Mobile number is required.";
            $_SESSION['transaction_type'] = "error";
            header('Location: transaction_details.php');
            exit();
        }
    }

    // Store additional details in session
    $_SESSION['cardNumber'] = $cardNumber;
    $_SESSION['mobileNumber'] = $mobileNumber;

    // Redirect to transaction processing page
    header('Location: process_transaction.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <?php include 'dashboard_header.php'; ?>

    <div class="container">
        <h2>Enter Additional Transaction Details</h2>

        <div class="form-container">
            <form action="transaction_details.php" method="post">
                <?php if ($paymentMethod === 'Visa' || $paymentMethod === 'MasterCard'): ?>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" required>
                    </div>
                <?php elseif ($paymentMethod === 'Mobile Payment'): ?>
                    <div class="form-group">
                        <label for="mobileNumber">Mobile Number</label>
                        <input type="text" id="mobileNumber" name="mobileNumber" required>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <button type="submit">Submit Details</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'dashboard_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
