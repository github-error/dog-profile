<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Database connection
$conn = new mysqli("localhost", "root", "", "animowrld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch upcoming events
$today = date("Y-m-d");
$stmt = $conn->prepare("SELECT * FROM dog_events WHERE event_date >= ? ORDER BY event_date ASC");
$stmt->bind_param("s", $today);
$stmt->execute();
$events = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upcoming Dog Shows | Animo World</title>
  <link rel="stylesheet" href="events.css">
</head>
<body>
  <div class="events-container">
    <h1>🐕 Upcoming Dog Shows</h1>

    <!-- Add Event Button for Admins -->
    <?php if ($isAdmin): ?>
      <div style="text-align: center; margin-bottom: 20px;">
        <a href="add_event.php" style="padding: 10px 20px; background: #2ecc71; color: white; text-decoration: none; border-radius: 6px;">➕ Add Event</a>
      </div>
    <?php endif; ?>

    <!-- Events List -->
    <?php if ($events->num_rows === 0): ?>
      <p>No upcoming events found. Here's an example:</p>
      <div class="event-card">
        <h2>Golden Retriever Fest 2025</h2>
        <p><strong>📅 Date:</strong> 2025-06-15</p>
        <p><strong>📍 Venue:</strong> Central Park Arena, Mumbai</p>
        <p><strong>📝 Registration Fee:</strong> ₹500</p>
        <p><strong>🏆 Prize Money:</strong> ₹10,000</p>
        <?php if ($isAdmin): ?>
          <a href="edit_event.php?id=example" style="color: #2980b9; text-decoration: none;">✏️ Edit</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <?php while ($event = $events->fetch_assoc()): ?>
        <div class="event-card">
          <h2><?= htmlspecialchars($event['event_name']) ?></h2>
          <p><strong>📅 Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
          <p><strong>📍 Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
          <p><strong>📝 Registration Fee:</strong> ₹<?= htmlspecialchars($event['registration_fee']) ?></p>
          <p><strong>🏆 Prize Money:</strong> ₹<?= htmlspecialchars($event['prize_money']) ?></p>
          <?php if ($isAdmin): ?>
            <a href="edit_event.php?id=<?= $event['id'] ?>" style="color: #2980b9; text-decoration: none;">✏️ Edit</a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }
    .events-container {
      max-width: 800px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .event-card {
      background: #fafafa;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      border-left: 5px solid #3498db;
    }
    .event-card h2 {
      margin-top: 0;
      color: #34495e;
    }
    .event-card p {
      margin: 5px 0;
    }
    .event-card a {
      display: inline-block;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</body>
</html>
