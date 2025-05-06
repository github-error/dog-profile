<?php
session_start();

// Hardcoded users
$users = [
    'john' => [
        'username' => 'JohnDoe',
        'email' => 'johndoe@example.com',
        'role' => 'Admin',
        'avatar' => 'https://i.pravatar.cc/50'
    ],
    'jane' => [
        'username' => 'JaneSmith',
        'email' => 'janesmith@example.com',
        'role' => 'User',
        'avatar' => 'https://i.pravatar.cc/50?img=2'
    ]
];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['username'];
    if (isset($users[$login])) {
        $_SESSION['user'] = $users[$login];
    } else {
        $error = "Invalid username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="usermanagement.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .user-management-container { max-width: 700px; margin: auto; }
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
    <!-- Login Form -->
    <form method="POST" class="login-form">
        <h2>🔐 Login</h2>
        <input type="text" name="username" placeholder="Enter username (john / jane)" required>
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
<?php else: ?>
    <!-- Logged-in Dashboard -->
    <?php $user = $_SESSION['user']; ?>
    <div class="user-management-container">
        <h2>👥 User Management</h2>
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
                <tr>
                    <td><img src="<?= htmlspecialchars($user['avatar']) ?>" alt="User Avatar"></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="role <?= strtolower($user['role']) ?>"><?= htmlspecialchars($user['role']) ?></span></td>
                    <td>
                        <button class="edit-btn"><i class="fas fa-edit"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <form method="GET">
            <button class="logout-btn" name="logout">Logout</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
