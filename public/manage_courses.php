<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add'])) {
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $instructor_id = $_POST['instructor_id'];
            $query = $pdo->prepare("INSERT INTO courses (course_code, course_name, description, instructor_id) VALUES (?, ?, ?, ?)");
            $query->execute([$course_code, $course_name, $description, $instructor_id]);
        } elseif (isset($_POST['update'])) {
            $course_id = $_POST['course_id'];
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $instructor_id = $_POST['instructor_id'];
            $query = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, description = ?, instructor_id = ? WHERE course_id = ?");
            $query->execute([$course_code, $course_name, $description, $instructor_id, $course_id]);
        } elseif (isset($_POST['delete'])) {
            $course_id = $_POST['course_id'];
            $query = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
            $query->execute([$course_id]);
        }
    }

    $query = $pdo->query("SELECT * FROM courses");
    $courses = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $pdo->query("SELECT user_id, full_name FROM users WHERE role = 'instructor'");
    $instructors = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Courses</h2>
        <form method="POST">
            <input type="hidden" name="course_id" id="course_id">
            <label for="course_code">Course Code:</label>
            <input type="text" name="course_code" id="course_code" required>
            <label for="course_name">Course Name:</label>
            <input type="text" name="course_name" id="course_name" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="instructor_id">Instructor:</label>
            <select name="instructor_id" id="instructor_id" required>
                <option value="">Select an instructor</option>
                <?php foreach ($instructors as $instructor): ?>
                    <option value="<?php echo htmlspecialchars($instructor['user_id']); ?>">
                        <?php echo htmlspecialchars($instructor['full_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="add">Add Course</button>
            <button type="submit" name="update">Update Course</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Instructor</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                <td><?php echo htmlspecialchars($course['description']); ?></td>
                <td>
                    <?php
                    $instructor_id = $course['instructor_id'];
                    $query = $pdo->prepare("SELECT full_name FROM users WHERE user_id = ?");
                    $query->execute([$instructor_id]);
                    $instructor = $query->fetch(PDO::FETCH_ASSOC);
                    echo htmlspecialchars($instructor['full_name']);
                    ?>
                </td>
                <td>
                    <a href="course_details.php?course_id=<?php echo htmlspecialchars($course['course_id']); ?>">View Details</a>
                    <button onclick="editCourse(<?php echo htmlspecialchars(json_encode($course)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editCourse(course) {
            document.getElementById('course_id').value = course.course_id;
            document.getElementById('course_code').value = course.course_code;
            document.getElementById('course_name').value = course.course_name;
            document.getElementById('description').value = course.description;
            document.getElementById('instructor_id').value = course.instructor_id;
        }
    </script>
</body>
</html>
