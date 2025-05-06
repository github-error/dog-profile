<?php
session_start();

// DB connection
$host = "localhost";
$dbname = "animowrld";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle login (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $input_password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM adminpage WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $input_password === $admin['password']) {
        $_SESSION['admin'] = $admin;
    } else {
        $error = "Invalid admin credentials!";
    }
}

// Handle new admin creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin']) && isset($_SESSION['admin'])) {
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newPassword = $_POST['new_password'];
    $newAvatar = $_POST['new_avatar'];

    $stmt = $pdo->prepare("INSERT INTO adminpage (username, email, password, avatar) VALUES (:username, :email, :password, :avatar)");
    try {
        $stmt->execute([
            'username' => $newUsername,
            'email' => $newEmail,
            'password' => $newPassword,
            'avatar' => $newAvatar
        ]);
        $success = "New admin created successfully!";
    } catch (PDOException $e) {
        $error = "Error creating admin: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - User Management</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .container { max-width: 900px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ccc; }
        .role.admin { background: darkred; color: white; padding: 4px 8px; border-radius: 6px; }
        .role.user { background: dodgerblue; color: white; padding: 4px 8px; border-radius: 6px; }
        .edit-btn, .delete-btn { background: none; border: none; cursor: pointer; font-size: 16px; }
        .logout-btn { margin-top: 20px; padding: 8px 16px; background: #555; color: white; border: none; cursor: pointer; }
        form.login-form, form.new-admin-form { max-width: 400px; margin: auto; margin-top: 40px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        form input { padding: 10px; margin: 10px 0; width: 100%; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin'])): ?>
    <form method="POST" class="login-form">
        <h2>🔐 Admin Login</h2>
        <input type="text" name="username" placeholder="Admin username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
<?php else: ?>
    <div class="container">
        <h2>👥 Admin Panel - Manage Users</h2>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Admin Creation Form -->
        <form method="POST" class="new-admin-form">
            <h3>➕ Create New Admin</h3>
            <input type="text" name="new_username" placeholder="Username" required>
            <input type="email" name="new_email" placeholder="Email" required>
            <input type="password" name="new_password" placeholder="Password" required>
            <input type="text" name="new_avatar" placeholder="Avatar URL (optional)">
            <button type="submit" name="create_admin">Create Admin</button>
        </form>

        <!-- User Table -->
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = [];

                // Fetch admin users
                $stmtAdmin = $pdo->query("SELECT username, email, avatar, 'admin' AS role FROM adminpage");
                $admins = $stmtAdmin->fetchAll(PDO::FETCH_ASSOC);

                // Fetch normal users
                $stmtUsers = $pdo->query("SELECT username, email, images AS avatar, 'user' AS role FROM accounts");
                $regularUsers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

                // Merge all users
                $users = array_merge($admins, $regularUsers);

                foreach ($users as $user):
                    $avatarPath = !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : "https://source.unsplash.com/random/50x50";
                ?>
                    <tr>
                        <td><img src="<?= $avatarPath ?>" alt="Avatar" width="40" height="40" style="border-radius: 50%;"></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="role <?= strtolower($user['role']) ?>"><?= ucfirst($user['role']) ?></span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="GET">
            <button class="logout-btn" name="logout">Logout</button>
        </form>
        <!-- Back to Dashboard Button -->
<form action="project.php" method="get">
    <button class="logout-btn" type="submit" style="background: #007bff; margin-left: 10px;">⬅ Back to Dashboard</button>
</form>

    </div>
<?php endif; ?>

</body>
</html>
