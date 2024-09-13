<?php
require_once 'configs.php';

$success = false;
$error = '';

if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']);
    
    if ($news_id <= 0) {
        $error = 'Invalid news ID.';
    } else {
        $mysqli = getDbConnection();
        $stmt = $mysqli->prepare("DELETE FROM news WHERE news_id = ?");
        
        if ($stmt === false) {
            $error = 'Failed to prepare statement: ' . $mysqli->error;
        } else {
            $stmt->bind_param("i", $news_id);
            
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Failed to execute query: ' . $stmt->error;
            }
            
            $stmt->close();
        }
        
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h1 class="success">News Deleted Successfully!</h1>
            <p>The news article has been deleted. <a href="news.php">Back to News Feed</a></p>
        <?php else: ?>
            <h1 class="error">Deletion Failed</h1>
            <p><?php echo htmlspecialchars($error); ?></p>
            <p><a href="news.php">Back to News Feed</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
