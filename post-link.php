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
$id = $_GET['post_id'];
$amount = isset($_GET['limit']) ? $_GET['limit'] : 10; // Number of posts to display
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "SELECT COUNT(*) FROM comments WHERE post_id = $id";
$result = $pdo->query($sql);
$count = $result->fetchColumn();

$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "SELECT * FROM posts WHERE id = $id";
$result = $pdo->query($sql);
$post = $result->fetch(PDO::FETCH_ASSOC);
$sql = "SELECT 
    comments.*, 
    users.Fname, 
    users.Lname, 
    users.prof_img
FROM comments
JOIN users ON users.username = comments.username
WHERE comments.post_id = $id
ORDER BY comments.created_at DESC
LIMIT $amount";
$Cresult = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental First | How to Practice Mindfulness Daily</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #2C6E49;
            --secondary: #1F4B6E;
            --accent: #76b39d;
            --text-dark: #1F4B6E;
            --text-light: #FFFFFF;
            --bg-light: #f8fafc;
            --bg-gradient: linear-gradient(135deg, #e8f6ef 0%, #dbeafe 100%);
            --bg-gradient-dark: linear-gradient(120deg, #1F4B6E 0%, #2C6E49 100%);
            --bg-card: #f0f8ff;
            --bg-card-alt: #e6f4ea;
            --bg-footer: #1F4B6E;
            --gray: #f5f6fa;
            --shadow: 0 4px 24px rgba(44,110,73,0.07);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--bg-gradient);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* Navigation Bar */
        .main-nav {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 20px 40px;
            background: var(--bg-gradient-dark);
            gap: 32px;
            box-shadow: 0 2px 12px rgba(44,110,73,0.09);
        }
        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
            margin-right: auto;
        }
        .nav-links a {
            text-decoration: none;
            color: #c7e2ff;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.2s;
            padding: 7px 10px;
            border-radius: 7px;
        }
        .nav-links a:hover,
        .nav-links a.active {
            color: var(--accent);
            background: rgba(255,255,255,.07);
        }
        .prof {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.13);
            padding: 5px 16px 5px 8px;
            border-radius: 32px;
            box-shadow: 0 2px 8px rgba(44,110,73,0.08);
        }
        .prof img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--accent);
            background: #fff;
        }
        .prof span {
            color: #fff;
            font-size: 17px;
            font-weight: 500;
        }

        /* Hero/Post Header */
        .post-hero {
            background: linear-gradient(90deg, #76b39d 0%, #e8f6ef 80%);
            padding: 54px 0 32px 0;
            box-shadow: 0 4px 24px rgba(44,110,73,0.09);
        }
        .post-single-header {
            max-width: 750px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 11px;
        }
        .post-single-header img {
            width: 100%;
            max-width: 460px;
            border-radius: 19px;
            margin-bottom: 15px;
            background: var(--bg-card);
            box-shadow: 0 2px 16px rgba(44,110,73,0.13);
        }
        .post-single-category {
            color: #fff;
            background: var(--secondary);
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 15px;
            border-radius: 7px;
            padding: 5px 19px;
            box-shadow: 0 2px 8px rgba(44,110,73,0.11);
        }
        .post-single-title {
            color: var(--secondary);
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 4px;
            text-align: center;
            text-shadow: 0 2px 6px #e6f4ea77;
        }
        .post-single-date {
            color: #5f8aaf;
            font-size: 16px;
            margin-bottom: 9px;
            z-index: 5;
        }

        /* Post Content Card */
        .post-single-section {
            max-width: 790px;
            margin: -70px auto 0 auto;
            background: var(--bg-card);
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 42px 32px 35px 32px;
            position: relative;
            z-index: 2;
        }
        .post-single-content {
            color: var(--text-dark);
            font-size: 19px;
            line-height: 1.8;
            margin-bottom: 36px;
        }
        .post-single-content h3 {
            color: var(--primary);
            margin: 20px 0 10px 0;
            font-size: 22px;
        }
        .post-single-content ul {
            margin: 0 0 10px 18px;
        }
        .post-single-content li {
            margin-bottom: 7px;
        }

        /* COMMENTS SECTION */
        .comments-section {
            margin-top: 36px;
            background: var(--bg-card-alt);
            border-radius: 15px;
            padding: 30px 18px 18px 18px;
            box-shadow: 0 1px 9px rgba(44,110,73,0.06);
        }
        .comments-title {
            font-size: 23px;
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 18px;
        }
        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 17px;
            margin-bottom: 24px;
        }
        .comment {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            background: #fff;
            border-radius: 10px;
            padding: 11px 13px;
            box-shadow: 0 1px 5px rgba(44,110,73,0.04);
            border-left: 4px solid var(--accent);
            transition: background 0.2s;
        }
        .comment-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #eee;
            object-fit: cover;
            border: 2px solid var(--accent);
        }
        .comment-body {
            flex: 1;
        }
        .comment-user {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 2px;
            font-size: 17px;
        }
        .comment-date {
            font-size: 14px;
            color: #aaa;
            margin-bottom: 5px;
        }
        .comment-text {
            color: #444;
            font-size: 16px;
            line-height: 1.6;
        }
        #showMoreBtn {
            margin-top: 12px;
            display: block;
            width: fit-content;
            text-decoration: none;
            background: var(--secondary);
            color: #fff;
            border: none;
            padding: 8px 26px;
            border-radius: 24px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.18s;
            box-shadow: 0 2px 8px #1f4b6e13;
        }
        #showMoreBtn:hover {
            background: var(--primary);
        }
        /* Add Comment Form */
        .add-comment-form {
            background: #fff;
            margin-top: 24px;
            border-radius: 10px;
            padding: 18px 16px 10px 16px;
            box-shadow: 0 1px 4px rgba(44,110,73,0.04);
            margin-bottom: 0;
        }
        .add-comment-form label {
            color: var(--secondary);
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }
        .add-comment-form input,
        .add-comment-form textarea {
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid #c6e2d7;
            font-size: 16px;
            margin-bottom: 11px;
            resize: none;
            font-family: inherit;
            background: #f8fafc;
        }
        .add-comment-form input:focus,
        .add-comment-form textarea:focus {
            border-color: var(--primary);
            background: #fff;
            outline: none;
        }
        .add-comment-btn {
            background: var(--accent);
            color: #fff;
            padding: 10px 34px;
            border-radius: 32px;
            font-size: 17px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.17s;
        }
        .add-comment-btn:hover {
            background: var(--secondary);
        }
        .delete-comment-btn{
            background: #fff;
            border: 1.5px solid #e63946;
            color: #e63946;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            padding: 7px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.17s, color 0.17s;
            box-shadow: 0 2px 6px rgba(230,57,70,0.09);
        }
        .delete-comment-btn:hover{
            background-color: #e63946;
            color:white;
        }

        /* Footer */
        footer {
            background: var(--bg-footer);
            color: #cde7ff;
            text-align: center;
            padding: 43px 16px 35px 16px;
            font-size: 17px;
            border-top: 1px solid #eaeaea11;
            margin-top: 60px;
        }
        .footer-content {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 32px;
        }
        .footer-logo img {
            height: 36px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px #1F4B6E22;
        }
        .footer-links {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        .footer-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 17px;
            transition: color 0.2s;
        }
        .footer-links a:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        .footer-info {
            font-size: 16px;
            color: #b5d3f0;
            margin-top: 11px;
            text-align: right;
        }
        .footer-social {
            margin: 19px 0 0 0;
            display: flex;
            justify-content: center;
            gap: 24px;
        }
        .footer-social a {
            color: var(--accent);
            font-size: 24px;
            transition: color 0.2s;
        }
        .footer-social a:hover {
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .post-single-header img {
                max-width: 100%;
            }
            .post-single-section {
                padding: 28px 4vw 24px 4vw;
            }
            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 19px;
            }
            .footer-info {
                text-align: center;
            }
        }
        @media (max-width: 600px) {
            .main-nav {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
                padding: 16px 10px;
            }
            .nav-links {
                justify-content: center;
                gap: 15px;
            }
            .prof {
                margin: 0 auto;
            }
            .post-single-section {
                padding: 13px 1vw 13px 1vw;
            }
            .footer-links {
                gap: 10px;
                flex-direction: column;
                align-items: center;
            }
            .post-hero {
                padding: 30px 0 15px 0;
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

    <!-- Hero/Post Header -->
    <div class="post-hero">
        <div class="post-single-header">
            <img src="<?php echo "posts_imgs/".$post["image"] ?>" alt="<?php echo $post["title"] ?>">
            <span class="post-single-category"><?php echo $post["category"] ?></span>
            <h1 class="post-single-title"><?php echo $post["title"] ?></h1>
            <div class="post-single-date"></div>
        </div>
    </div>

    <!-- Single Post Content Section -->
    <section class="post-single-section">
        <div class="post-single-content">
            <p><?php echo $post["content"] ?></p>
            
        </div>

        <!-- COMMENTS SECTION -->
        <div class="comments-section">
            <div class="comments-title"><i class="fa-regular fa-comments"></i><?php echo " " .$count ?> Comments</div>
            <div class="comments-list" id="commentsList">
                <?php
        while($comment = $Cresult->fetch(PDO::FETCH_ASSOC)) :?>
                <div class="comment">
                    <img class="comment-avatar" src="p_imgs\<?php echo $comment["prof_img"] ?>" alt="User">
                    <div class="comment-body">
                        <div class="comment-user"><?php echo $comment["Fname"]." ".$comment["Lname"] ?></div>
                        <div class="comment-date" data-Pdate="<?php echo $comment["created_at"] ?>"></div>
                        <div class="comment-text"><?php echo $comment["content"] ?></div>
                    </div>
                    <?php if ($loged_in && $role): ?>
                        <a href="do_comment.php?delete=<?php echo $comment['id'] ?>&post_id=<?php echo $comment['post_id'] ?>" class="delete-comment-btn"><i class="fa-solid fa-trash"></i></a>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            </div>
            <?php
            if ($count > $amount) {
                echo '<a id="showMoreBtn" href="post-link.php?post_id='.$id.'&limit='.($amount + 10).'" style="display:block;">See More Comments</a>';
            }else{
                echo '<a id="showMoreBtn" href="post-link.php?post_id='.$id.'&limit='.($amount + 10).'" style="display:none;">See More Comments</a>';
            }
            ?>

            <!-- ADD COMMENT FORM -->
            <form class="add-comment-form" id="addCommentForm" action="do_comment.php" method="POST">
                <label for="commentText"><i class="fa-regular fa-comment-dots"></i> Comment</label>
                <textarea id="commentText" name="comment" rows="3" required maxlength="300" placeholder="Write your comment..."></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" class="add-comment-btn"><i class="fa-solid fa-paper-plane"></i> Post Comment</button>
                <div id="commentMsg" style="margin:10px 0 0 0;font-size:15px;color:var(--primary);"></div>
            </form>
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

    <!-- COMMENTS SHOW/HIDE SCRIPT + ADD COMMENT DYNAMIC -->
    <script>
        let str_date = <?php echo '"'.$post['created_at'].'"'; ?>;

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
        let diff = TimeAgo(str_date);
        document.querySelector('.post-single-date').textContent = diff;

        document.querySelectorAll('.comment-date').forEach((el) => {
            let commentDate = TimeAgo(el.dataset.pdate);
            el.textContent = commentDate;
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