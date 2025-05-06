<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$con = new mysqli("localhost", "root", "", "animowrld");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $target = "Images/" . basename($image);

    $stmt = $con->prepare("SELECT user_id FROM accounts WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'] ?? 0;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $con->prepare("INSERT INTO post (user_id, image, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $user_id, $image);
        $stmt->execute();
        header("Location: sharepost.php"); // Redirect to posts page
        exit();
    } else {
        echo "Image upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Post</title>
</head>
<body>
    <h2>Upload a New Dog Post</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
