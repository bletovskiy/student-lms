<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <nav>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_courses.php">Manage Courses</a>
            <a href="manage_enrollments.php">Manage Enrollments</a>
            <a href="manage_assignments.php">Manage Assignments</a>
            <a href="manage_submissions.php">Manage Submissions</a>
            <a href="manage_news.php">Manage News</a>
            <a href="index.php">Back to Home</a>
        </nav>
        <p>Welcome to the admin panel. Use the navigation above to manage various aspects of the platform.</p>
    </div>
</body>
</html>
