<?php
session_start();
require_once 'config.php'; // Ensure this path is correct

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created_at = $_POST['created_at'];

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO news (admin_id, title, content, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $admin_id = $_SESSION['user_id']; // Assuming the logged-in user is the admin
    $stmt->bind_param("isss", $admin_id, $title, $content, $created_at);

    if ($stmt->execute()) {
        echo "<p>News posted successfully.</p>";
    } else {
        echo "<p>Error posting news: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
}

// Fetch news articles from the database
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<?php include 'dashboard_header.php'; ?>

<!-- News Page Content -->
<div class="news-page-container">
    <h2>News & Announcements</h2>
    
    <form action="news_page.php" method="post">
        <label for="news_title">News Title:</label>
        <input type="text" id="news_title" name="title" required>
        
        <label for="news_content">Content:</label>
        <textarea id="news_content" name="content" required></textarea>
        
        <label for="news_date">Date:</label>
        <input type="date" id="news_date" name="created_at" required>
        
        <button type="submit">Post News</button>
    </form>
    
    <h3>Recent News</h3>
    <div class="news-list">
        <?php
        if ($result->num_rows > 0) {
            while ($news = $result->fetch_assoc()) {
                echo '<div class="news-item">';
                echo '<h4>' . htmlspecialchars($news['title']) . '</h4>';
                echo '<p>Date: ' . htmlspecialchars($news['created_at']) . '</p>';
                echo '<p>' . htmlspecialchars($news['content']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No news available.</p>';
        }
        ?>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .news-page-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .news-page-container h2 {
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .news-page-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .news-page-container label {
        font-weight: bold;
    }

    .news-page-container input[type="text"],
    .news-page-container textarea,
    .news-page-container input[type="date"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    .news-page-container button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .news-page-container button:hover {
        background-color: #45a049;
    }

    .news-list {
        margin-top: 20px;
    }

    .news-item {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .news-item h4 {
        margin-bottom: 5px;
        color: #333;
    }

    .news-item p {
        margin-bottom: 10px;
    }
</style>
