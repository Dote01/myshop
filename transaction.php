<?php
require_once 'config.php';
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$accountBalance = 0;

// Fetch the current balance
$sql = "SELECT balance FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $accountBalance = $result->fetch_assoc()['balance'];
} else {
    $_SESSION['transaction_message'] = "Failed to fetch account balance.";
    $_SESSION['transaction_type'] = "error";
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $action = $_POST['action'];
    $paymentMethod = $_POST['paymentMethod'];

    if ($amount <= 0) {
        $_SESSION['transaction_message'] = "Amount must be greater than zero.";
        $_SESSION['transaction_type'] = "error";
    } elseif (in_array($action, ['deposit', 'withdraw'])) {
        $_SESSION['transaction_amount'] = $amount;
        $_SESSION['transaction_action'] = $action;
        $_SESSION['transaction_paymentMethod'] = $paymentMethod;

        // Redirect based on payment method
        $redirectPage = in_array($paymentMethod, ['Visa', 'MasterCard']) ? 'transaction_details.php' : 'process_transaction.php';
        header('Location: ' . $redirectPage);
        exit();
    } else {
        $_SESSION['transaction_message'] = "Invalid action.";
        $_SESSION['transaction_type'] = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Page Styles */
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .balance-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .balance {
            font-size: 2rem;
            font-weight: 700;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .transaction-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .transaction-container h3 {
            margin-bottom: 20px;
            font-size: 1.75rem;
            font-weight: 600;
            color: #4CAF50;
        }

        .transaction-form {
            border-top: 2px solid #e9ecef;
            padding-top: 20px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
            color: #495057;
        }

        .form-control, .form-select {
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .btn-primary, .btn-danger {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .alert {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            font-size: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        @media (max-width: 767.98px) {
            .container {
                padding: 10px;
            }

            .balance {
                font-size: 1.5rem;
                padding-bottom: 10px;
            }

            .transaction-container {
                padding: 15px;
            }

            .transaction-container h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'dashboard_header.php'; ?>

    <div class="container">
        <div class="header">
            <h2>Manage Your Transactions</h2>
        </div>

        <!-- Balance Container -->
        <div class="balance-container">
            <div class="balance">Current Balance: $<?php echo number_format($accountBalance, 2); ?></div>
        </div>

        <!-- Transaction Container -->
        <div class="transaction-container">
            <h3>Deposit or Withdraw Funds</h3>

            <!-- Deposit Form -->
            <div class="transaction-form">
                <h4>Deposit Funds</h4>
                <form action="transaction.php" method="post">
                    <div class="form-group">
                        <label for="amount-deposit">Amount</label>
                        <input type="number" id="amount-deposit" name="amount" step="0.01" min="0" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod-deposit">Payment Method</label>
                        <select id="paymentMethod-deposit" name="paymentMethod" class="form-select" required>
                            <option value="" disabled selected>Select a payment method</option>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Mobile Payment">Mobile Payment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="action" value="deposit" class="btn btn-primary">Deposit</button>
                    </div>
                </form>
            </div>

            <!-- Withdraw Form -->
            <div class="transaction-form">
                <h4>Withdraw Funds</h4>
                <form action="transaction.php" method="post">
                    <div class="form-group">
                        <label for="amount-withdraw">Amount</label>
                        <input type="number" id="amount-withdraw" name="amount" step="0.01" min="0" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod-withdraw">Payment Method</label>
                        <select id="paymentMethod-withdraw" name="paymentMethod" class="form-select" required>
                            <option value="" disabled selected>Select a payment method</option>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Mobile Payment">Mobile Payment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="action" value="withdraw" class="btn btn-danger">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['transaction_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['transaction_type']; ?>">
                <strong><?php echo htmlspecialchars($_SESSION['transaction_message']); ?></strong>
            </div>
            <?php unset($_SESSION['transaction_message'], $_SESSION['transaction_type']); ?>
        <?php endif; ?>
    </div>

    <?php include 'dashboard_footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
