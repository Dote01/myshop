<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'configs.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role_id = $_SESSION['role'];

$role_name = "";
switch ($role_id) {
    case 1:
        $role_name = "User";
        break;
    case 2:
        $role_name = "Retailer";
        break;
    case 3:
        $role_name = "Admin";
        break;
}

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Build the query
$query = "SELECT * FROM transportation_requests 
          WHERE (request_details LIKE :search OR :search = '') 
          AND (request_status LIKE :status_filter OR :status_filter = '') 
          ORDER BY $sort_by $order 
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', "%$search%");
$stmt->bindValue(':status_filter', "%$status_filter%");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$requests = $stmt->fetchAll();

// Get total number of records
$count_query = "SELECT COUNT(*) AS total FROM transportation_requests 
                WHERE (request_details LIKE :search OR :search = '') 
                AND (request_status LIKE :status_filter OR :status_filter = '')";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindValue(':search', "%$search%");
$count_stmt->bindValue(':status_filter', "%$status_filter%");
$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History - MyShop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f4f7;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #4CAF50, #2e8b57);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid #2e8b57;
        }
        .logo {
            font-size: 1.8em;
            font-weight: bold;
            letter-spacing: 1px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }
        nav ul li {
            position: relative;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s;
            display: block;
            font-size: 16px;
        }
        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
            top: 100%;
            left: 0;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Dashboard Styles */
        .dashboard {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dashboard-container {
            max-width: 1200px;
            width: 100%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border: 3px solid #4CAF50;
            animation: fadeInUp 1s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .dashboard-container h2 {
            font-size: 2.4em;
            color: #4CAF50;
            margin-bottom: 20px;
            font-weight: 700;
            text-align: center;
        }
        .dashboard-container p {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
            font-weight: 300;
            text-align: center;
        }
        .filter-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .filter-form input, .filter-form select, .filter-form button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .filter-form button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .filter-form button:hover {
            background-color: #45a049;
        }
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-link {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        /* Pagination Styles */
        .pagination {
            margin: 20px 0;
            text-align: center;
        }
        .pagination a {
            color: #4CAF50;
            padding: 10px 15px;
            text-decoration: none;
            border: 1px solid #4CAF50;
            border-radius: 4px;
            margin: 0 5px;
            font-size: 14px;
        }
        .pagination a:hover {
            background-color: #4CAF50;
            color: #fff;
        }
        .pagination .active {
            background-color: #4CAF50;
            color: #fff;
            border: 1px solid #4CAF50;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            position: relative;
            border-top: 2px solid #4CAF50;
        }
        .footer-content {
            padding: 0 20px;
        }
        .footer-content p {
            margin: 10px 0;
        }
        .social-media a, .legal a, .quick-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
        }
        .social-media a:hover, .legal a:hover, .quick-links a:hover {
            text-decoration: underline;
        }
        .newsletter form {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .newsletter input {
            padding: 10px;
            border-radius: 4px;
            border: none;
            margin-right: 10px;
            flex: 1;
            font-size: 14px;
        }
        .newsletter button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .newsletter button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">MyShop</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li class="dropdown">
                    <a href="#">Manage Orders</a>
                    <div class="dropdown-content">
                        <a href="pending_orders.php">Pending Orders</a>
                        <a href="verified_orders.php">Verified Orders</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Market</a>
                    <div class="dropdown-content">
                        <a href="buyers.php">Buyers</a>
                        <a href="sellers.php">Sellers</a>
                    </div>
                </li>
                <li><a href="transportation.php">Transportation</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section -->
    <div class="dashboard">
        <div class="dashboard-container">
            <h2>Request History</h2>
            <p>View and manage historical transportation requests.</p>
            
            <!-- Filter Form -->
            <form class="filter-form" action="request_history.php" method="get">
                <input type="text" name="search" placeholder="Search by details" value="<?php echo htmlspecialchars($search); ?>">
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
                <select name="sort_by">
                    <option value="created_at" <?php echo $sort_by === 'created_at' ? 'selected' : ''; ?>>Date Created</option>
                    <option value="request_status" <?php echo $sort_by === 'request_status' ? 'selected' : ''; ?>>Status</option>
                </select>
                <select name="order">
                    <option value="DESC" <?php echo $order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                    <option value="ASC" <?php echo $order === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                </select>
                <button type="submit">Apply Filters</button>
            </form>

            <!-- Requests Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Retailer ID</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['id']); ?></td>
                            <td><?php echo htmlspecialchars($request['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['retailer_id']); ?></td>
                            <td><?php echo htmlspecialchars($request['request_details']); ?></td>
                            <td><?php echo htmlspecialchars($request['request_status']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($request['created_at']))); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="request_history.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&sort_by=<?php echo urlencode($sort_by); ?>&order=<?php echo urlencode($order); ?>" class="<?php echo $i === (int)$page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 MyShop. All rights reserved.</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
            <div class="quick-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <div class="newsletter">
                <form action="subscribe.php" method="post">
                    <input type="email" name="email" placeholder="Subscribe to our newsletter" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>
