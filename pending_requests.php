<?php
// Include database connection
include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO requests (user_id, product_name, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $product_name, $quantity);
    
    if ($stmt->execute()) {
        echo "Request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch pending requests
$sql = "SELECT requests.id, users.username, requests.product_name, requests.quantity, requests.request_date 
        FROM requests
        JOIN users ON requests.user_id = users.id
        WHERE requests.status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Requests</title>
</head>
<body>
    <h1>Request Shipment</h1>
    <form method="POST" action="">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" required><br>
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required><br>
        <input type="submit" value="Submit Request">
    </form>

    <h2>Pending Requests</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Request Date</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No pending requests found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
