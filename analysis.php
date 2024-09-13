<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Sales & Product Analysis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet/dist/leaflet.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Analysis Page Styles */
        .analysis-page-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin: 20px;
            max-width: 100%;
            box-sizing: border-box;
            width: 100%;
        }

        .analysis-page-container h2 {
            margin-bottom: 20px;
            color: #28a745;
            text-align: center;
        }

        .product-filter {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .product-filter label {
            margin-right: 10px;
            font-size: 16px;
        }

        .product-filter select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
        }

        .chart-container {
            margin-bottom: 30px;
        }

        canvas {
            width: 100% !important;
            height: auto !important; /* Adjust height to auto for better responsiveness */
        }

        #map {
            height: 500px;
            width: 100%;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        /* Flexbox container to manage alignment */
        .chart-controls, .filter-controls, .export-controls, .annotation-controls {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .chart-controls label, .filter-controls label, .annotation-controls label {
            margin-right: 10px;
            font-size: 16px;
        }

        .chart-controls select, .filter-controls input, .annotation-controls input, .export-controls button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <!-- Analysis Page Content -->
    <div class="analysis-page-container">
        <h2>Advanced Sales & Product Analysis</h2>

        <div class="product-filter">
            <label for="productFilter">Select Product:</label>
            <select id="productFilter">
                <option value="AFYA" selected>AFYA</option>
                <option value="JEMBE">JEMBE</option>
                <option value="MO_ENERGY">MO ENERGY</option>
                <option value="GBOOST">GBOOST</option>
                <option value="AZAM_ENERGY">AZAM ENERGY</option>
                <option value="COCACOLA">COCACOLA</option>
                <option value="PEPSI">PEPSI</option>
                <!-- Add other product options here -->
            </select>
        </div>

        <!-- Sales Chart -->
        <div class="chart-container">
            <h3>Sales Chart</h3>
            <canvas id="salesChart"></canvas>
        </div>

        <!-- Engagement Chart -->
        <div class="chart-container">
            <h3>Engagement Chart</h3>
            <canvas id="engagementChart"></canvas>
        </div>

        <!-- Orders Chart -->
        <div class="chart-container">
            <h3>Orders Chart</h3>
            <canvas id="ordersChart"></canvas>
        </div>

        <!-- Map for Regional Analysis -->
        <div id="map"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = ['January', 'February', 'March', 'April', 'May', 'June'];
            let salesData = {
                AFYA: [120, 150, 180, 130, 140, 170],
                JEMBE: [100, 110, 150, 160, 145, 180],
                MO_ENERGY: [90, 120, 130, 100, 110, 140],
                GBOOST: [50, 60, 70, 80, 90, 100],
                AZAM_ENERGY: [130, 140, 160, 170, 180, 190],
                COCACOLA: [180, 190, 200, 210, 220, 230],
                PEPSI: [160, 170, 190, 200, 210, 220]
            };

            let salesChart, engagementChart, ordersChart;

            // Function to create charts
            function createChart(ctx, label, data) {
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Initialize charts with default product AFYA
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const engagementCtx = document.getElementById('engagementChart').getContext('2d');
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');

            salesChart = createChart(salesCtx, 'Sales for AFYA', salesData['AFYA']);
            engagementChart = createChart(engagementCtx, 'Engagement for AFYA', salesData['AFYA']);
            ordersChart = createChart(ordersCtx, 'Orders for AFYA', salesData['AFYA']);

            // Handle product filter change
            document.getElementById('productFilter').addEventListener('change', function () {
                const selectedProduct = this.value;
                salesChart.data.datasets[0].data = salesData[selectedProduct];
                salesChart.data.datasets[0].label = 'Sales for ' + selectedProduct;
                salesChart.update();

                engagementChart.data.datasets[0].data = salesData[selectedProduct];
                engagementChart.data.datasets[0].label = 'Engagement for ' + selectedProduct;
                engagementChart.update();

                ordersChart.data.datasets[0].data = salesData[selectedProduct];
                ordersChart.data.datasets[0].label = 'Orders for ' + selectedProduct;
                ordersChart.update();
            });

            // Initialize the map for geographical analysis
            const map = L.map('map').setView([51.505, -0.09], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const locations = [
                { lat: 51.5, lng: -0.09, popup: 'Marker 1' },
                { lat: 51.515, lng: -0.1, popup: 'Marker 2' },
                { lat: 51.51, lng: -0.12, popup: 'Marker 3' }
            ];

            locations.forEach(location => {
                L.marker([location.lat, location.lng]).addTo(map)
                    .bindPopup(location.popup)
                    .openPopup();
            });
        });
    </script>
</body>
</html>

<?php include 'dashboard_footer.php'; ?>
