<?php
// Assuming this file is located in the 'public' directory
require_once('../config/database.php'); // Adjust the path as necessary

// Example data for courses and announcements (replace with actual data retrieval)
$courses = [
    ['id' => 1, 'title' => 'Introduction to Programming', 'description' => 'Learn the basics of programming languages.'],
    ['id' => 2, 'title' => 'Web Development Fundamentals', 'description' => 'Master essential skills for web development.'],
    ['id' => 3, 'title' => 'Database Design and Management', 'description' => 'Understand the principles of database design.']
];

$announcements = [
    ['title' => 'Important Notice', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
    ['title' => 'New Course Added', 'content' => 'Check out our latest course offerings!']
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student LMS - Home</title>
    <link rel="stylesheet" href="assets/styles.css"> <!-- Adjust path as needed -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Welcome to Student LMS</h1>
        </div>
    </header>
    <main>
        <section class="container">
            <h2>Featured Courses</h2>
            <div class="courses">
                <?php foreach ($courses as $course): ?>
                <div class="course">
                    <h3><?php echo $course['title']; ?></h3>
                    <p><?php echo $course['description']; ?></p>
                    <a href="courses.php?id=<?php echo $course['id']; ?>" class="btn">Explore Course</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="container">
            <h2>Announcements</h2>
            <div class="announcements">
                <?php foreach ($announcements as $announcement): ?>
                <div class="announcement">
                    <h3><?php echo $announcement['title']; ?></h3>
                    <p><?php echo $announcement['content']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Student LMS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
