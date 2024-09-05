<?php


include 'dashboard_header.php'; // Optional: include header file

?>
<div class="dashboard-content">
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

    <?php if ($_SESSION['role_id'] == 1): ?>
        <!-- User-specific content -->
        <p>This is your user dashboard. Here you can:</p>
        <ul>
            <li>Browse and purchase products.</li>
            <li>Manage your profile and settings.</li>
            <li>View your order history.</li>
        </ul>
    <?php elseif ($_SESSION['role_id'] == 2): ?>
        <!-- Retailer-specific content -->
        <p>This is your retailer dashboard. Here you can:</p>
        <ul>
            <li>Manage your inventory and sales.</li>
            <li>Purchase products from the warehouse.</li>
            <li>View sales reports and analytics.</li>
        </ul>
    <?php endif; ?>
</div>
<?php include 'dashboard_footer.php'; // Optional: include footer file ?>
