<?php
    
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "animowrld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentUsername = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $newUsername = $_POST['username'];
        $newEmail = $_POST['email'];

        $stmt = $conn->prepare("UPDATE accounts SET username = ?, email = ? WHERE username = ?");
        $stmt->bind_param("sss", $newUsername, $newEmail, $currentUsername);

        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            $success = "Profile updated successfully.";
        } else {
            $error = "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST['update_password']) && !empty($_POST['password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE accounts SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $currentUsername);

        if ($stmt->execute()) {
            $success = "Password updated successfully.";
        } else {
            $error = "Error updating password: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Animo World</title>
    <link rel="stylesheet" href="setting.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="settings-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>⚙️ Settings</h2>
            <ul>
                <li class="tab active" data-tab="profile"><i class="fas fa-user"></i> Profile Settings</li>
                <li class="tab" data-tab="privacy"><i class="fas fa-lock"></i> Privacy & Security</li>
                <li class="tab" data-tab="notifications"><i class="fas fa-bell"></i> Notifications & Alerts</li>
                <li class="tab" data-tab="content"><i class="fas fa-sliders-h"></i> Content Preferences</li>
            </ul>
        </aside>

        <!-- Settings Content -->
        <div class="settings-content">
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST" action="">
                <!-- Profile Settings -->
                <div class="settings-panel active" id="profile">
                    <h2>Profile Settings</h2>
                    <div class="setting-item">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="setting-item">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="save-btn" name="update_profile">Save Changes</button>
                </div>

                <!-- Privacy & Security -->
                <div class="settings-panel" id="privacy">
                    <h2>Privacy & Security</h2>
                    <div class="setting-item">
                        <label>Change Password</label>
                        <input type="password" name="password" placeholder="New password">
                    </div>
                    <button type="submit" class="save-btn" name="update_password">Update Password</button>
                </div>
            </form>

            <!-- Notifications & Alerts -->
            <div class="settings-panel" id="notifications">
                <h2>Notifications & Alerts</h2>
                <div class="setting-item">
                    <label>Email Notifications</label>
                    <input type="checkbox">
                </div>
                <div class="setting-item">
                    <label>Push Notifications</label>
                    <input type="checkbox">
                </div>
                <div class="setting-item">
                    <label>Message Alerts</label>
                    <input type="checkbox">
                </div>
                <button class="save-btn">Save Notifications</button>
            </div>

            <!-- Content Preferences -->
            <div class="settings-panel" id="content">
                <h2>Content Preferences</h2>
                <div class="setting-item">
                    <label>Preferred Feed Type</label>
                    <select>
                        <option>Dogs</option>
                        <option>Cattle</option>
                        <option>Both</option>
                    </select>
                </div>
                <div class="setting-item">
                    <label>Language Preferences</label>
                    <select>
                        <option>English</option>
                        <option>Hindi</option>
                        <option>Spanish</option>
                    </select>
                </div>
                <button class="save-btn">Update Preferences</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll(".tab").forEach(tab => {
            tab.addEventListener("click", function() {
                document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
                document.querySelectorAll(".settings-panel").forEach(panel => panel.classList.remove("active"));

                this.classList.add("active");
                document.getElementById(this.getAttribute("data-tab")).classList.add("active");
            });
        });
    </script>
</body>
</html>
