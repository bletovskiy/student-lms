<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $query = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $query->execute([$username, $email, $password, $role]);
    } elseif (isset($_POST['update'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $query = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE user_id = ?");
        $query->execute([$username, $email, $password, $role, $user_id]);
    } elseif (isset($_POST['delete'])) {
        $user_id = $_POST['user_id'];
        $query = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $query->execute([$user_id]);
    }
}

$query = $pdo->query("SELECT * FROM users");
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="assets/adminstyles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>
        <form method="POST">
            <input type="hidden" name="user_id" id="user_id">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="instructor">Instructor</option>
                <option value="student">Student</option>
            </select>
            <button type="submit" name="add">Add User</button>
            <button type="submit" name="update">Update User</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <script>
        function editUser(user) {
            document.getElementById('user_id').value = user.user_id;
            document.getElementById('username').value = user.username;
            document.getElementById('email').value = user.email;
            document.getElementById('password').value = '';
            document.getElementById('role').value = user.role;
        }
    </script>
</body>
</html>
