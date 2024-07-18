<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require '../config/database.php';

// Fetch all courses
try {
    $stmt = $pdo->query("SELECT * FROM courses");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        header('Location: manage_courses.php');
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
    <title>Manage Courses</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Manage Courses</h2>
    <table>
        <tr>
            <th>Course ID</th>
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Description</th>
            <th>Instructor ID</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo $course['course_id']; ?></td>
                <td><?php echo $course['course_code']; ?></td>
                <td><?php echo $course['course_name']; ?></td>
                <td><?php echo $course['description']; ?></td>
                <td><?php echo $course['instructor_id']; ?></td>
                <td>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                        <input type="submit" name="delete_course" value="Delete" onclick="return confirm('Are you sure you want to delete this course?')">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="admin_panel.php">Back to Admin Panel</a></p>
</body>
</html>
