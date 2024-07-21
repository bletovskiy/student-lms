<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $assignment_id = $_POST['assignment_id'];
        $user_id = $_POST['user_id'];
        $file_path = $_POST['file_path'];
        $score = $_POST['score'];
        $query = $pdo->prepare("INSERT INTO submissions (assignment_id, user_id, file_path, score) VALUES (?, ?, ?, ?)");
        $query->execute([$assignment_id, $user_id, $file_path, $score]);
    } elseif (isset($_POST['update'])) {
        $submission_id = $_POST['submission_id'];
        $assignment_id = $_POST['assignment_id'];
        $user_id = $_POST['user_id'];
        $file_path = $_POST['file_path'];
        $score = $_POST['score'];
        $query = $pdo->prepare("UPDATE submissions SET assignment_id = ?, user_id = ?, file_path = ?, score = ? WHERE submission_id = ?");
        $query->execute([$assignment_id, $user_id, $file_path, $score, $submission_id]);
    } elseif (isset($_POST['delete'])) {
        $submission_id = $_POST['submission_id'];
        $query = $pdo->prepare("DELETE FROM submissions WHERE submission_id = ?");
        $query->execute([$submission_id]);
    }
}

$query = $pdo->query("SELECT * FROM submissions");
$submissions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Submissions</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Submissions</h2>
        <form method="POST">
            <input type="hidden" name="submission_id" id="submission_id">
            <label for="assignment_id">Assignment ID:</label>
            <input type="number" name="assignment_id" id="assignment_id" required>
            <label for="user_id">User ID:</label>
            <input type="number" name="user_id" id="user_id" required>
            <label for="file_path">File Path:</label>
            <input type="text" name="file_path" id="file_path" required>
            <label for="score">Score:</label>
            <input type="number" step="0.01" name="score" id="score" required>
            <button type="submit" name="add">Add Submission</button>
            <button type="submit" name="update">Update Submission</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Assignment ID</th>
                <th>User ID</th>
                <th>File Path</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?php echo htmlspecialchars($submission['submission_id']); ?></td>
                <td><?php echo htmlspecialchars($submission['assignment_id']); ?></td>
                <td><?php echo htmlspecialchars($submission['user_id']); ?></td>
                <td><?php echo htmlspecialchars($submission['file_path']); ?></td>
                <td><?php echo htmlspecialchars($submission['score']); ?></td>
                <td>
                    <button onclick="editSubmission(<?php echo htmlspecialchars(json_encode($submission)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="submission_id" value="<?php echo htmlspecialchars($submission['submission_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editSubmission(submission) {
            document.getElementById('submission_id').value = submission.submission_id;
            document.getElementById('assignment_id').value = submission.assignment_id;
            document.getElementById('user_id').value = submission.user_id;
            document.getElementById('file_path').value = submission.file_path;
            document.getElementById('score').value = submission.score;
        }
    </script>
</body>
</html>
