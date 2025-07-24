<?php
class User {
                public $username;
                public $email;
                public $password;
                public $nom;
                public $prenome;
                public $bio;
                public $image;
                public $role;
                
                public function __construct($username,$email, $nom, $prenome,$password, $bio, $image,$role) {
                    $this->username = $username;
                    $this->email = $email;
                    $this->nom = $nom;
                    $this->prenome = $prenome;
                    $this->bio = $bio;
                    $this->password = $password;
                    $this->image = $image;
                    $this->role = $role;
                }
            }
session_start();
if (!isset($_SESSION["user"])) {
    header("location: index.php");
    exit();
}else if (!($_SESSION["user"]->role)){
    header("location: index.php");
    exit();
}
$user = $_SESSION["user"];
$amount = 8; // Number of posts to display
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "SELECT * FROM posts
ORDER BY id DESC
LIMIT $amount;";
$result = $pdo->query($sql);
$posts_count = ($pdo->query("SELECT COUNT(*) FROM posts"))->fetchColumn();
$users_count = ($pdo->query("SELECT COUNT(*) FROM users"))->fetchColumn();
$USERS_result = $pdo->query("SELECT * FROM users ORDER BY created_at LIMIT $amount");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental First Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Responsive dashboard layout */
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto 0 auto;
            padding: 20px;

        }
        .dashboard-section {
            flex: 1 1 350px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            padding: 24px 18px 18px 18px;
            min-width: 300px;
            margin-bottom: 20px;
        }
        .sec_header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .dashboard-section .posts_count{
            /* background: #3366cc; */
            color: #3366cc;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .dashboard-section h2 {
            font-size: 1.25rem;
            margin-bottom: 14px;
            color: #3366cc;
        }
        .user-list, .post-list {
            list-style: none;
            padding: 0;
            margin: 0 0 12px 0;
        }
        .user-item, .post-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .new_post{
            text-align: center;
            padding: 10px;
            background-color: #3366cc;
            margin-top: 40px;
            border-radius: 8px;
        }
        .new_post a {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }
        .new_post:hover{
            background-color: #285a99;
        }
        .user-item:last-child, .post-item:last-child {
            border-bottom: none;
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #3366cc;
            font-weight: bold;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-name, .post-title {
            flex: 1;
            font-size: 1rem;
        }
        .user-role {
            background: #eee;
            color: #666;
            font-size: 0.85rem;
            border-radius: 4px;
            padding: 2px 7px;
        }
        .post-date {
            color: #888;
            font-size: 0.85rem;
            margin-left: 10px;
        }
        .dashboard-see-more {
            display: inline-block;
            margin-top: 8px;
            color: #3366cc;
            font-weight: 500;
            text-decoration: none;
            transition: text-decoration 0.2s;
        }
        .dashboard-see-more:hover {
            text-decoration: underline;
        }
        @media (max-width: 900px) {
            .dashboard-container {
                flex-direction: column;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="main-nav">
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="categories.php?category=All">Categories</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <a href="profile.php" class="prof">
            <span><?php echo $user->username ?></span>
            <img src="<?php echo"p_imgs\\".$user->image ?>" alt="Profile">
        </a>
    </nav>

    <!-- Dashboard Main Content -->
    <div class="dashboard-container">
        <!-- Users Section -->
        <section class="dashboard-section">
            <div class="sec_header">
                <h2><i class="fa fa-users"></i> Users</h2>
                <div class="posts_count"><?php echo $users_count?></div>
            </div>
            <ul class="user-list">
                <?php while($user = $USERS_result->fetch(PDO::FETCH_ASSOC)) :?>
                    <li class="user-item">
                        <div class="user-avatar"><img src="p_imgs/<?php echo htmlspecialchars($user["prof_img"])?>" alt="<?php echo htmlspecialchars($user["username"])?>"></div>
                        <span class="user-name"><?php echo htmlspecialchars($user["username"])?></span>
                        <span class="user-role"><?php echo $user["role"] == 1 ? "Admin" : "member"?></span>
                    </li>
                <?php endwhile;?>
            </ul>
            <a href="users.php" class="dashboard-see-more">See More <i class="fa fa-arrow-right"></i></a>
        </section>

        <!-- Latest Posts Section -->
        <section class="dashboard-section">
            <div class="sec_header">
                <h2><i class="fa fa-newspaper"></i> Posts</h2>
                <div class="posts_count"><?php echo $posts_count ?></div>
            </div>
            <ul class="post-list">
                <?php while($post = $result->fetch(PDO::FETCH_ASSOC)) :?>
                    <li class="post-item">
                    <span class="post-title"><?php echo htmlspecialchars($post["title"])?></span>
                    <span class="post-date" data-time="<?php echo htmlspecialchars($post["created_at"])?>"></span>
                </li>
                <?php endwhile;?>
            </ul>
            <a href="posts.php" class="dashboard-see-more">See More <i class="fa fa-arrow-right"></i></a>
            <div class="new_post"><a href="write.php">Create new post</a></div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo"><img src="logo.jpg" alt="Logo"></div>
            <div class="footer-links">
                <a href="#">Home</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
                <a href="#">Categories</a>
            </div>
            <div class="footer-info">
                &copy; 2025 Mental First. All rights reserved.<br>
                Empowering your mental wellness. | Designed by Mental First Team
            </div>
        </div>
        <div class="footer-social">
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </footer>

    <!-- TimeAgo Script for Post Dates -->
    <script>
        function TimeAgo(Tdate) {
            let date = (new Date()).getTime();
            let postDate = new Date(Tdate).getTime();
            let dff = date - postDate;
            let secs = Math.floor((dff) / (1000));
            let mins = Math.floor((dff) / (1000 * 60));
            let hours = Math.floor((dff) / (1000 * 60 * 60));
            let days = Math.floor((dff) / (1000 * 60 * 60 * 24));
            let months = Math.floor((dff) / (1000 * 60 * 60 * 24 * 30));
            let years = Math.floor((dff) / (1000 * 60 * 60 * 24 * 30 * 12));
            if (years > 0) {
                return years + " year" + (years > 1 ? "s ago" : " ago");
            }else if (months > 0) {
                return months + " month" + (months > 1 ? "s ago" : " ago");
            }else if (days > 0) {
                return days + " day" + (days > 1 ? "s ago" : " ago");
            }else if (hours > 0) {
                return hours + " hour" + (hours > 1 ? "s ago" : " ago");
            }else if (mins > 0) {
                return mins + " minute" + (mins > 1 ? "s ago" : " ago");
            }else if (secs > 0) {
                return secs + " second" + (secs > 1 ? "s ago" : " ago");
            } else {
                return "just now";
            }
        }
        document.querySelectorAll('.post-date').forEach(function(dateElement) {
            dateElement.textContent = TimeAgo(dateElement.dataset.time);
        });
        function toggleMenu() {
            document.querySelector('.nav-links').classList.toggle('show');
            document.querySelector('.hamburger').classList.toggle('active');
            if (document.querySelector('.hamburger i').classList.contains('fa-bars')) {
                document.querySelector('.hamburger i').classList.remove('fa-bars');
                document.querySelector('.hamburger i').classList.add('fa-times');
            } else {
                document.querySelector('.hamburger i').classList.remove('fa-times');
                document.querySelector('.hamburger i').classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>