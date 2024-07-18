<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <div>
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="manage_assignments.php">Manage Assignments</a></li>
	    <li><a href="manage_enrollments.php">Manage Enrollments</a></li>
	    <li><a href="manage_submissions.php">Manage Submissions</a></li>
            <!-- Add more links for other admin functionalities -->
        </ul>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
