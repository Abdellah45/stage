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
$edit = false;
if (isset($_GET["post_id"])) {
    $edit = true;
    $postId = $_GET["post_id"];
    // Fetch post data from database if editing
    $pdo = new PDO('mysql:host=localhost;dbname=amosoblog', 'root', '');
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post – Mental First</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f7fafc;
        }
        .write-wrapper {
            max-width: 650px;
            margin: 50px auto 0 auto;
            padding: 20px 18px 35px 18px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 20px rgba(51, 102, 204, 0.09);
        }
        .write-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #3366cc;
            letter-spacing: 1px;
            margin-bottom: 30px;
        }
        .write-form {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .form-label {
            font-weight: 500;
            color: #223366;
        }
        .form-input, .form-select, .form-textarea {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1.5px solid #cdd7ee;
            font-size: 1rem;
            background: #f7fafc;
            color: #223366;
            transition: border 0.17s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #3366cc;
            outline: none;
        }
        .form-select {
            min-width: 120px;
        }
        .form-textarea {
            min-height: 140px;
            resize: vertical;
        }
        .form-image-upload {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .image-preview {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            object-fit: cover;
            background: #d8e6fa;
            display: none;
        }
        .publish-btn {
            margin-top: 10px;
            display: inline-block;
            background: #3366cc;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 12px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(51,102,204,0.08);
            transition: background 0.17s;
        }
        .publish-btn:hover {
            background: #254a99;
        }
        @media (max-width: 700px) {
            .write-wrapper {
                max-width: 98vw;
                padding: 10px 4vw 18px 4vw;
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

    <div class="write-wrapper">
        <div class="write-title"><?php echo ($edit) ? "Edit Post" :  "New Post" ?></div>
        <form class="write-form" action="save_post.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" for="post-title">Title</label>
                <input type="text" class="form-input" id="post-title" name="title" value="<?php echo ($edit) ? $post["title"] : "" ?>" required placeholder="Enter post title">
            </div>
            <div class="form-group">
                <label class="form-label" for="post-category">Category</label>
                <select class="form-select" id="post-category" name="category" required>
                    <?php if ($edit): ?>
                        
                    <option value="">Select category</option>
                    <option <?php echo ($post["category"] == "Marketing") ? "selected" :  ""  ?> value="Marketing">Marketing</option>
                    <option <?php echo ($post["category"] == "Education") ? "selected" :  ""  ?> value="Education">Education</option>
                    <option <?php echo ($post["category"] == "Entertainment") ? "selected" :  ""  ?> value="Entertainment">Entertainment</option>
                    <option <?php echo ($post["category"] == "Wellness") ? "selected" :  ""  ?> value="Wellness">Wellness</option>
                    <?php else: ?>
                    <option value="">Select category</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Education">Education</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Wellness">Wellness</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Image</label>
                <div class="form-image-upload">
                    <input type="file" class="form-input" name="image" id="image-upload" accept="image/*" onchange="previewImage(event)">
                    <img id="image-preview" style="<?php echo ($edit) ? "display:block" : "" ?>" src="posts_imgs/<?php echo ($edit) ? $post["image"] : "" ?>" class="image-preview" alt="Preview">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="post-content">Content</label>
                <textarea class="form-textarea"  id="post-content" name="content"  placeholder="Write your post here...">
                    <?php echo ($edit) ? $post["content"] : "" ?>
                </textarea>
            </div>
            <input type="hidden" name="edit" value="<?php echo $edit ? 'true' : 'false'; ?>">
            <?php if ($edit): ?>
                <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
            <?php endif; ?>
            <button type="submit" class="publish-btn"><i class="fa fa-paper-plane"></i> <?php echo ($edit) ? "Edit" :  "Publish" ?></button>
        </form>
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
    <script src="https://cdn.tiny.cloud/1/4istsy1uewg5rebhi1gb1g94yedv4ikopl9qrs62oymcptjy/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#post-content'
    });
    </script>
    <script>
        // Preview image before upload
        function previewImage(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('image-preview');
            if (file) {
                preview.style.display = 'block';
                preview.src = URL.createObjectURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '';
            }
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