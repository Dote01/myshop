<?php
session_start();
require_once 'config.php';

// Check if user is authorized to post news
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'news_poster') {
    header("Location: ../dashboard/dashboard.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    $sql = "INSERT INTO news (title, content, author_id, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $author_id);

    if ($stmt->execute()) {
        echo "News posted successfully.";
    } else {
        echo "Error posting news.";
    }
}
?>

<form method="POST" action="post_news.php">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="content">Content:</label>
    <textarea name="content" id="content" required></textarea>

    <button type="submit">Post News</button>
</form>
