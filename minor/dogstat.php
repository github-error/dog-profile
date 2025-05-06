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

// Get user ID
$user_stmt = $conn->prepare("SELECT user_id FROM accounts WHERE username = ?");
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_id = $user['user_id'];

// Get dogs owned by user
$dog_stmt = $conn->prepare("
  SELECT d.dog_name, d.breed, d.age, d.points, d.achievements, d.image
  FROM dogs d
  WHERE d.owner_id = ?
");
$dog_stmt->bind_param("i", $user_id);
$dog_stmt->execute();
$dogs = $dog_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Dog Stats | Animo World</title>
  <link rel="stylesheet" href="dogstats.css">
</head>
<body>
  <div class="dogstats-container">
    <h1>🐶 Your Dog Stats</h1>

    <?php if ($dogs->num_rows === 0): ?>
      <p>You haven’t registered any dogs yet.</p>
    <?php else: ?>
      <div class="dog-cards">
        <?php while ($dog = $dogs->fetch_assoc()): ?>
          <div class="dog-card">
            <img src="<?= htmlspecialchars($dog['image'] ?? 'default_dog.jpg') ?>" alt="Dog Image" class="dog-image">
            <h2><?= htmlspecialchars($dog['dog_name']) ?></h2>
            <p><strong>Breed:</strong> <?= htmlspecialchars($dog['breed']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($dog['age']) ?> years</p>
            <p><strong>Achievements:</strong> <?= nl2br(htmlspecialchars($dog['achievements'] ?? 'None')) ?></p>
            <p><strong>Points:</strong> <?= htmlspecialchars($dog['points']) ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      padding: 30px;
    }
    .dogstats-container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .dog-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }
    .dog-card {
      background: #fdfdfd;
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s;
    }
    .dog-card:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .dog-image {
      width: 100%;
      max-height: 180px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .dog-card p {
      margin: 8px 0;
      color: #555;
    }
  </style>
</body>
</html>


 

