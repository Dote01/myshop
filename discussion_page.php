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

// Handle new discussion form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_discussion'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (empty($title) || empty($content)) {
        $message = "Title and content cannot be empty.";
    } else {
        $sql = "INSERT INTO discussions (user_id, title, content, status) VALUES (?, ?, ?, 'open')";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            handleSqlError($conn);
        }
        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            $discussion_id = $stmt->insert_id;

            // Add the user to the discussion participants
            $sql = "INSERT INTO discussion_participants (discussion_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                handleSqlError($conn);
            }
            $stmt->bind_param("ii", $discussion_id, $user_id);
            $stmt->execute();

            $message = "Discussion posted successfully!";
        } else {
            $message = "Error posting discussion: " . $stmt->error;
        }
    }
}

// Handle joining a discussion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['join_discussion'])) {
    $discussion_id = $_POST['discussion_id'];

    // Check if the user is already a participant
    $sql = "SELECT * FROM discussion_participants WHERE discussion_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("ii", $discussion_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "You are already a participant of this discussion.";
    } else {
        // Add the user to the discussion participants
        $sql = "INSERT INTO discussion_participants (discussion_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            handleSqlError($conn);
        }
        $stmt->bind_param("ii", $discussion_id, $user_id);

        if ($stmt->execute()) {
            $message = "You have joined the discussion!";
        } else {
            $message = "Error joining discussion: " . $stmt->error;
        }
    }
}

// Handle new comment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_comment'])) {
    $discussion_id = $_POST['discussion_id'];
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

// Fetch discussions from the database
function fetchDiscussions($conn) {
    $sql = "SELECT d.discussion_id, d.title, d.content, d.created_at, d.status, u.username 
            FROM discussions d 
            JOIN users u ON d.user_id = u.user_id 
            ORDER BY d.created_at DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error fetching discussions: " . $conn->error);
    }

    return $result;
}

// Fetch comments for a specific discussion
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
    if (!$stmt->execute()) {
        handleSqlError($stmt);
    }
    return $stmt->get_result();
}

// Check if user is a participant in the discussion
function isParticipant($conn, $discussion_id, $user_id) {
    $sql = "SELECT * FROM discussion_participants WHERE discussion_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("ii", $discussion_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

$discussions = fetchDiscussions($conn);
?>

<?php include 'dashboard_header.php'; ?>

<div class="discussion-container">
    <h2>Discussions</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h3>Recent Discussions</h3>
    <div class="discussion-list">
        <?php if ($discussions->num_rows > 0): ?>
            <?php while ($discussion = $discussions->fetch_assoc()): ?>
                <div class="discussion-item">
                    <h4><?php echo htmlspecialchars($discussion['title']); ?></h4>
                    <p><strong><?php echo htmlspecialchars($discussion['username']); ?></strong> - <?php echo htmlspecialchars($discussion['created_at']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($discussion['content'])); ?></p>

                    <?php if (isParticipant($conn, $discussion['discussion_id'], $user_id)): ?>
                        <!-- User is a participant, show comment form -->
                        <div class="comment-section">
                            <h5>Comments:</h5>
                            <?php
                            $comments = fetchComments($conn, $discussion['discussion_id']);
                            ?>
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
                            <form action="discussion_page.php" method="POST">
                                <input type="hidden" name="discussion_id" value="<?php echo $discussion['discussion_id']; ?>">
                                <div class="form-group">
                                    <label for="comment_content">Add a comment:</label>
                                    <textarea id="comment_content" name="content" required></textarea>
                                </div>
                                <button type="submit" name="post_comment" class="btn btn-primary">Post Comment</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- User is not a participant, show join button -->
                        <form action="discussion_page.php" method="POST">
                            <input type="hidden" name="discussion_id" value="<?php echo $discussion['discussion_id']; ?>">
                            <button type="submit" name="join_discussion" class="btn btn-secondary">Join Discussion</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No discussions available.</p>
        <?php endif; ?>
    </div>

    <!-- Form to post a new discussion -->
    <h3>Start a New Discussion</h3>
    <form action="discussion_page.php" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required class="form-control">
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required class="form-control"></textarea>
        </div>
        <button type="submit" name="post_discussion"```php
        <button type="submit" name="post_discussion" class="btn btn-primary">Post Discussion</button>
    </form>
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

    .discussion-container {
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

    h4 {
        margin-top: 0;
        color: #555;
    }

    p {
        line-height: 1.6;
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

    .discussion-item, .comment-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .comment-section {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input, .form-group textarea {
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

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
