<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "example");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2 style='color: red;'>❌ User not found. Please check your session or database entry.</h2>";
    exit(); // 👈 THIS LINE IS CRITICAL
}

$user = $result->fetch_assoc(); // This is safe now
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile | Animo World</title>
  <link rel="stylesheet" href="profile.css">
  <script defer src="profile.js"></script>
</head>
<body>

<div class="profile-container">
  <!-- Cover Image -->
  <div class="cover-photo">
    <img src="https://addcovers.com/covers/k24idx1kxgvgpti.jpg" alt="Cover Photo">
  </div>

  <!-- Profile Info Section -->
  <div class="profile-info">
    <img src="<?php echo htmlspecialchars($user['profile']); ?>" alt="Profile Picture" class="profile-pic">
    <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
    <p>@<?php echo htmlspecialchars($user['username']); ?></p>
    <p><?php echo htmlspecialchars($user['bio']); ?></p>
    <button class="edit-btn" onclick="openEditModal()">Edit Profile</button>
  </div>

  <!-- Statistics -->
  <div class="profile-stats">
    <div><h3>🐶 <?php echo $user['dogs_registered']; ?></h3><p>Dogs Registered</p></div>
    <div><h3>📸 <?php echo $user['posts']; ?></h3><p>Posts</p></div>
    <div><h3>❤️ <?php echo $user['likes']; ?></h3><p>Likes</p></div>
    <div><h3>👥 <?php echo $user['followers']; ?></h3><p>Followers</p></div>
  </div>

  <!-- Tabs -->
  <div class="profile-tabs">
    <button class="tab active" onclick="switchTab('posts')">📸 Posts</button>
    <button class="tab" onclick="switchTab('about')">ℹ️ About</button>
    <button class="tab" onclick="switchTab('followers')">👥 Followers</button>
    <button class="tab" onclick="switchTab('following')">🔗 Following</button>
  </div>

  <div class="tab-content">
    <div id="posts" class="tab-section active"><p>Recent posts will be displayed here...</p></div>
    <div id="about" class="tab-section"><p><?php echo htmlspecialchars($user['about']); ?></p></div>
    <div id="followers" class="tab-section"><p>List of followers...</p></div>
    <div id="following" class="tab-section"><p>List of following...</p></div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Profile</h2>
    <input type="text" placeholder="Full Name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
    <input type="text" placeholder="Username" value="<?php echo htmlspecialchars($user['username']); ?>">
    <input type="text" placeholder="Bio" value="<?php echo htmlspecialchars($user['bio']); ?>">
    <button class="save-btn">Save Changes</button>
  </div>
</div>

</body>
</html>
