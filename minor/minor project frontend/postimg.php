<?php
/*include 'insta.php';
$con = mysqli_connect("localhost", "root", "", "animoworld");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$posts = mysqli_query($con, "SELECT * FROM imagess ORDER BY id DESC");
while ($post = mysqli_fetch_assoc($posts)) {
    $post_id = $post['number'];
    $caption = htmlspecialchars($post['caption']);
    $user = htmlspecialchars($post['user']);

    echo '<div class="post">';
    echo '<div class="post-header">';
    echo '<img src="https://source.unsplash.com/random/50x50" class="post-user-avatar" alt="User">';
    echo "<span>$user</span>";
    echo '</div>';
    echo '<div class="post-carousel">';
    
    // Fetch all images for this post
    $images = mysqli_query($con, "SELECT file FROM imagess WHERE post_id = $post_id");
    while ($img = mysqli_fetch_assoc($images)) {
        $imgPath = 'Images/' . htmlspecialchars($img['file']);
        echo "<img src='$imgPath' class='carousel-img'>";
    }

    echo '</div>';

    echo '<div class="post-actions">
        <div> 
            <img src="https://img.icons8.com/ios/50/000000/dog-bone.png" 
                 class="like-btn" alt="Bone" 
                 onclick="toggleLike(this)" data-liked="false">
            <div id="like-count">0</div>
        </div>
        <div>
            <img src="https://img.icons8.com/ios/50/000000/dog-bark.png" alt="Bark">
        </div>
        <img src="https://img.icons8.com/ios/50/000000/dog-leash.png" alt="Leash">
    </div>'; // ✅ Correct closing


    echo "<div class='post-content'>
            <div class='post-caption'><strong>$user</strong> $caption</div>
            <div class='post-comments'>View all 284 comments</div>
        </div>";
    
}*/
?>
<?php
$con = mysqli_connect("localhost", "root", "", "animoworld");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch images
$images = mysqli_query($con, "SELECT * FROM imagess ORDER BY number DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Feed</title>
    <style>
        .image-container {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            width: 300px;
        }
        .image-container img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<h1>🐾 Animo World Feed</h1>

<?php
while ($img = mysqli_fetch_assoc($images)) {
    $imgPath = 'Images/' . htmlspecialchars($img['file']);

    echo '<div class="image-container">';
    if (file_exists($imgPath)) {
        echo "<img src='$imgPath' alt='Image'>";
    } else {
        echo "<p>Image not found: $imgPath</p>";
    }
    echo '</div>';
}
?>

</body>
</html>



