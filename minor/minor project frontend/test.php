<?php
// Retrieve form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$gmail = $_POST['gmail'];
$pass = $_POST['password'];
$username = $_POST['username'];

// Database connection
$conn = new mysqli("localhost", "root", "", "animoworld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO acctsofusers (gmail, password, name, username, phone) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $gmail, $pass, $name, $username, $phone);

// Execute the query
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>