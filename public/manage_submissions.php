<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require '../config/database.php';

// Fetch all submissions
try {
    $stmt = $pdo->query("SELECT * FROM submissions");
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Submissions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Manage Submissions</h2>
    <table>
        <tr>
            <th>Submission ID</th>
            <th>Assignment ID</th>
            <th>User ID</th>
            <th>Submission Date</th>
            <th>File Path</th>
            <th>Score</th>
        </tr>
        <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?php echo $submission['submission_id']; ?></td>
                <td><?php echo $submission['assignment_id']; ?></td>
                <td><?php echo $submission['user_id']; ?></td>
                <td><?php echo $submission['submission_date']; ?></td>
                <td><?php echo $submission['file_path']; ?></td>
                <td><?php echo $submission['score']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="admin_panel.php">Back to Admin Panel</a></p>
</body>
</html>
