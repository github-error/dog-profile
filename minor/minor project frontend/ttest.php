<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "animoworld";

// Connect to database
$con = mysqli_connect($host, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $imgName = $_FILES["image"]["name"];
    $imgTmp = $_FILES["image"]["tmp_name"];
    $targetDir = "Images/";

    // Optional: Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

    if (in_array($ext, $allowedTypes)) {
        $newName = uniqid() . "." . $ext;
        $targetPath = $targetDir . $newName;

        if (move_uploaded_file($imgTmp, $targetPath)) {
            // Save file info to database
            $stmt = $con->prepare("INSERT INTO images (file) VALUES (?)");
            $stmt->bind_param("s", $newName);
            $stmt->execute();
        } else {
            echo "Upload failed.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}
?>

<!-- HTML Form to Upload Image -->
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <button type="submit">Upload Image</button>
</form>

<hr>

<!-- Display Images -->
<?php
$res = mysqli_query($con, "SELECT * FROM images ORDER BY id DESC");
while ($row = mysqli_fetch_array($res)) {
    $imgPath = 'Images/' . htmlspecialchars($row['file']);
    echo "<img src='$imgPath' height='400px' width='400px' style='margin:10px;'>";
}
?>
