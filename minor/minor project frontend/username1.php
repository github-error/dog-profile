<?php
// database connection
$conn = new mysqli("localhost", "root", "", "animoworld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or show an error
    header("Location: dashboard.html");
    exit;
}

// Handle profile settings form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_profile"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $profile_picture = ""; // Handle file upload logic here if needed

    $sql = "UPDATE users SET username=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $user_id);
    $stmt->execute();
}

// You can add similar blocks for privacy, notifications, and content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Animo World</title>
    <link rel="stylesheet" href="setting.css">
</head>
<body>
    <div class="settings-container">
        <aside class="sidebar">
            <h2>⚙️ Settings</h2>
            <ul>
                <li class="tab active" data-tab="profile">Profile Settings</li>
                <li class="tab" data-tab="privacy">Privacy & Security</li>
                <li class="tab" data-tab="notifications">Notifications & Alerts</li>
                <li class="tab" data-tab="content">Content Preferences</li>
            </ul>
        </aside>

        <div class="settings-content">
            <div class="settings-panel active" id="profile">
                <h2>Profile Settings</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="setting-item">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture">
                    </div>
                    <div class="setting-item">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username">
                    </div>
                    <div class="setting-item">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email">
                    </div>
                    <button class="save-btn" type="submit" name="save_profile">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll(".tab").forEach(tab => {
            tab.addEventListener("click", function () {
                document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
                document.querySelectorAll(".settings-panel").forEach(panel => panel.classList.remove("active"));
                this.classList.add("active");
                document.getElementById(this.getAttribute("data-tab")).classList.add("active");
            });
        });
    </script>
</body>
</html>


