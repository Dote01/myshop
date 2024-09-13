<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle new discussion form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_discussion'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO discussions (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);

    if ($stmt->execute()) {
        $message = "Discussion posted successfully!";
    } else {
        $message = "Error posting discussion.";
    }
}

// Fetch discussions from the database
$sql = "SELECT d.discussion_id, d.title, d.content, d.created_at, u.username 
        FROM discussions d 
        JOIN users u ON d.user_id = u.user_id 
        ORDER BY d.created_at DESC";
$discussions = $conn->query($sql);
?>

<?php include 'dashboard_header.php'; ?>

<div class="discussion-container">
    <h2>Discussions</h2>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="discussion_page.php" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
        </div>

        <button type="submit" name="post_discussion">Post Discussion</button>
    </form>

    <h3>Recent Discussions</h3>
    <div class="discussion-list">
        <?php if ($discussions->num_rows > 0): ?>
            <?php while ($discussion = $discussions->fetch_assoc()): ?>
                <div class="discussion-item">
                    <h4><?php echo htmlspecialchars($discussion['title']); ?></h4>
                    <p><strong><?php echo htmlspecialchars($discussion['username']); ?></strong> - <?php echo htmlspecialchars($discussion['created_at']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($discussion['content'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No discussions available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .discussion-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .discussion-container h2 {
        color: #007BFF;
        margin-bottom: 20px;
        font-size: 2em;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 10px;
    }

    .discussion-container form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group textarea {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: 100%;
        box-sizing: border-box;
        font-size: 1em;
        color: #333;
    }

    .form-group textarea {
        height: 150px;
        resize: vertical;
    }

    .discussion-container button {
        padding: 12px 25px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s, transform 0.2s;
    }

    .discussion-container button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .message {
        padding: 12px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 1em;
    }

    .discussion-list {
        margin-top: 20px;
    }

    .discussion-item {
        padding: 20px;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.3s;
    }

    .discussion-item:hover {
        background-color: #f9f9f9;
    }

    .discussion-item h4 {
        margin-bottom: 10px;
        color: #007BFF;
        font-size: 1.2em;
    }

    .discussion-item p {
        margin-bottom: 10px;
        color: #555;
    }

    .discussion-item p strong {
        color: #333;
    }
</style>
