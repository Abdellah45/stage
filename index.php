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
    $loged_in = false;
}else{
    $loged_in = true;
    
    $user = $_SESSION["user"];
    $username = $user->username;
    $image = $user->image;
    $role = $user->role;
}
$amount = 3; // Number of posts to display
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "SELECT * FROM posts
ORDER BY id DESC
LIMIT $amount;";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental First</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="main-nav">
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="nav-links">
            <?php 
            if ($loged_in && $role){
                echo "<li><a href='dashboard.php'>Dashboard</a></li>";
            }
            ?>
            <li><a href="index.php">Home</a></li>
            <li><a href="categories.php?category=All">Categories</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <?php
        if (!$loged_in) {
            echo '<a href="login.php" class="join-btn">Join</a>';
        }else{
            echo '<a href="profile.php" class="prof">';
            echo '<span>'.$username.'</span>';
            echo '<img src="p_imgs\\'.$image.'" alt="">';
            echo '</a>';
        }
        ?>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-grid">
            <div class="hero-content">
                <h1>Welcome to Mental First: Your Daily Inspiration</h1>
                <p>
                    Discover a world of positivity and mental wellness tips. Join our community and get inspired every day!
                    At Mental First, we believe mental health is the foundation for a happy, successful life. Explore expert articles, practical guides, and real stories to help you build resilience, manage stress, and thrive. Whether you’re looking for daily motivation or in-depth resources, you’ll find support and inspiration here.
                </p>
                <div class="hero-buttons">
                    <?php
                    if (!$loged_in) {
                        echo '<a href="login.php" class="hero-btn primary">Join</a>';
                    }
                    ?>
                </div>
            </div>
            <div class="hero-image">
                <img src="logo.jpg" alt="Mental First Hero">
            </div>
        </div>
    </section>

    <!-- Latest Posts Section -->
    <section class="latest-posts">
        <h2>Explore Our Latest Posts</h2>
        <p class="posts-subtitle">Discover insightful articles and engaging stories.</p>
        <div class="posts-grid">
            <?php
        while($post = $result->fetch(PDO::FETCH_ASSOC)) :?>
            <div class="post-card">
                <div class="post-img">
                    <img src="<?php echo "posts_imgs\\".$post["image"]?>" alt="Post">
                </div>
                <div class="post-category"><?php echo $post["category"]?></div>
                <div class="cont_pos">
                    <form action="post-link.php" method="GET">
                        <h3 class="post-title"><?php echo $post["title"]?></h3>
                        <div class="post-date" data-time="<?php echo $post["created_at"]?>"></div>
                        <?php $excerpt = substr($post["content"], 0, 100); ?>
                        <p class="post-excerpt"><?php echo $excerpt."..." ?></p>
                        <input type="hidden" name="post_id" value="<?php echo $post["id"]?>">
                        <input type="submit" class="post-btn" value="Read More">
                    </form>
                </div>
            </div>

        <?php endwhile;?>
        </div>
        <a class="see-more-btn" href="categories.php?category=All">See More</a>
        

        
    </section>

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
        // let diff = TimeAgo();
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