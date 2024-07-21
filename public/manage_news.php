<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $query = $pdo->prepare("INSERT INTO news (title, content) VALUES (?, ?)");
        $query->execute([$title, $content]);
    } elseif (isset($_POST['update'])) {
        $news_id = $_POST['news_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $query = $pdo->prepare("UPDATE news SET title = ?, content = ? WHERE news_id = ?");
        $query->execute([$title, $content, $news_id]);
    } elseif (isset($_POST['delete'])) {
        $news_id = $_POST['news_id'];
        $query = $pdo->prepare("DELETE FROM news WHERE news_id = ?");
        $query->execute([$news_id]);
    }
}

$query = $pdo->query("SELECT * FROM news");
$news_items = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage News</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage News</h2>
        <form method="POST">
            <input type="hidden" name="news_id" id="news_id">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="content">Content:</label>
            <textarea name="content" id="content" required></textarea>
            <button type="submit" name="add">Add News</button>
            <button type="submit" name="update">Update News</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($news_items as $news): ?>
            <tr>
                <td><?php echo htmlspecialchars($news['news_id']); ?></td>
                <td><?php echo htmlspecialchars($news['title']); ?></td>
                <td><?php echo htmlspecialchars($news['content']); ?></td>
                <td>
                    <button onclick="editNews(<?php echo htmlspecialchars(json_encode($news)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="news_id" value="<?php echo htmlspecialchars($news['news_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editNews(news) {
            document.getElementById('news_id').value = news.news_id;
            document.getElementById('title').value = news.title;
            document.getElementById('content').value = news.content;
        }
    </script>
</body>
</html>
