<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require '../config/database.php';

// Fetch all enrollments
try {
    $stmt = $pdo->query("SELECT * FROM enrollments");
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Manage Enrollments</h2>
    <table>
        <tr>
            <th>Enrollment ID</th>
            <th>User ID</th>
            <th>Course ID</th>
            <th>Enrollment Date</th>
        </tr>
        <?php foreach ($enrollments as $enrollment): ?>
            <tr>
                <td><?php echo $enrollment['enrollment_id']; ?></td>
                <td><?php echo $enrollment['user_id']; ?></td>
                <td><?php echo $enrollment['course_id']; ?></td>
                <td><?php echo $enrollment['enrollment_date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="admin_panel.php">Back to Admin Panel</a></p>
</body>
</html>
