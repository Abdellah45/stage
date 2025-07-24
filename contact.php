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
    $email = $user->email;
    $full_name = $user->nom . " " . $user->prenome;
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

    <!-- Contact Us Section -->
    <section class="contact-section">
        <div class="contact-container">
            <h2>Contact Us</h2>
            <p class="contact-desc">
                Have a question, suggestion, or just want to say hello? Fill out the form below and our team will get back to you as soon as possible.
            </p>
            <form class="contact-form" action="#" method="post">
                <input type="text" name="name" <?php echo $loged_in ?"value='".$full_name."'" : "" ?> placeholder="Your Name" required>
                <input type="email" name="email"<?php echo $loged_in ?"value='".$email."'" : "" ?> placeholder="Your Email" required>
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit" class="contact-btn">Send Message</button>
            </form>
            <div class="contact-info">
                <p><i class="fas fa-envelope"></i> support@mentalfirst.com</p>
                <p><i class="fas fa-phone"></i> +1 234 567 8901</p>
            </div>
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