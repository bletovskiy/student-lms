<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require 'database.php';

// Fetch the current user's courses
try {
    $stmt = $pdo->prepare("SELECT c.course_id, c.course_name, c.description FROM courses c
                           JOIN enrollments e ON c.course_id = e.course_id
                           WHERE e.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch news
try {
    $stmt = $pdo->query("SELECT title, content, created_at FROM news ORDER BY created_at DESC LIMIT 5");
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student LMS - Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
        }
        .nav {
            width: 20%;
            background-color: #f0f0f0;
            padding: 15px;
        }
        .content {
            width: 80%;
            padding: 15px;
        }
        .nav ul {
            list-style-type: none;
            padding: 0;
        }
        .nav ul li {
            margin: 10px 0;
        }
        .nav ul li a {
            text-decoration: none;
            color: #333;
        }
        .nav ul li a:hover {
            text-decoration: underline;
        }
        .course, .news-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <h2>Navigation</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="assignments.php">Assignments</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
            <p>Today's date: <?php echo date('Y-m-d'); ?></p>
            
            <h3>Your Courses</h3>
            <?php if ($courses): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course">
                        <h4><?php echo $course['course_name']; ?></h4>
                        <p><?php echo $course['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You are not enrolled in any courses.</p>
            <?php endif; ?>

            <h3>Latest News</h3>
            <?php if ($news): ?>
                <?php foreach ($news as $news_item): ?>
                    <div class="news-item">
                        <h4><?php echo $news_item['title']; ?></h4>
                        <p><?php echo $news_item['content']; ?></p>
                        <p><small>Posted on: <?php echo $news_item['created_at']; ?></small></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No news available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
