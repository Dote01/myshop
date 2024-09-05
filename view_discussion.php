<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Helper function to handle SQL preparation errors
function handleSqlError($stmt) {
    die("SQL Error: " . $stmt->error);
}

// Fetch the discussion details
if (isset($_GET['discussion_id'])) {
    $discussion_id = $_GET['discussion_id'];

    // Fetch the discussion
    $sql = "SELECT d.discussion_id, d.title, d.content, d.created_at, d.status, u.username 
            FROM discussions d 
            JOIN users u ON d.user_id = u.user_id 
            WHERE d.discussion_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("i", $discussion_id);
    $stmt->execute();
    $discussion = $stmt->get_result()->fetch_assoc();

    if (!$discussion) {
        die("Discussion not found.");
    }

    // Fetch comments for the discussion
    function fetchComments($conn, $discussion_id) {
        $sql = "SELECT c.content, c.created_at, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.user_id 
                WHERE c.discussion_id = ? 
                ORDER BY c.created_at ASC";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            handleSqlError($conn);
        }
        $stmt->bind_param("i", $discussion_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Handle new comment form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_comment'])) {
        $content = $_POST['content'];

        if (empty($content)) {
            $message = "Comment content cannot be empty.";
        } else {
            $sql = "INSERT INTO comments (discussion_id, user_id, content) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                handleSqlError($conn);
            }
            $stmt->bind_param("iis", $discussion_id, $user_id, $content);

            if ($stmt->execute()) {
                $message = "Comment posted successfully!";
            } else {
                $message = "Error posting comment: " . $stmt->error;
            }
        }
    }

    $comments = fetchComments($conn, $discussion_id);
} else {
    die("No discussion ID provided.");
}

?>

<?php include 'dashboard_header.php'; ?>

<div class="discussion-container">
    <h2>Discussion</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="discussion-item">
        <h4><?php echo htmlspecialchars($discussion['title']); ?></h4>
        <p><strong><?php echo htmlspecialchars($discussion['username']); ?></strong> - <?php echo htmlspecialchars($discussion['created_at']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($discussion['content'])); ?></p>

        <div class="comment-section">
            <h5>Comments:</h5>
            <?php if ($comments->num_rows > 0): ?>
                <?php while ($comment = $comments->fetch_assoc()): ?>
                    <div class="comment-item">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong> - <?php echo htmlspecialchars($comment['created_at']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>

            <!-- Form to post a new comment -->
            <form action="view_discussion.php?discussion_id=<?php echo htmlspecialchars($discussion['discussion_id']); ?>" method="POST">
                <div class="form-group">
                    <label for="comment_content">Add a comment:</label>
                    <textarea id="comment_content" name="content" required></textarea>
                </div>
                <button type="submit" name="post_comment" class="btn btn-primary">Post Comment</button>
            </form>
        </div>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>
