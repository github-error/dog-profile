<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "animoworld";

$con = mysqli_connect($host, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}



// Create 'Images' folder if it doesn't exist
$folderPath = 'Images/';
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

if (isset($_POST['submit'])) {
    // Access uploaded file
    $file_name = basename($_FILES['fileToUpload']['name']);
    $tempname = $_FILES['fileToUpload']['tmp_name'];
    $folder = $folderPath . $file_name;

    // Sanitize input
    $file_name_escaped = mysqli_real_escape_string($con, $file_name);

    // Insert into database
    $query = mysqli_query($con, "INSERT INTO imagess (file) VALUES ('$file_name_escaped')");

    if (!$query) {
        echo "<h2>Database error: " . mysqli_error($con) . "</h2>";
    }

    // Move the file to server
    if (move_uploaded_file($tempname, $folder)) {
        echo "<h2>File uploaded successfully</h2>";
    } else {
        echo "<h2>File not uploaded successfully</h2>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="postimg.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br><br>
        <input type="submit" value="Submit" name="submit">
    </form>
</body>
</html>
