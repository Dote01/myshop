<?php
require_once 'configs.php';

function getNews($search = '', $page = 1, $limit = 10) {
    $mysqli = getDbConnection();
    
    $offset = ($page - 1) * $limit;
    
    // Prepare search query if search term is provided
    $searchQuery = "";
    $params = [];
    $types = "";
    
    if ($search) {
        $searchTerm = '%' . $mysqli->real_escape_string($search) . '%';
        $searchQuery = "WHERE title LIKE ? OR content LIKE ?";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }
    
    // Fetch total count for pagination
    $totalQuery = "SELECT COUNT(*) as total FROM news $searchQuery";
    $totalStmt = $mysqli->prepare($totalQuery);
    if ($search) {
        $totalStmt->bind_param($types, ...$params);
    }
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRow = $totalResult->fetch_assoc();
    $totalCount = $totalRow['total'];
    $totalStmt->close();

    // Prepare main query with LIMIT and OFFSET
    $query = "SELECT * FROM news $searchQuery ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $mysqli->prepare($query);
    
    if ($search) {
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        $stmt->bind_param($types, ...$params);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $news = [];
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }

    $stmt->close();
    $mysqli->close();
    
    return ['news' => $news, 'total' => $totalCount, 'limit' => $limit];
}

// Handle search and pagination
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;

$data = getNews($search, $page, $limit);
$news = $data['news'];
$totalCount = $data['total'];
$limit = $data['limit'];
$totalPages = ceil($totalCount / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for better organization -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            margin: auto;
            max-width: 1200px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .search-form {
            margin: 20px 0;
            text-align: center;
        }
        .search-form input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .news-actions {
            text-align: center;
            margin-bottom: 20px;
        }
        .news-actions a {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        .news-actions a:hover {
            background-color: #218838;
        }
        .news-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .news-item {
            flex: 1 1 calc(33% - 20px);
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .news-item:hover {
            transform: scale(1.02);
        }
        .news-item img {
            width: 100%;
            height: auto;
            display: block;
        }
        .news-title {
            font-size: 1.6em;
            margin: 15px;
            color: #333;
            font-weight: bold;
        }
        .news-date {
            color: #888;
            margin: 0 15px;
            font-size: 0.9em;
        }
        .news-content {
            margin: 15px;
            color: #555;
        }
        .actions {
            margin: 15px;
            text-align: right;
        }
        .actions a {
            text-decoration: none;
            color: #007BFF;
            margin-left: 15px;
            font-weight: bold;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .pagination {
            margin: 30px 0;
            text-align: center;
        }
        .pagination a {
            margin: 0 7px;
            text-decoration: none;
            color: #007BFF;
            font-size: 16px;
        }
        .pagination a:hover {
            text-decoration: underline;
        }
        .pagination .active {
            font-weight: bold;
            color: #0056b3;
        }
        .pagination .disabled {
            color: #ddd;
            pointer-events: none;
        }
    </style>
</head>

<?php include 'dashboard_header.php'; ?>
<body>
    <div class="container">
        <div class="search-form">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search news..." value="<?php echo htmlspecialchars($search); ?>">
                <input type="submit" value="Search">
            </form>
        </div>

        <div class="news-actions">
            <a href="add_news.php">Add News</a>
        </div>

        <div class="news-container">
            <?php foreach ($news as $item): ?>
                <div class="news-item">
                    <?php if (!empty($item['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Image">
                    <?php endif; ?>
                    <div class="news-title"><?php echo htmlspecialchars($item['title']); ?></div>
                    <div class="news-date">Created: <?php echo htmlspecialchars($item['created_at']); ?> | Updated: <?php echo htmlspecialchars($item['updated_at']); ?></div>
                    <div class="news-content"><?php echo nl2br(htmlspecialchars($item['content'])); ?></div>
                    <div class="actions">
                        <a href="edit_news.php?id=<?php echo $item['news_id']; ?>">Edit</a>
                        <a href="delete_news.php?id=<?php echo $item['news_id']; ?>" onclick="return confirm('Are you sure you want to delete this news item?')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php else: ?>
                <span class="disabled">&laquo; Previous</span>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next &raquo;</span>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
