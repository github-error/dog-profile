<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "animoworld";
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

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $input_password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM adminpage WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] !== null && $input_password === $user['password']) {
        $_SESSION['user'] = $user;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .user-management-container { max-width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ccc; }
        .role.admin { color: white; background: darkred; padding: 4px 8px; border-radius: 6px; }
        .role.user { color: white; background: dodgerblue; padding: 4px 8px; border-radius: 6px; }
        .edit-btn, .delete-btn { background: none; border: none; cursor: pointer; font-size: 16px; }
        .logout-btn { margin-top: 20px; padding: 8px 16px; background: #555; color: white; border: none; cursor: pointer; }
        form.login-form { max-width: 300px; margin: auto; margin-top: 50px; text-align: center; }
        form.login-form input { padding: 10px; margin: 10px 0; width: 100%; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['user'])): ?>
    <form method="POST" class="login-form">
        <h2>🔐 Admin Login</h2>
        <input type="text" name="username" placeholder="Enter username" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
<?php else: ?>
    <div class="user-management-container">
        <h2>👥 Admin Panel - User Management</h2>

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
                $stmt = $pdo->query("SELECT * FROM adminpage");
                while ($user = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $avatarPath = !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : "https://source.unsplash.com/random/50x50";
                ?>
                    <tr>
                        <td><img src="<?= $avatarPath ?>" alt="Avatar" width="40" height="40" style="border-radius: 50%;"></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="role <?= strtolower($user['role']) ?>"><?= htmlspecialchars($user['role']) ?></span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <form method="GET">
            <button class="logout-btn" name="logout">Logout</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
