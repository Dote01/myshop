<?php
session_start();
echo 'User ID: ' . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not Set') . '<br>';
echo 'Role: ' . (isset($_SESSION['role']) ? $_SESSION['role'] : 'Not Set') . '<br>';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) { // Ensure only retailers can access this page
    header("Location: login.php");
    exit();
}

require_once 'config.php'; // Include database configuration

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch buyers data
try {
    $stmt = $pdo->prepare("SELECT id, name, email, phone, created_at FROM buyers ORDER BY created_at DESC");
    $stmt->execute();
    $buyers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyers List - MyShop</title>
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
        .alert-success {
            margin-top: 20px;
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
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
        <h2 class="mb-4">Buyers List</h2>
        <p>Below is a list of all registered buyers.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($buyers)): ?>
                    <?php foreach ($buyers as $buyer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($buyer['id']); ?></td>
                        <td><?php echo htmlspecialchars($buyer['name']); ?></td>
                        <td><?php echo htmlspecialchars($buyer['email']); ?></td>
                        <td><?php echo htmlspecialchars($buyer['phone']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($buyer['created_at']))); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No buyers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
