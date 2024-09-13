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

// Fetch comments for a specific discussion
function fetchComments($conn, $discussion_id) {
    $sql = "SELECT c.comment_id, c.content, c.created_at, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.user_id 
            WHERE c.discussion_id = ? 
            ORDER BY c.created_at ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("i", $discussion_id);
    if (!$stmt->execute()) {
        handleSqlError($stmt);
    }
    return $stmt->get_result();
}

// Fetch discussion details
function fetchDiscussion($conn, $discussion_id) {
    $sql = "SELECT d.discussion_id, d.title, d.content, d.created_at, d.status, u.username 
            FROM discussions d 
            JOIN users u ON d.user_id = u.user_id 
            WHERE d.discussion_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("i", $discussion_id);
    if (!$stmt->execute()) {
        handleSqlError($stmt);
    }
    return $stmt->get_result()->fetch_assoc();
}

// Fetch discussion id from the query string
$discussion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($discussion_id > 0) {
    $discussion = fetchDiscussion($conn, $discussion_id);
    if (!$discussion) {
        die("Discussion not found.");
    }
    $comments = fetchComments($conn, $discussion_id);
} else {
    die("Invalid discussion ID.");
}
?>

<?php include 'dashboard_header.php'; ?>

<div class="discussion-container">
    <h2>Discussion Details</h2>
    <div class="discussion-item">
        <h4><?php echo htmlspecialchars($discussion['title']); ?></h4>
        <p><strong><?php echo htmlspecialchars($discussion['username']); ?></strong> - <?php echo htmlspecialchars($discussion['created_at']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($discussion['content'])); ?></p>
    </div>

    <h3>Comments</h3>
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
    <h3>Add a Comment</h3>
    <form action="discussion_view.php?id=<?php echo $discussion_id; ?>" method="POST">
        <div class="form-group">
            <label for="content">Comment:</label>
            <textarea id="content" name="content" required></textarea>
        </div>
        <button type="submit" name="post_comment" class="btn btn-primary">Post Comment</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>
