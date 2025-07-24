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
    <title>Mental First - Posts</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f7fafc;
        }
        .posts-wrapper {
            max-width: 1100px;
            margin: 40px auto 0 auto;
            padding: 18px;
        }
        .posts-title {
            font-size: 2rem;
            color: #3366cc;
            margin-bottom: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-align: center;
        }
        .posts-filter-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 22px;
            gap: 12px;
        }
        .posts-filter-label {
            color: #223366;
            font-weight: 500;
            font-size: 1rem;
        }
        .posts-filter-select {
            padding: 7px 18px 7px 12px;
            border-radius: 6px;
            border: 1.5px solid #cdd7ee;
            font-size: 1rem;
            background: #fff;
            color: #3366cc;
            transition: border 0.17s;
        }
        .posts-list {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 28px;
        }
        .post {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(51,102,204,0.07);
            position: relative;
            transition: box-shadow 0.18s;
        }
        .post-card:hover {
            box-shadow: 0 4px 18px rgba(51,102,204,0.13);
        }
        .post-image {
            width: 100%;
            height: 170px;
            object-fit: cover;
            background: #d8e6fa;
        }
        .post-content {
            padding: 18px 16px 14px 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .post-title {
            font-size: 1.11rem;
            font-weight: 600;
            color: #3366cc;
            margin-bottom: 2px;
        }
        .post-date {
            color: #888;
            font-size: 0.97rem;
        }
        .post-category {
            display: inline-block;
            background: #eaf1fc;
            color: #3366cc;
            font-size: 0.95rem;
            border-radius: 4px;
            padding: 2.5px 9px;
            margin-bottom: 4px;
            font-weight: 500;
        }
        .post_actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 16px 16px 16px;
        }
        .post_actions div {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.15s;
        }
        .post_actions a {
            color: #3366cc;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.15s;
        }
        .post_actions .delete a {
            color: #e74c3c;
        }
        .post_actions div:hover a {
            color: #fff;
        }
        .post_actions div:hover {
            background: #2244aa;
        }
        .post_actions .delete:hover {
            background: #e74c3c;
        }
        @media (max-width: 900px) {
            .posts-list {
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
        }
        @media (max-width: 600px) {
            .posts-list {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .post .post-image {
                height: 140px;
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
            <li><a href='dashboard.php'>Dashboard</a></li>
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

    <div class="posts-wrapper">
        <div class="posts-title">
            <?php echo $caty ?> Posts
        </div>
        <form class="posts-filter-bar" action="posts.php" method="GET">
            <label class="posts-filter-label" for="category-filter">
                Filter by Category:
            </label>
            <select name="category" class="posts-filter-select" id="category-filter" style="cursor: pointer;" >
                <option <?php echo $caty == 'All' ? "selected" : "" ?> value="All">All</option>
                <option <?php echo $caty == 'Marketing' ? "selected" : "" ?> value="Marketing">Marketing</option>
                <option <?php echo $caty == 'Education' ? "selected" : "" ?> value="Education">Education</option>
                <option <?php echo $caty == 'Entertainment' ? "selected" : "" ?> value="Entertainment">Entertainment</option>
                <option <?php echo $caty == 'Wellness' ? "selected" : "" ?> value="Wellness">Wellness</option>
            </select>
        </form>
        <div class="posts-list" id="posts-list">
            <!-- Post 1 -->
            <?php while($post = $result->fetch(PDO::FETCH_ASSOC)) :?>
            <div class="post" data-category="<?php echo $post["category"] ?>">
                <img src="posts_imgs/<?php echo $post["image"] ?>" class="post-image" alt="<?php echo $post["title"] ?>">
                <div class="post-content">
                    <span class="post-category"><?php echo $post["category"] ?></span>
                    <span class="post-title"><?php echo $post["title"] ?></span>
                    <span class="post-date" data-time="<?php echo $post["created_at"] ?>"></span>
                </div>
                <div class="post_actions">
                    <div class="show"><a href="post-link.php?post_id=<?php echo $post["id"]?>"><i class="fa fa-eye"></i> Show</a></div>
                    <div class="edit"><a href="write.php?post_id=<?php echo $post["id"]?>"><i class="fa fa-edit"></i> Edit</a></div>
                    <div class="delete"><a href="do_delete.php?post_id=<?php echo $post["id"]?>"><i class="fa fa-trash"></i> Delete</a></div>
                </div>
            </div>
            <?php endwhile;?>
        </div>
    </div>

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
    <!-- Scripts -->
    <script>
        // TimeAgo for post dates
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
        document.getElementById("category-filter").onchange = function (){
            this.parentElement.submit();
        }
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