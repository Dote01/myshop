<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

function handleSqlError($stmt) {
    // Logging error details might be useful
    error_log("SQL Error: " . $stmt->error);
    die("An error occurred. Please try again later.");
}

function handlePostRequest($conn, $user_id) {
    $message = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['post_discussion'])) {
            handlePostDiscussion($conn, $user_id);
        } elseif (isset($_POST['join_discussion'])) {
            handleJoinDiscussion($conn, $user_id);
        } elseif (isset($_POST['post_comment'])) {
            handlePostComment($conn, $user_id);
        } elseif (isset($_POST['like']) || isset($_POST['dislike'])) {
            handleReaction($conn, $user_id);
        }
    }

    return $message;
}

function handlePostDiscussion($conn, $user_id) {
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
            addParticipant($conn, $discussion_id, $user_id);
            $message = "Discussion posted successfully!";
        } else {
            $message = "Error posting discussion: " . $stmt->error;
        }
    }
    return $message;
}

function addParticipant($conn, $discussion_id, $user_id) {
    $sql = "INSERT INTO discussion_participants (discussion_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("ii", $discussion_id, $user_id);
    $stmt->execute();
}

function handleJoinDiscussion($conn, $user_id) {
    $discussion_id = $_POST['discussion_id'];

    if (isParticipant($conn, $discussion_id, $user_id)) {
        $message = "You are already a participant of this discussion.";
    } else {
        addParticipant($conn, $discussion_id, $user_id);
        $message = "You have joined the discussion!";
    }
    return $message;
}

function handlePostComment($conn, $user_id) {
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
    return $message;
}

function handleReaction($conn, $user_id) {
    $comment_id = $_POST['comment_id'];
    $reaction_type = isset($_POST['like']) ? 'like' : 'dislike';

    $sql = "SELECT * FROM comment_reactions WHERE comment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        handleSqlError($conn);
    }
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE comment_reactions SET reaction_type = ? WHERE comment_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            handleSqlError($conn);
        }
        $stmt->bind_param("sii", $reaction_type, $comment_id, $user_id);
        $message = $stmt->execute() ? "Reaction updated successfully!" : "Error updating reaction: " . $stmt->error;
    } else {
        $sql = "INSERT INTO comment_reactions (comment_id, user_id, reaction_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            handleSqlError($conn);
        }
        $stmt->bind_param("iis", $comment_id, $user_id, $reaction_type);
        $message = $stmt->execute() ? "Reaction added successfully!" : "Error adding reaction: " . $stmt->error;
    }
    return $message;
}

function fetchDiscussions($conn) {
    $sql = "SELECT d.discussion_id, d.title, d.content, d.created_at, d.status, u.username 
            FROM discussions d 
            JOIN users u ON d.user_id = u.user_id 
            ORDER BY d.created_at DESC";
    $result = $conn->query($sql);
    if ($result === false) {
        handleSqlError($conn);
    }
    return $result;
}

function fetchComments($conn, $discussion_id) {
    $sql = "SELECT c.comment_id, c.content, c.created_at, u.username,
                   (SELECT COUNT(*) FROM comment_reactions WHERE comment_id = c.comment_id AND reaction_type = 'like') AS likes,
                   (SELECT COUNT(*) FROM comment_reactions WHERE comment_id = c.comment_id AND reaction_type = 'dislike') AS dislikes
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

$message = handlePostRequest($conn, $user_id);
$discussions = fetchDiscussions($conn);
?>
<?php include 'dashboard_header.php'; ?>
<head>
    <!-- Other head elements -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container */
        .discussion-container {
            max-width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Headings */
        h2, h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        h4 {
            color: #555;
            margin-top: 0;
        }

        /* Text */
        p {
            line-height: 1.8;
            color: #666;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid transparent;
            font-size: 16px;
            background-color: #e7f0ff;
            color: #31708f;
        }

        /* Discussion and Comment Items */
        .discussion-item, .comment-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .discussion-item:last-child, .comment-item:last-child {
            border-bottom: none;
        }

        /* Comment Section */
        .comment-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
            font-size: 20px; /* Adjust icon size */
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            border: none; /* Remove default border */
        }

        .btn i {
            margin: 0; /* Remove default margin for icons */
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        /* Gold Button */
        .btn-primary {
            background-color: #ffd700; /* Gold color */
            color: #333; /* Dark text color for contrast */
        }

        .btn-primary:hover {
            background-color: #e5c100; /* Slightly darker gold for hover effect */
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        /* Comment Actions */
        .comment-actions {
            margin-top: 10px;
        }

        .comment-actions button {
            margin-right: 5px;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .discussion-container {
                padding: 15px;
                margin: 15px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 18px; /* Adjust icon size for smaller screens */
            }

            .form-group input, .form-group textarea {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .discussion-container {
                padding: 10px;
                margin: 10px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 16px; /* Adjust icon size for very small screens */
            }

            .form-group input, .form-group textarea {
                font-size: 12px;
            }
        }
    </style>
</head>

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
                    <p>Status: <span class="status-label"><?php echo htmlspecialchars($discussion['status']); ?></span></p>

                    <?php if (isParticipant($conn, $discussion['discussion_id'], $user_id)): ?>
                        <div class="comments-section">
                            <h4>Comments</h4>
                            <?php $comments = fetchComments($conn, $discussion['discussion_id']); ?>
                            <?php if ($comments->num_rows > 0): ?>
                                <?php while ($comment = $comments->fetch_assoc()): ?>
                                    <div class="comment-item">
                                        <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong> - <?php echo htmlspecialchars($comment['created_at']); ?></p>
                                        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                        <p>
                                            <span class="reaction-count">Likes: <?php echo htmlspecialchars($comment['likes']); ?></span> | 
                                            <span class="reaction-count">Dislikes: <?php echo htmlspecialchars($comment['dislikes']); ?></span>
                                        </p>
                                        <div class="comment-actions">
                                            <form action="discussion_page.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                                <button type="submit" name="like" class="btn btn-success" title="Like">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </button>
                                                <button type="submit" name="dislike" class="btn btn-danger" title="Dislike">
                                                    <i class="fas fa-thumbs-down"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No comments yet.</p>
                            <?php endif; ?>
                            <div class="comment-section">
                                <form action="discussion_page.php" method="POST">
                                    <input type="hidden" name="discussion_id" value="<?php echo $discussion['discussion_id']; ?>">
                                    <textarea name="content" rows="4" placeholder="Add a comment..." required></textarea>
                                    <button type="submit" name="post_comment" class="btn btn-primary">Post Comment</button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="join-discussion">
                            <form action="discussion_page.php" method="POST">
                                <input type="hidden" name="discussion_id" value="<?php echo $discussion['discussion_id']; ?>">
                                <button type="submit" name="join_discussion" class="btn btn-primary">Join Discussion</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No discussions found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>
