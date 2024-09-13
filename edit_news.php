<?php
require_once 'configs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = $_POST['news_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $mysqli = getDbConnection();
    
    $uploadDir = 'uploads/';
    $imageName = null;

    // Check if the directory exists and is writable
    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        die("Error: Upload directory does not exist or is not writable.");
    }

    if ($image['error'] === UPLOAD_ERR_OK) {
        // Handle image upload
        $imageName = basename($image['name']);
        $uploadFile = $uploadDir . $imageName;
        
        if (!move_uploaded_file($image['tmp_name'], $uploadFile)) {
            die("Error: Failed to move uploaded file.");
        }
    }

    // Prepare the SQL query
    $sql = "UPDATE news SET title = ?, content = ?";
    if ($imageName) {
        $sql .= ", image = ?";
    }
    $sql .= " WHERE news_id = ?";

    $stmt = $mysqli->prepare($sql);

    if ($imageName) {
        $stmt->bind_param("sssi", $title, $content, $imageName, $news_id);
    } else {
        $stmt->bind_param("ssi", $title, $content, $news_id);
    }

    if ($stmt->execute()) {
        echo "News updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} else {
    $news_id = $_GET['id'];
    $mysqli = getDbConnection();
    $stmt = $mysqli->prepare("SELECT * FROM news WHERE news_id = ?");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
</head>
<body>
    <h1>Edit News</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="news_id" value="<?php echo htmlspecialchars($news['news_id']); ?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea><br><br>
        <?php if (!empty($news['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="Image" style="max-width: 200px;"><br><br>
        <?php endif; ?>
        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image"><br><br>
        <input type="submit" value="Update News">
    </form>
</body>
</html>
