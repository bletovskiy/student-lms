<?php
session_start();
include '../config/database.php';

// Define available roles
$roles = ['student', 'instructor', 'admin'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $user_group = $_POST['user_group'] ?? ''; // Default to empty if not set

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $query = $pdo->prepare("
        INSERT INTO users (username, full_name, email, password_hash, role, user_group)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    try {
        $query->execute([$username, $full_name, $email, $password_hash, $role, $user_group]);
        $message = "User registered successfully.";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Register</h1>
        <nav>
            <a href="login.php">Back to Login</a>
        </nav>
    </header>
    <main>
        <section class="registration-form">
            <h2>Create a New Account</h2>
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" id="full_name" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?php echo htmlspecialchars($r); ?>"><?php echo htmlspecialchars(ucfirst($r)); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="user_group">Group:</label>
                <input type="text" name="user_group" id="user_group">

                <button type="submit">Register</button>
            </form>
        </section>
    </main>
</body>
</html>
