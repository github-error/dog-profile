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

$stmt = $con->prepare("SELECT user_id FROM accounts WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'] ?? 0;

// Handle delete (optional, if needed)
// if (isset($_GET['delete'])) {
//     // your delete logic here
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animoworld - Dog Social Media</title>
    <style>
        /* (Your entire CSS remains untouched — omitted here for brevity) */
        /* Keep the CSS block you already have */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: #F2F6D0;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #EFDCAB;
            border-bottom: 2px solid #D98324;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            font-family: 'Brush Script MT', cursive;
            color: #443627;
        }

        .nav-icons img {
            height: 24px;
            margin-left: 1rem;
        }

        .main {
            max-width: 600px;
            margin: 80px auto 0;
            padding: 1rem;
        }

        .stories {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 2px solid #D98324;
            margin-bottom: 1rem;
        }

        .story {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #e1306c;
            padding: 2px;
        }

        .story img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .post {
            background: #EFDCAB;
            border: 2px solid #D98324;
            border-radius: 5px;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .post-header {
            display: flex;
            align-items: center;
        }

        .post-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        .post-image {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .post-actions {
            padding: 1rem;
        }

        .post-actions img {
            height: 24px;
            margin-right: 1rem;
            cursor: pointer;
        }

        .post-likes {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #443627;
        }

        .post-caption {
            margin-bottom: 0.5rem;
            color: #443627;
        }

        .post-comments {
            color: #8e8e8e;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">Photogram</div>
        <input type="text" placeholder="Search" style="padding: 0.3rem; width: 200px;">
        <div class="nav-icons">
            <a href="project.html"><img src="https://img.icons8.com/ios/50/000000/home--v1.png" alt="Home"></a>
            <a href="message.html"><img src="https://img.icons8.com/ios/50/000000/paper-plane.png" alt="DM"></a>
            <a href="profile.html"><img src="https://img.icons8.com/ios/50/000000/user.png" alt="Profile"></a>
        </div>
    </header>

    <main class="main">
        <?php
        $stmt = $con->prepare("SELECT id, image, created_at FROM post WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $image = htmlspecialchars($row['image']);
                $created = htmlspecialchars($row['created_at']);
                echo '
                <div class="post">
                    <div class="post-header">
                        <img src="https://source.unsplash.com/random/50x50?dog" class="post-user-avatar" alt="User">
                        <span>' . htmlspecialchars($_SESSION['username']) . '</span>
                    </div>
                    <img src="Images/' . $image . '" class="post-image" alt="Post">
                    <div class="post-actions">
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
                    </div>
                    <div class="post-content" style="padding: 0 1rem 1rem;">
                        <div class="post-caption">
                            <strong>' . htmlspecialchars($_SESSION['username']) . '</strong> Posted on ' . $created . '
                        </div>
                        <div class="post-comments">
                            View all 0 comments
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p style="text-align:center; color:#555;">No posts yet. Upload one to get started!</p>';
        }
        ?>
    </main>

    <script>
        function toggleLike(element) {
            let likeCountElement = element.nextElementSibling;
            let currentLikes = parseInt(likeCountElement.textContent);
            let isLiked = element.dataset.liked === "true";

            if (isLiked) {
                currentLikes--;
                element.src = "https://img.icons8.com/ios/50/000000/dog-bone.png";
                element.dataset.liked = "false";
            } else {
                currentLikes++;
                element.src = "https://img.icons8.com/ios-filled/50/ff0000/dog-bone.png";
                element.dataset.liked = "true";
            }

            likeCountElement.textContent = currentLikes;
        }
    </script>
</body>
</html>
