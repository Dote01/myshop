<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'configss.php';

// Check if $pdo is defined
if (!isset($pdo)) {
    die('Database connection not established.');
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role_id = $_SESSION['role'];

// Define the query for summary and chart data
$summary_query = "SELECT request_status, COUNT(*) AS total_requests
                  FROM transportation_requests
                  GROUP BY request_status";

$chart_query = "SELECT DATE(created_at) AS date, COUNT(*) AS trips
                FROM transportation_requests
                WHERE DATE(created_at) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                GROUP BY DATE(created_at)";

// Fetch summary data
$summary_stmt = $pdo->query($summary_query);
$summary_data = $summary_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch chart data
$chart_stmt = $pdo->query($chart_query);
$chart_data = $chart_stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for chart
$chart_labels = [];
$chart_values = [];
foreach ($chart_data as $row) {
    $chart_labels[] = $row['date'];
    $chart_values[] = $row['trips'];
}

$summary = [];
foreach ($summary_data as $row) {
    $summary[$row['request_status']] = $row['total_requests'];
}
include 'dashboard_header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Summary - MyShop</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> /* General Styles */
/* Base Styles */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f2f4f7;
    color: #333;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
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

/* Dropdown Styles */
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
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.dashboard-container {
    width: 100%;
    max-width: 1200px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    padding: 30px;
    border: 3px solid #4CAF50;
    animation: fadeInUp 1s ease-out;
    text-align: center;
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
    font-size: 2em;
    color: #4CAF50;
    margin-bottom: 20px;
    font-weight: 700;
}

.dashboard-container p {
    font-size: 1.1em;
    color: #666;
    margin-bottom: 20px;
    font-weight: 300;
}

.home-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.home-button {
    display: inline-block;
    padding: 15px 30px;
    background-color: #2c6b59;
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-size: 16px;
    transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    font-weight: 500;
}

.home-button:hover {
    background-color: #1f5d49;
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.dashboard-summary {
    margin-top: 30px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.summary-card {
    flex: 1;
    min-width: 280px;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    text-align: left;
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 20px;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.summary-card h3 {
    font-size: 1.4em;
    color: #2c6b59;
    margin-bottom: 15px;
    font-weight: 600;
}

.summary-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.summary-list li {
    margin: 10px 0;
}

.summary-link {
    display: inline-block;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-weight: 500;
}

.summary-link:hover {
    background-color: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
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

/* Additional Styles for Advanced Features */
.widget {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.chart-container {
    margin-top: 20px;
    position: relative;
    height: 300px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 20px;
    }

    .home-buttons {
        flex-direction: column;
    }

    .summary-card {
        min-width: 100%;
    }

    .chart-container {
        height: 200px;
    }
}

@media (max-width: 480px) {
    header {
        flex-direction: column;
        align-items: flex-start;
    }

    nav ul {
        flex-direction: column;
        gap: 10px;
    }

    .dropdown-content {
        position: static;
        box-shadow: none;
    }
}


    </style>
</head>
<body>

    <!-- Header Section -->
    

    <!-- Main Section -->
    <div class="dashboard">
        <div class="dashboard-container">
            <h2>Logistics Summary</h2>
            <p>Overview of logistics trips and request statuses.</p>
            
            <!-- Summary Table -->
            <h3>Request Status Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Total Requests</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary as $status => $total): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($status); ?></td>
                        <td><?php echo htmlspecialchars($total); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Chart -->
            <div class="chart-container">
                <h3>Trips Over the Last 30 Days</h3>
                <canvas id="tripsChart" width="400" height="200"></canvas>
            </div>
            <script>
                const ctx = document.getElementById('tripsChart').getContext('2d');
                const tripsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($chart_labels); ?>,
                        datasets: [{
                            label: 'Number of Trips',
                            data: <?php echo json_encode($chart_values); ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                },
                                beginAtZero: true
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Number of Trips'
                                },
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('mouseenter', function () {
            this.querySelector('.dropdown-content').style.display = 'block';
        });

        dropdown.addEventListener('mouseleave', function () {
            this.querySelector('.dropdown-content').style.display = 'none';
        });
    });
});

    document.addEventListener('DOMContentLoaded', (event) => {
        const ctx = document.getElementById('tripsChart').getContext('2d');

        // Initialize chart
        const tripsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Number of Trips',
                    data: <?php echo json_encode($chart_values); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Trips'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // Function to simulate updating chart data
        function updateChart() {
            // Simulate new data (this is where you'd fetch new data from your server)
            var newDataPoint = Math.floor(Math.random() * 100);
            var newLabel = new Date().toISOString().split('T')[0]; // Today's date

            // Add new data point
            tripsChart.data.labels.push(newLabel);
            tripsChart.data.datasets[0].data.push(newDataPoint);

            // Keep only the last 30 days of data
            if (tripsChart.data.labels.length > 30) {
                tripsChart.data.labels.shift(); // Remove oldest label
                tripsChart.data.datasets[0].data.shift(); // Remove oldest data point
            }

            tripsChart.update(); // Update the chart with new data
        }

        // Update the chart every 5 seconds
        setInterval(updateChart, 5000);
    });
</script>
