<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch performance data
$stmt = $conn->prepare("
    SELECT p.name AS product_name, 
           SUM(o.quantity) AS total_quantity_sold, 
           SUM(o.total_price) AS total_revenue, 
           COUNT(o.id) AS total_orders
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE p.retailer_id = ? AND o.status = 'verified'
    GROUP BY p.name
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$performance_data = $stmt->get_result();

include 'partials/header.php';
?>

<div class="performance-container">
    <h2>Performance Overview</h2>
    
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
                <th>Total Orders</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $performance_data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_quantity_sold']); ?></td>
                    <td>$<?php echo htmlspecialchars($row['total_revenue']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_orders']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<!-- Chart.js for performance visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chartData = {
        labels: [<?php while ($row = $performance_data->fetch_assoc()) { echo '"' . htmlspecialchars($row['product_name']) . '",'; } ?>],
        datasets: [{
            label: 'Total Sales',
            data: [<?php while ($row = $performance_data->fetch_assoc()) { echo htmlspecialchars($row['total_quantity_sold']) . ','; } ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<!-- CSS for performance page -->
<style>
    .performance-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    .chart-container {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
