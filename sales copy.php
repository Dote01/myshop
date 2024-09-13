<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Sales Page Content -->
<div class="sales-container">
    <h2>Sales Overview</h2>
    <div class="sales-summary">
        <?php
        // Fetch sales data from the database
        $sql = "SELECT SUM(amount) as total_sales FROM sales WHERE retailer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $retailer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sales_data = $result->fetch_assoc();
            echo '<p>Total Sales: $' . htmlspecialchars($sales_data['total_sales']) . '</p>';
        } else {
            echo '<p>No sales data available.</p>';
        }
        ?>
    </div>

    <h3>Recent Transactions</h3>
    <div class="transaction-list">
        <?php
        // Fetch recent transactions from the database
        $sql = "SELECT * FROM transactions WHERE retailer_id = ? ORDER BY transaction_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $retailer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($transaction = $result->fetch_assoc()) {
                echo '<div class="transaction-item">';
                echo '<p>Product: ' . htmlspecialchars($transaction['product_name']) . '</p>';
                echo '<p>Amount: $' . htmlspecialchars($transaction['amount']) . '</p>';
                echo '<p>Date: ' . htmlspecialchars($transaction['transaction_date']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No transactions available.</p>';
        }
        ?>
    </div>
</div>

<?php include '../dashboard/dashboard_footer.php'; ?>
