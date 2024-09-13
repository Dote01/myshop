<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle feedback form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $feedback_text = $_POST['feedback_text'];
    $customer_name = $_POST['customer_name'];
    $sentiment = $_POST['sentiment'];
    $retailer_id = $_POST['retailer_id'];

    // Validate inputs
    if (empty($feedback_text) || empty($customer_name) || empty($sentiment) || empty($retailer_id)) {
        $message = "All fields are required.";
    } else {
        // Check if retailer_id exists
        $sql = "SELECT COUNT(*) FROM retailers WHERE retailer_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("i", $retailer_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $message = "The specified retailer does not exist.";
        } else {
            // Prepare and execute the insertion query
            $sql = "INSERT INTO customer_feedback (retailer_id, feedback_text, customer_name, sentiment) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("SQL Error: " . $conn->error);
            }
            $stmt->bind_param("isss", $retailer_id, $feedback_text, $customer_name, $sentiment);

            if ($stmt->execute()) {
                $message = "Feedback submitted successfully!";
            } else {
                $message = "Error submitting feedback: " . $stmt->error;
            }
        }
    }
}

// Fetch feedback from the database
function fetchFeedback($conn) {
    $sql = "SELECT * FROM customer_feedback ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error fetching feedback: " . $conn->error);
    }

    return $result;
}

$feedbacks = fetchFeedback($conn);
?>

<?php include 'dashboard_header.php'; ?>

<div class="feedback-container">
    <h2>Customer Feedback</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Form to submit new feedback -->
    <h3>Submit Feedback</h3>
    <form action="customer_feedback.php" method="POST">
        <div class="form-group">
            <label for="retailer_id">Retailer ID:</label>
            <input type="number" id="retailer_id" name="retailer_id" required class="form-control">
        </div>
        <div class="form-group">
            <label for="feedback_text">Feedback:</label>
            <textarea id="feedback_text" name="feedback_text" required class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="customer_name">Your Name:</label>
            <input type="text" id="customer_name" name="customer_name" required class="form-control">
        </div>
        <div class="form-group">
            <label for="sentiment">Sentiment:</label>
            <select id="sentiment" name="sentiment" required class="form-control">
                <option value="positive">Positive</option>
                <option value="neutral">Neutral</option>
                <option value="negative">Negative</option>
            </select>
        </div>
        <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
    </form>

    <!-- Display existing feedback -->
    <h3>Existing Feedback</h3>
    <div class="feedback-list">
        <?php if ($feedbacks->num_rows > 0): ?>
            <?php while ($feedback = $feedbacks->fetch_assoc()): ?>
                <div class="feedback-item">
                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($feedback['customer_name']); ?></p>
                    <p><strong>Feedback:</strong> <?php echo nl2br(htmlspecialchars($feedback['feedback_text'])); ?></p>
                    <p><strong>Sentiment:</strong> <?php echo htmlspecialchars($feedback['sentiment']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($feedback['created_at']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    /* Basic Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .feedback-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2, h3 {
        color: #333;
    }

    .alert {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-info {
        background-color: #e7f0ff;
        color: #31708f;
        border: 1px solid #bce8f1;
    }

    .feedback-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .feedback-list {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
    }

    .btn:hover {
        background-color: #0056b3;
    }
</style>
