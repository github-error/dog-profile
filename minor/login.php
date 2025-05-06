
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Animo World</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Welcome back to <span>Animo World</span></h2>
            <form method="POST" action="">
    <div class="input-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Enter your username" required>
    </div>
    <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="login-btn">Login</button>
    <p class="register-link">New here? <a href="createacct.html">Sign Up</a></p>
</form>

        </div>
    </div>
</body>
</html>
<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $con = new mysqli("localhost", "root", "", "animowrld");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $stmt = $con->prepare("SELECT * FROM accounts WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: project.php");
        exit;
    } else {
        header("Location: createacct.html");
        exit;
    }

    $stmt->close();
    $con->close();
}
?>
