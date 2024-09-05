<?php
// discussion_details.php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

// Fetch discussion details
$query = "SELECT * FROM discussionrooms WHERE discussion_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $discussion_id);
$stmt->execute();
$discussion = $stmt->get_result()->fetch_assoc();

if (!$discussion) {
    echo "Discussion not found.";
    exit();
}

// Fetch messages
$query = "SELECT * FROM discussionmessages WHERE discussion_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $discussion_id);
$stmt->execute();
$messages = $stmt->get_result();

// Handle new message form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $sender_id = $_SESSION['user_id'];

    $query = "INSERT INTO discussionmessages (discussion_id, sender_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $discussion_id, $sender_id, $message);
    if ($stmt->execute()) {
        header("Location: discussion_details.php?discussion_id=$discussion_id");
        exit();
    } else {
        echo "Error adding message.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($discussion['topic']); ?> - Discussion Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <h1><?php echo htmlspecialchars($discussion['topic']); ?></h1>
    
    <div class="discussion-details">
        <?php while ($message = $messages->fetch_assoc()): ?>
            <div class="message">
                <p><strong>User <?php echo htmlspecialchars($message['sender_id']); ?>:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                <p><em>Posted on: <?php echo htmlspecialchars($message['created_at']); ?></em></p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Post a new message</h2>
    <form method="post" action="">
        <textarea name="message" rows="5" required></textarea>
        <br>
        <button type="submit">Send</button>
    </form>
    
    <a href="discussion_page.php">Back to Discussions</a>
</body>
</html>
