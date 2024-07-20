<?php
session_start();

// Include the database connection file
require_once('../config/database.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the user exists
    try {
        $sql = "SELECT user_id, password_hash, role FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id();

            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard or homepage
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log the error
        $error = "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Example: Link to your CSS file -->
</head>
<body>
    <h2>Login</h2>
    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
    <br>
    <a href="register.php">Register</a> <!-- Example: Link to registration page -->
</body>
</html>
