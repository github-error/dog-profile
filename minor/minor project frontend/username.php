<?php
if (!isset($_SESSION['username'])) {
    header("Location: username1.php");
    exit();
}

// Get username from session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Animo World</title>
    <link rel="stylesheet" href="project.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" />
  </head>
  <body>
    <div class="dashboard">
      <!-- Sidebar -->
      <div class="sidebar">
        <div class="sidebar-header">
          <h2><i class="fa-solid fa-dog"></i>Animo World</h2>
        </div>
        <ul class="sidebar-menu">
          <li class="dropdown">
            <a href="#"><i class="fas fa-home"></i> Dashboard</a>
            <!-- Dropdown Menu for Home -->
            <ul class="dropdown-menu">
              <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard Overview</a></li>
              <li><a href="statistics.html"><i class="fas fa-chart-line"></i> Statistics</a></li>
              <li><a href="usermanagement.html"><i class="fas fa-users"></i> User Management</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#"><i class="fas fa-chart-pie"></i> Analytics</a>
            <ul class="dropdown-menu">
              <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
              <li><a href="report.html"><i class="fas fa-chart-bar"></i> Reports</a></li>
              <li><a href="filters.html"><i class="fas fa-filter"></i> Filters</a></li>
            </ul>
          </li>
          <li>
            <a href="setting.html"><i class="fas fa-cog"></i> Settings</a>
          </li>
          <li><a href="profile.html"><i class="fas fa-user"></i> Profile</a></li>
          <li class="Logout"><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
          <div class="topbar-left">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
          </div>
          <div class="topbar-right">
            <span><i class="fas fa-user" name="username"></i>  <?php echo htmlspecialchars($username); ?></span>
          </div>
        </div>

        <!-- Content Section -->
        <div class="content">
          <h2><i class="fas fa-chart-line"></i> Dashboard Overview</h2>
          <p>Manage your dog’s profile, share posts, and explore popular pet reels.</p>
          <div>
            <a href="share-posts.html" class="div1">
                <i class="fas fa-comments"></i> Our Social Gathering
            </a>
            <a href="#" class="div2">
                <i class="fas fa-eye"></i> Check your dog’s popularity
            </a>
        </div>
          <!-- BENEFITS  -->
          <div class="div3">
            <div class="col1">
              <h4><i class="fas fa-star"></i> Trending Posts</h4>
              <p>See what’s trending among pet lovers.</p>
            </div>
            <div class="col2">
              <h4><i class="fas fa-heart"></i> Your Dog’s Stats</h4>
              <p>Track views, likes, and interactions.</p>
            </div>
            <div class="col3">
              <h4><i class="fas fa-check-circle"></i> Upcoming Events</h4>
              <p>Stay updated on pet meetups and activities.</p>
            </div>
          </div>
          <!-- SERVICE QUALITY -->
          <div class="service">
            <div class="service-row1"><i class="fas fa-cogs"></i> Services</div>
            <div class="service-row2">
                Explore services for your pets and cattle!
                <br />
                <button class="service-button"><i class="fas fa-play"></i> View All Services</button>
            </div>
            <div class="service-row3">
                <a href="PetHealth.html" class="service-box">
                    📑 Pet Health Check (Veterinary)
                </a>
                <a href="BuySell.html" class="service-box">
                    🐶 Puppies & Cattle (Buy/Sell)
                </a>
                <a href="ai-disease.html" class="service-box">
                    🤖 AI-Powered Disease Detection
                </a>
            </div>
        </div>
            </div><br>
            <!-- About Us Section -->
            <div>
              <div class="section">
                  <div class="about-section">
                      
                  </div>
                  <footer>
                    <p class="about"><i class="fas fa-info-circle"></i> About Us</p>
                      <h1 class="about-h1">Join 15k+ Pet Enthusiasts!</h1>
                      <p class="about-p">
                          Create a profile for your dog, share reels, and connect with other pet lovers worldwide.
                      </p>
                      <a href="aboutUs.html" class="more-aboutus">
                          <h2 class="aboutus-h2"><i class="fas fa-arrow-right"></i> About Us</h2>
                      </a>
                  </footer>
              </div>
          </div>
                <!-- <div class="aboutus-2">
                  <p class="more-1"><i class="fas fa-check"></i> <b>Your Pet'ss Digital Identity</b><br>Create a unique profile for your dog, share photos, reels, and special moments. Let your furry friend gain followers, receive likes (Bones 🦴), and become a star in the pet community!</p>
                  <p class="more-2"><i class="fas fa-check"></i><b> Smart Cattle Breeding & Trading</b><br>Easily connect with verified breeders, buy & sell cattle, and find the best matches for breeding. Our platform ensures a seamless and secure way to manage livestock transactions.</p>
                  <p class="more-3"><i class="fas fa-check"></i><b> AI-Powered Pet Health Insights</b><br>Worried about your pet’s health? Our AI-based system helps detect early signs of diseases based on symptoms you upload. Get instant recommendations and connect with expert veterinarians.</p>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>