<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "animoworld";

$con = mysqli_connect($host, $username, $password, $database);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$folderPath = 'Images/';
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

// Handle post submission
if (isset($_POST['submit'])) {
    $caption = mysqli_real_escape_string($con, $_POST['caption']);
    mysqli_query($con, "INSERT INTO posts (caption) VALUES ('$caption')");
    $post_id = mysqli_insert_id($con);

    foreach ($_FILES['fileToUpload']['tmp_name'] as $index => $tmpName) {
        $fileName = basename($_FILES['fileToUpload']['name'][$index]);
        $tempname = $_FILES['fileToUpload']['tmp_name'][$index];
        $folder = $folderPath . $fileName;
        $fileNameEscaped = mysqli_real_escape_string($con, $fileName);

        mysqli_query($con, "INSERT INTO images (post_id, file) VALUES ($post_id, '$fileNameEscaped')");
        move_uploaded_file($tempname, $folder);
    }

    header("Location: postimg.php");
    exit;
}

// Handle delete post
if (isset($_GET['delete'])) {
    $postId = intval($_GET['delete']);
    // Delete images from folder
    $imgRes = mysqli_query($con, "SELECT file FROM images WHERE post_id = $postId");
    while ($row = mysqli_fetch_assoc($imgRes)) {
        $path = $folderPath . $row['file'];
        if (file_exists($path)) unlink($path);
    }
    mysqli_query($con, "DELETE FROM posts WHERE id = $postId");
    header("Location: postimg.php");
    exit;
}
?>
include postimg.php;
<!DOCTYPE html>
<html>
<head>
    <title>Upload Post</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            margin: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .post {
            background: white;
            margin: 20px;
            width: 500px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .image-carousel {
            display: flex;
            overflow-x: auto;
        }

        .image-carousel img {
            width: 500px;
            flex-shrink: 0;
            height: auto;
            object-fit: cover;
        }

        .caption {
            padding: 10px;
        }

        .delete-btn {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <form action="postimg.php" method="post" enctype="multipart/form-data">
        <textarea name="caption" rows="3" cols="40" placeholder="Write a caption..." required></textarea><br><br>
        <input type="file" name="fileToUpload[]" multiple required><br><br>
        <input type="submit" name="submit" value="Post">
    </form>

    <?php
    $posts = mysqli_query($con, "SELECT * FROM posts ORDER BY id DESC");
    while ($post = mysqli_fetch_assoc($posts)) {
        echo '<div class="post">';
        echo '<div class="image-carousel">';
        $images = mysqli_query($con, "SELECT * FROM images WHERE post_id = " . $post['id']);
        while ($img = mysqli_fetch_assoc($images)) {
            echo '<img src="Images/' . htmlspecialchars($img['file']) . '" alt="Post Image">';
        }
        echo '</div>';
        echo '<div class="caption"><strong>Caption:</strong> ' . htmlspecialchars($post['caption']) . '</div>';
        echo '<form method="get"><input type="hidden" name="delete" value="' . $post['id'] . '"><button class="delete-btn">Delete Post</button></form>';
        echo '</div>';
    }
    ?>

</body>
</html>
