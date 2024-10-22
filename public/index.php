<?php
session_start();
include '../config/database.php';

// Check if the user is logged in and fetch user information
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query_user = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
    $query_user->execute([$user_id]);
    $user = $query_user->fetch(PDO::FETCH_ASSOC);

    // Check if the user role is admin
    $is_admin = ($user['role'] === 'admin');
} else {
    $is_admin = false;
}

// Fetch latest news
$query_news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
$news_items = $query_news->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses
$query_courses = $pdo->query("SELECT * FROM courses");
$courses = $query_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <nav>
            <a href="logout.php">Logout</a>
            <?php if ($is_admin): ?>
                <a href="admin_panel.php" class="admin-panel-button">Admin Panel</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <aside class="sidebar">
            <h2>Navigation</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </aside>
        <main>
            <section class="info-section">
                <h2>Latest News</h2>
                <div class="card-container">
                    <?php foreach ($news_items as $news): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo htmlspecialchars($news['content']); ?></p>
                        <small><?php echo htmlspecialchars($news['created_at']); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="info-section">
                <h2>Courses</h2>
                <div class="card-container">
                    <?php foreach ($courses as $course): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                        <small>Instructor: <?php echo htmlspecialchars($course['instructor_id']); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
