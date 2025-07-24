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
$caty = isset($_GET['category']) ? $_GET['category'] : 'All'; // Get the category from the URL or default to 'All'
$amount = 16; // Number of posts to display
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = $caty === 'All' ? "SELECT COUNT(*) FROM posts ORDER BY id" : "SELECT COUNT(*) FROM posts WHERE category = \"$caty\" ORDER BY id";
$result = $pdo->query($sql);
$count = $result->fetchColumn();
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pages_count = ceil($count / $amount);
$offset = ($current_page - 1) * $amount; // Calculate offset for pagination
$sql = $caty === 'All' ? "SELECT * FROM posts ORDER BY id LIMIT $amount OFFSET $offset" : "SELECT * FROM posts WHERE category = \"$caty\" ORDER BY id  LIMIT $amount OFFSET $offset";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental First - Categories</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Inline Category Navbar */
        .category-navbar {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            align-items: center;
            background: #fff;
            border-bottom: 1px solid #eaeaea;
            padding: 1.1rem 0 1.1rem 0;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }
        .category-nav-link {
            font-size: 1.08rem;
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 1.5rem;
            transition: background 0.18s, color 0.18s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
        }
        .category-nav-link.active, .category-nav-link:hover {
            background: var(--accent);
            color: #fff;
        }
        @media (max-width: 600px) {
            .category-navbar {
                gap: 0.4rem;
                padding: 0.7rem 0;
            }
            .posts-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
.pagination {
  display: flex;
  justify-content: center;
  padding: 20px;
  gap: 8px;
  flex-wrap: wrap;
}

.pagination a {
  color: #333;
  float: left;
  padding: 8px 14px;
  text-decoration: none;
  border: 1px solid #ccc;
  border-radius: 6px;
  transition: background-color 0.3s, color 0.3s;
}

.pagination a:hover {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}

.pagination a.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
  pointer-events: none;
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
            <?php 
            if ($loged_in && $role){
                echo "<li><a href='dashboard.php'>Dashboard</a></li>";
            }
            ?>
            <li><a href="index.php">Home</a></li>
            <li><a href="categories.php" class="active">Categories</a></li>
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

    <!-- Inline Category Navbar -->
    <nav class="category-navbar" id="category-navbar">
        <a class="category-nav-link <?php echo $caty == "All" ? "active" : ""?>" href="categories.php?category=All" data-category="All"><i class="fas fa-globe"></i> All</a>
        <a class="category-nav-link <?php echo $caty == "Entertainment" ? "active" : ""?>" href="categories.php?category=Entertainment" data-category="Entertainment"><i class="fas fa-leaf"></i> Entertainment</a>
        <a class="category-nav-link <?php echo $caty == "Health" ? "active" : ""?>" href="categories.php?category=Health" data-category="Health"><i class="fas fa-brain"></i> Health</a>
        <a class="category-nav-link <?php echo $caty == "Marketing" ? "active" : ""?>" href="categories.php?category=Marketing" data-category="Marketing"><i class="fas fa-burn"></i> Marketing</a>
        <a class="category-nav-link <?php echo $caty == "Education" ? "active" : ""?>" href="categories.php?category=Education" data-category="Education"><i class="fas fa-seedling"></i> Education</a>
    </nav>

    <section class="latest-posts" id="category-posts-section">
        <h2 id="posts-title"><?php echo $caty." Posts" ?></h2>
        <div class="posts-grid" id="posts-grid">
             <?php
        while($post = $result->fetch(PDO::FETCH_ASSOC)) :?>
            <div class="post-card" data-category="<?php echo $post["category"]?>">
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
    </section>
    <!-- HTML: Pagination -->
     <div class="pagination">
        <?php
        if ($current_page > 1) {
            echo '<a href="?category='.$caty.'&page=' . ($current_page - 1) . '">&laquo;</a>';
        }
        if ($pages_count > 1) {
            for ($i = 1; $i <= $pages_count; $i++) {
            if ($i == $current_page) {
                echo '<a href="?category='.$caty.'&page=' . $i . '" class="active">' . $i . '</a>';
            } else {
                echo '<a href="?category='.$caty.'&page=' . $i . '">' . $i . '</a>';
            }
        }
        }
        if ($current_page < $pages_count) {
            echo '<a href="?category='.$caty.'&page=' . ($current_page + 1) . '">&raquo;</a>';
        }
        ?>
    </div>
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo"><img src="logo.jpg" alt="Logo"></div>
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <a href="categories.php">Categories</a>
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