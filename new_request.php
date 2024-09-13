<?php
session_start();

// Debug: Output session variables
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    // Debug: Check role and session values
    echo "Role: " . $_SESSION['role'] . "<br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    
    // Ensure only users and retailers can access this page
    header("Location: login.php");
    exit();
}

require_once 'config.php'; // Include database configuration

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $shipment_details = $_POST['shipment_details'];
    $destination = $_POST['destination'];
    $shipment_date = $_POST['shipment_date'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO shipments (user_id, shipment_details, destination, shipment_date, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $shipment_details, $destination, $shipment_date]);
        $success_message = "Shipment request submitted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Shipment Request - MyShop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="bg-success text-white p-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4">MyShop</h1>
            <nav>
                <a href="dashboard.php" class="text-white mx-2">Dashboard</a>
                <a href="logout.php" class="text-white">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2 class="mb-4">New Shipment Request</h2>
        <p>Please fill out the form below to request a new shipment.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="new_request.php" method="POST">
            <div class="mb-3">
                <label for="shipment_details" class="form-label">Shipment Details</label>
                <textarea id="shipment_details" name="shipment_details" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" id="destination" name="destination" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="shipment_date" class="form-label">Shipment Date</label>
                <input type="date" id="shipment_date" name="shipment_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
