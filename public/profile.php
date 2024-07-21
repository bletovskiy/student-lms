<?php
session_start();
include '../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$query_user = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$query_user->execute([$user_id]);
$user = $query_user->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Profile</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <aside class="sidebar">
            <h2>Navigation</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </aside>
        <main>
            <section class="info-section">
                <h2>Your Profile</h2>
                <div class="card">
                    <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
                    <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>
                    <p>User Group: <?php echo htmlspecialchars($user['user_group']); ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
