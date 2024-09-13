<?php
require_once 'configs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $mysqli = getDbConnection();

    // Handle image upload
    $imageName = '';
    if ($image['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($image['name']);
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . $imageName;

        if (!move_uploaded_file($image['tmp_name'], $uploadFile)) {
            echo "Error uploading image.";
            $mysqli->close();
            exit;
        }
    }

    // Prepare and execute the insert query
    $query = "INSERT INTO news (title, content, image, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $title, $content, $imageName);

    if ($stmt->execute()) {
        header("Location: news.php"); // Redirect to news page after successful addition
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for better organization -->
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; }
        .container { width: 90%; margin: auto; max-width: 800px; }
        .form-container { margin: 20px 0; }
        .form-container label { display: block; margin: 10px 0 5px; }
        .form-container input[type="text"], .form-container textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .form-container textarea { resize: vertical; }
        .form-container input[type="file"] { margin: 10px 0; }
        .form-container input[type="submit"] { padding: 10px 20px; border: none; background-color: #007BFF; color: white; border-radius: 4px; cursor: pointer; }
        .form-container input[type="submit"]:hover { background-color: #0056b3; }
    </style>
</head>

<?php include 'dashboard_header.php'; ?>
<body>
    <div class="container">
        <h1>Add News</h1>
        <div class="form-container">
            <form method="post" action="" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" required></textarea>

                <label for="image">Upload Image (optional):</label>
                <input type="file" id="image" name="image">

                <input type="submit" value="Add News">
            </form>
        </div>
    </div>
</body>
</html>
