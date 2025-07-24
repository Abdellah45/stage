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
           <li><a href="categories.php">Categories</a></li>
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

    <!-- About Section -->
    <section class="about-section" style="background: var(--gray); padding: 3rem 0;">
        <div class="about-container" style="max-width: 700px; margin: 0 auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(44,110,73,0.10); padding: 2.5rem 2rem;">
            <h2 style="color: var(--secondary); font-size: 2rem; margin-bottom: 1rem; text-align:center;">About Mental First</h2>
            <p style="color: #444; font-size: 1.15rem; line-height: 1.8; margin-bottom: 1.5rem; text-align:center;">
                <strong>Mental First</strong> is a community-driven platform dedicated to promoting mental wellness, positivity, and personal growth. 
                Our mission is to empower individuals with practical tools, expert advice, and inspiring stories to help them thrive in all aspects of life.
            </p>
            <ul style="color: #555; font-size: 1.08rem; margin-bottom: 1.5rem; line-height: 1.7;">
                <li>✓ Curated articles and guides on mindfulness, stress management, and healthy habits.</li>
                <li>✓ A supportive community for sharing experiences and encouragement.</li>
                <li>✓ Resources for building resilience and maintaining a positive mindset.</li>
                <li>✓ Regular updates, tips, and motivation for your mental health journey.</li>
            </ul>
            <p style="color: #444; font-size: 1.08rem; text-align:center;">
                Whether you're seeking daily inspiration or in-depth resources, Mental First is here to support you every step of the way.
            </p>
        </div>
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