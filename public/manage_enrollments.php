<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];
        $query = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $query->execute([$user_id, $course_id]);
    } elseif (isset($_POST['update'])) {
        $enrollment_id = $_POST['enrollment_id'];
        $user_id = $_POST['user_id'];
        $course_id = $_POST['course_id'];
        $query = $pdo->prepare("UPDATE enrollments SET user_id = ?, course_id = ? WHERE enrollment_id = ?");
        $query->execute([$user_id, $course_id, $enrollment_id]);
    } elseif (isset($_POST['delete'])) {
        $enrollment_id = $_POST['enrollment_id'];
        $query = $pdo->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
        $query->execute([$enrollment_id]);
    }
}

$query = $pdo->query("SELECT * FROM enrollments");
$enrollments = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Enrollments</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Enrollments</h2>
        <form method="POST">
            <input type="hidden" name="enrollment_id" id="enrollment_id">
            <label for="user_id">User ID:</label>
            <input type="number" name="user_id" id="user_id" required>
            <label for="course_id">Course ID:</label>
            <input type="number" name="course_id" id="course_id" required>
            <button type="submit" name="add">Add Enrollment</button>
            <button type="submit" name="update">Update Enrollment</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Course ID</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($enrollments as $enrollment): ?>
            <tr>
                <td><?php echo htmlspecialchars($enrollment['enrollment_id']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['user_id']); ?></td>
                <td><?php echo htmlspecialchars($enrollment['course_id']); ?></td>
                <td>
                    <button onclick="editEnrollment(<?php echo htmlspecialchars(json_encode($enrollment)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="enrollment_id" value="<?php echo htmlspecialchars($enrollment['enrollment_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editEnrollment(enrollment) {
            document.getElementById('enrollment_id').value = enrollment.enrollment_id;
            document.getElementById('user_id').value = enrollment.user_id;
            document.getElementById('course_id').value = enrollment.course_id;
        }
    </script>
</body>
</html>
