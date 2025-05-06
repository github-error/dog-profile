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

$username = $_SESSION['username'];

// Fetch user info
$sql = "SELECT user_id, username, images, name, bio, dogsregistered FROM accounts WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2 style='color: red;'>❌ User not found.</h2>";
    exit();
}

$user = $result->fetch_assoc();
$user_id = $user['user_id'];
$old_cover = $user['images'] ?? null;

// Handle profile update
if (isset($_POST['saveProfile'])) {
    $newName = trim($_POST['name']);
    $newUsername = trim($_POST['username']);
    $newBio = trim($_POST['bio']);

    if (!empty($newName) && !empty($newUsername)) {
        $updateStmt = $conn->prepare("UPDATE accounts SET name = ?, username = ?, bio = ? WHERE user_id = ?");
        $updateStmt->bind_param("sssi", $newName, $newUsername, $newBio, $user_id);
        $updateStmt->execute();

        $_SESSION['username'] = $newUsername;
        $username = $newUsername;
    }

    // Refresh user data
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $old_cover = $user['images'] ?? null;
}

// Update dogsregistered
if (isset($_POST['updateDogs'])) {
    $dogs = intval($_POST['dogsregistered']);
    $updateStmt = $conn->prepare("UPDATE accounts SET dogsregistered = ? WHERE user_id = ?");
    $updateStmt->bind_param("ii", $dogs, $user_id);
    $updateStmt->execute();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $old_cover = $user['images'] ?? null;
}

// Handle cover photo upload
if (isset($_FILES['coverUpload'])) {
    $folderPath = 'cover_uploads/';
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    $fileName = basename($_FILES['coverUpload']['name']);
    $tempName = $_FILES['coverUpload']['tmp_name'];
    $newCoverPath = $folderPath . uniqid() . '_' . $fileName;

    if (move_uploaded_file($tempName, $newCoverPath)) {
        if ($old_cover && file_exists($old_cover)) {
            unlink($old_cover);
        }

        $updateStmt = $conn->prepare("UPDATE accounts SET images = ? WHERE username = ?");
        $updateStmt->bind_param("ss", $newCoverPath, $username);
        $updateStmt->execute();

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }
}

// Fetch posts
$post_query = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$post_query->bind_param("i", $user_id);
$post_query->execute();
$posts = $post_query->get_result();

// Count likes
$like_query = $conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE user_id = ?");
$like_query->bind_param("i", $user_id);
$like_query->execute();
$like_result = $like_query->get_result()->fetch_assoc();
$total_likes = $like_result['total_likes'] ?? 0;

// Get followers
$follower_query = $conn->prepare("
  SELECT a.username, a.name, a.images
  FROM followers f
  JOIN accounts a ON f.follower_id = a.user_id
  WHERE f.user_id = ?
");
$follower_query->bind_param("i", $user_id);
$follower_query->execute();
$followers = $follower_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile | Animo World</title>
  <link rel="stylesheet" href="profile.css">
  <script defer src="profile.js"></script>
</head>
<body>
<div class="profile-container">

  <div class="cover-photo">
    <img src="<?= htmlspecialchars($user['images'] ?? 'default_cover.jpg') ?>" alt="Cover Photo">
  </div>

  <div class="profile-info">
    <img src="<?= htmlspecialchars($user['images']) ?>" class="profile-pic" alt="Profile">
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p>@<?= htmlspecialchars($user['username']) ?></p>
    <p><?= htmlspecialchars($user['bio']) ?></p>
    <button class="edit-btn" onclick="openEditModal()">Edit Profile</button>
  </div>

  <div class="profile-stats">
    <div>
      <h3>🐶 <?= htmlspecialchars($user['dogsregistered'] ?? 0) ?></h3>
      <p>Dogs Registered</p>
      <form method="post">
        <input type="number" name="dogsregistered" value="<?= htmlspecialchars($user['dogsregistered'] ?? 0) ?>" min="0" required>
        <button type="submit" name="updateDogs">Update</button>
      </form>
    </div>
    <div><h3>📸 <?= $posts->num_rows ?></h3><p>Posts</p></div>
    <div><h3>❤️ <?= $total_likes ?></h3><p>Likes</p></div>
    <div><h3>👥 <?= $followers->num_rows ?></h3><p>Followers</p></div>
  </div>

  <div class="profile-tabs">
    <button class="tab active" onclick="switchTab('posts')">📸 Posts</button>
    <button class="tab" onclick="switchTab('about')">ℹ️ About</button>
    <button class="tab" onclick="switchTab('followers')">👥 Followers</button>
    <button class="tab" onclick="switchTab('following')">🔗 Following</button>
  </div>

  <div class="tab-content">
    <div id="posts" class="tab-section active">
      <?php if ($posts->num_rows === 0): ?>
        <p>No posts yet.</p>
      <?php else: ?>
        <?php while ($post = $posts->fetch_assoc()): ?>
          <div class="post-card">
            <?php if (!empty($post['image'])): ?>
              <img src="<?= htmlspecialchars($post['image']) ?>" class="post-image" alt="Post Image">
            <?php endif; ?>
            <p><?= htmlspecialchars($post['content']) ?></p>
            <small>👍 <?= $post['likes'] ?> likes</small>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <div id="about" class="tab-section">
      <p><?= htmlspecialchars($user['bio']) ?></p>
    </div>

    <div id="followers" class="tab-section">
      <?php if ($followers->num_rows === 0): ?>
        <p>No followers yet.</p>
      <?php else: ?>
        <ul class="followers-list">
          <?php while ($f = $followers->fetch_assoc()): ?>
            <li class="follower-item">
              <img src="<?= htmlspecialchars($f['images']) ?>" class="follower-pic" alt="Follower">
              <div>
                <strong><?= htmlspecialchars($f['name']) ?></strong><br>
                @<?= htmlspecialchars($f['username']) ?>
              </div>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div id="following" class="tab-section">
      <p>List of following coming soon...</p>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Profile</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Full Name" required>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Username" required>
      <input type="text" name="bio" value="<?= htmlspecialchars($user['bio']) ?>" placeholder="Bio">
      <input type="file" name="coverUpload" accept="image/*">
      <button type="submit" name="saveProfile">Save Changes</button>
    </form>
  </div>
</div>

<script>
function switchTab(tabId) {
  document.querySelectorAll('.tab-section').forEach(section => section.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
  document.getElementById(tabId).classList.add('active');
  event.currentTarget.classList.add('active');
}
function openEditModal() {
  document.getElementById("editModal").style.display = "block";
}
function closeEditModal() {
  document.getElementById("editModal").style.display = "none";
}
</script>

<style>
.post-card { background-color: #f8f8f8; padding: 10px; margin-bottom: 10px; border-radius: 8px; }
.post-image { width: 100%; max-height: 300px; object-fit: cover; border-radius: 6px; margin-bottom: 8px; }
.followers-list { list-style: none; padding: 0; }
.follower-item { display: flex; align-items: center; margin-bottom: 10px; }
.follower-pic { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
.modal-content { background-color: #fff; margin: 10% auto; padding: 20px; width: 90%; max-width: 400px; border-radius: 8px; }
.close { float: right; font-size: 20px; cursor: pointer; }
</style>

</body>
</html>
