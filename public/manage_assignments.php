<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require '../config/database.php';

// Fetch all assignments
try {
    $stmt = $pdo->query("SELECT * FROM assignments");
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle assignment deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_assignment'])) {
    $assignment_id = $_POST['assignment_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM assignments WHERE assignment_id = :assignment_id");
        $stmt->bindParam(':assignment_id', $assignment_id);
        $stmt->execute();
        header('Location: manage_assignments.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Manage Assignments</h2>
    <table>
        <tr>
            <th>Assignment ID</th>
            <th>Course ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?php echo $assignment['assignment_id']; ?></td>
                <td><?php echo $assignment['course_id']; ?></td>
                <td><?php echo $assignment['title']; ?></td>
                <td><?php echo $assignment['description']; ?></td>
                <td><?php echo $assignment['due_date']; ?></td>
                <td>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="assignment_id" value="<?php echo $assignment['assignment_id']; ?>">
                        <input type="submit" name="delete_assignment" value="Delete" onclick="return confirm('Are you sure you want to delete this assignment?')">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="admin_panel.php">Back to Admin Panel</a></p>
</body>
</html>
