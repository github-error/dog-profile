<?php
session_start();

// Only allow admin users
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: project.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "animowrld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_name = $_POST["event_name"];
    $event_date = $_POST["event_date"];
    $venue = $_POST["venue"];
    $registration_fee = $_POST["registration_fee"];
    $prize_money = $_POST["prize_money"];

    if (empty($event_name) || empty($event_date) || empty($venue) || empty($registration_fee) || empty($prize_money)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO dog_events (event_name, event_date, venue, registration_fee, prize_money) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $event_name, $event_date, $venue, $registration_fee, $prize_money);

        if ($stmt->execute()) {
            $success = "🎉 Event added successfully!";
        } else {
            $error = "❌ Failed to add event: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Event | Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      padding: 40px;
    }
    .form-container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    input, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    button {
      background: #2ecc71;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
    .message {
      text-align: center;
      margin-bottom: 15px;
    }
    .success { color: green; }
    .error { color: red; }
    a.back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
      text-decoration: none;
      color: #3498db;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>➕ Add New Dog Event</h2>

    <?php if ($success): ?>
      <p class="message success"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="event_name" placeholder="Event Name" required>
      <input type="date" name="event_date" required>
      <input type="text" name="venue" placeholder="Venue" required>
      <input type="number" name="registration_fee" placeholder="Registration Fee (₹)" required>
      <input type="number" name="prize_money" placeholder="Prize Money (₹)" required>
      <button type="submit">Add Event</button>
    </form>

    <a class="back-link" href="events.php">← Back to Events</a>
  </div>
</body>
</html>
