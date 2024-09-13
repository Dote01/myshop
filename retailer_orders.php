<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$requests = [];

try {
    $sql = "SELECT * FROM product_requests WHERE status = 'Pending'";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Product Requests - MyShop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .requests-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #4CAF50;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-size: 2.4rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .button {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.5rem 0;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #45a049;
        }
        .action-button {
            background-color: #007bff;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'dashbord_header.php'; ?>
    <main class="requests-container">
        <h1>Product Requests</h1>
        
        <?php if (is_array($requests) && count($requests) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>User</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($request['status']); ?></td>
                            <td>
                                <a href="handle_request.php?request_id=<?php echo htmlspecialchars($request['request_id']); ?>&action=approve" class="button">Approve</a>
                                <a href="handle_request.php?request_id=<?php echo htmlspecialchars($request['request_id']); ?>&action=reject" class="button action-button">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
            </table>
        <?php else: ?>
            <p>No pending product requests at the moment.</p>
        <?php endif; ?>
    </main>

    <?php include 'dashboard_footer.php'; ?>
</body>
</html>
