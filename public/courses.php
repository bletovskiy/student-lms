<?php
session_start();
include '../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch courses
$query_courses = $pdo->query("SELECT * FROM courses");
$courses = $query_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Courses</h1>
        <nav>
            <a href="logout.php">Logout</a>
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
                <h2>Available Courses</h2>
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
