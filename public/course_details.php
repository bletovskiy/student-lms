<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch the course details
try {
    if (isset($_GET['course_id'])) {
        $course_id = $_GET['course_id'];
        $query = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
        $query->execute([$course_id]);
        $course = $query->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            echo "Course not found.";
            exit;
        }
    } else {
        echo "No course ID provided.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Course Details</h2>
        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
        <p><strong>Course Code:</strong> <?php echo htmlspecialchars($course['course_code']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?></p>
        <p><strong>Instructor:</strong> 
            <?php
            $instructor_id = $course['instructor_id'];
            $query = $pdo->prepare("SELECT full_name FROM users WHERE user_id = ?");
            $query->execute([$instructor_id]);
            $instructor = $query->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($instructor['full_name']);
            ?>
        </p>
        <p><a href="manage_courses.php">Back to Courses</a></p>
    </div>
</body>
</html>
