<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $course_id = $_POST['course_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $query = $pdo->prepare("INSERT INTO assignments (course_id, title, description, due_date) VALUES (?, ?, ?, ?)");
        $query->execute([$course_id, $title, $description, $due_date]);
    } elseif (isset($_POST['update'])) {
        $assignment_id = $_POST['assignment_id'];
        $course_id = $_POST['course_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $query = $pdo->prepare("UPDATE assignments SET course_id = ?, title = ?, description = ?, due_date = ? WHERE assignment_id = ?");
        $query->execute([$course_id, $title, $description, $due_date, $assignment_id]);
    } elseif (isset($_POST['delete'])) {
        $assignment_id = $_POST['assignment_id'];
        $query = $pdo->prepare("DELETE FROM assignments WHERE assignment_id = ?");
        $query->execute([$assignment_id]);
    }
}

$query = $pdo->query("SELECT * FROM assignments");
$assignments = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Assignments</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Assignments</h2>
        <form method="POST">
            <input type="hidden" name="assignment_id" id="assignment_id">
            <label for="course_id">Course ID:</label>
            <input type="number" name="course_id" id="course_id" required>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="due_date">Due Date:</label>
            <input type="datetime-local" name="due_date" id="due_date" required>
            <button type="submit" name="add">Add Assignment</button>
            <button type="submit" name="update">Update Assignment</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Course ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?php echo htmlspecialchars($assignment['assignment_id']); ?></td>
                <td><?php echo htmlspecialchars($assignment['course_id']); ?></td>
                <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                <td><?php echo htmlspecialchars($assignment['description']); ?></td>
                <td><?php echo htmlspecialchars($assignment['due_date']); ?></td>
                <td>
                    <button onclick="editAssignment(<?php echo htmlspecialchars(json_encode($assignment)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['assignment_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editAssignment(assignment) {
            document.getElementById('assignment_id').value = assignment.assignment_id;
            document.getElementById('course_id').value = assignment.course_id;
            document.getElementById('title').value = assignment.title;
            document.getElementById('description').value = assignment.description;
            document.getElementById('due_date').value = assignment.due_date.replace(' ', 'T');
        }
    </script>
</body>
</html>
