<?php
session_start();

// Show all errors (for debugging only - remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to database
$conn = new mysqli("localhost", "root", "", "animowrld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$fullname = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL with placeholders
$sql = "INSERT INTO accounts (name, email, username, password) VALUES (?,?,?,?)";
$stmt = $conn->prepare($sql);

// Check for preparation error
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssss", $fullname, $email, $username, $password);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    header("Location: project.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
