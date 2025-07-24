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
    header("Location: index.php");
    exit();
}
$user = $_SESSION["user"];
$role = $user->role;
$username = $user->username;
$full_name = $user->prenome . " " . $user->nom;
$filename = $user->image;
$bio = $user->bio;
$email = $user->email;
$image = $user->image;
$upload_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($image != "pic_empty.png") {
            unlink("p_imgs/$image");
        }
    $targetDir = "p_imgs/"; // Make sure this folder exists and is writable
    $filename = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", basename($_FILES['fileToUpload']['name']));
    $targetFile = "p_imgs/" . $filename;
    $user->image = $filename;
    $_SESSION["user"] = $user;
    $pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
    $sql = "UPDATE users SET prof_img = :image WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':image', $user->image);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if (!(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))){
        $upload_error =  "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .profile-container {
            max-width: 460px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(44,110,73,0.07);
            padding: 40px 24px 32px 24px;
            text-align: center;
            position: relative;
        }
        .profile-pic {
            width: 128px;
            height: 128px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--accent);
            margin-bottom: 19px;
            background: #f5f6fa;
            display: inline-block;
            position: relative;
        }
        .edit-pic-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            position: absolute;
            right: calc(50% - 64px - 12px);
            top: 116px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(31,75,110,0.12);
            font-size: 1.1em;
            transition: background 0.2s;
        }
        .edit-pic-btn label{
            position: absolute;
            top:50%;
            left:50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
        }
        .edit-pic-btn:hover {
            background: var(--secondary);
        }
        .profile-username {
            color: var(--secondary);
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .profile-fullname {
            font-size: 1.6em;
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--primary);
        }
        .profile-email {
            color: #888;
            font-size: 1em;
            margin-bottom: 18px;
        }
        .profile-bio {
            color: #444;
            font-size: 1.05em;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        .profile-actions {
            margin-top: 16px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .profile-actions a {
            background: var(--accent);
            color: #fff;
            border-radius: 32px;
            padding: 9px 28px;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            transition: background 0.2s;
            border: none;
            display: inline-block;
        }
        .profile-actions a:hover {
            background: var(--secondary);
        }
        /* Modal Styles */
        .modal-bg {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(44,110,73,0.14);
            align-items: center; justify-content: center;
        }
        .modal-bg.active { display: flex; }
        .modal-content {
            background: #fff;
            padding: 32px 24px 24px 24px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(31,75,110,0.12);
            min-width: 290px;
            max-width: 90vw;
            text-align: center;
            position: relative;
        }
        .modal-content h3 {
            color: var(--secondary);
            margin-bottom: 16px;
        }
        .modal-content input[type="file"] {
            margin-bottom: 16px;
            font-size: 1em;
        }
        .close-modal {
            position: absolute;
            right: 16px; top: 14px;
            background: none;
            border: none;
            font-size: 22px;
            color: #444;
            cursor: pointer;
        }
        .upload-error {
            color: #d7263d;
            font-size: 15px;
            margin-bottom: 12px;
        }
        @media (max-width: 600px) {
            .profile-container {
                padding: 24px 8px;
            }
            .profile-pic {
                width: 88px;
                height: 88px;
            }
            .edit-pic-btn {
                width: 30px;
                height: 30px;
                font-size: 1em;
                right: calc(50% - 44px - 10px);
                top: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar (same as home) -->
    <nav class="main-nav">
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="nav-links">
            <?php 
            if ($role){
                echo "<li><a href='dashboard.php'>Dashboard</a></li>";
            }
            ?>
            <li><a href="index.php">Home</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <a href="profile.php" class="prof">
            <span><?php echo $username ?></span>
            <img src="<?php echo"p_imgs\\".$user->image ?>" alt="Profile">
        </a>
    </nav>

    <!-- Profile Section -->
    <section>
        <div class="profile-container">
            <img class="profile-pic" src="<?php echo"p_imgs\\".$user->image ?>" alt="Profile Picture" id="profilePicMain">
            <button class="edit-pic-btn" title="Edit Profile Photo" id="editPicBtn">
                <i class="fa-solid fa-camera"></i>
            </button>
            <div class="profile-username">@<?php echo $username ?></div>
            <div class="profile-fullname"><?php echo $full_name ?></div>
            <div class="profile-email"><i class="fa-solid fa-envelope"></i><?php echo $email ?></div>
            <div class="profile-bio"><?php echo $bio ?></div>
            <div class="profile-actions">
                <a href="logout.php" style="background:var(--secondary)"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
            </div>
        </div>
    </section>
    <!-- Modal for uploading new profile picture -->
    <div class="modal-bg" id="modalBg">
        <div class="modal-content">
            <button class="close-modal" id="closeModalBtn" title="Close">&times;</button>
            <h3>Edit Profile Picture</h3>
            <?php if ($upload_error): ?>
                <div class="upload-error"><?php echo $upload_error; ?></div>
            <?php endif; ?>
            <div class="up_path"></div>
            <form method="post" enctype="multipart/form-data">
                <input type="file" id="profilePicInput" name="fileToUpload" accept="image/*" required style="visibility: hidden">
                <br>
                <button type="submit" class="hero-btn primary"><i class="fa-solid fa-upload"></i> Upload</button>
            </form>
            <p style="color:#888; margin-top:8px; font-size:14px;">Only JPG, PNG, GIF (max 3MB)</p>
        </div>
    </div>
    <script>
        let modalBg = document.getElementById('modalBg');
        let editPicBtn = document.getElementById('editPicBtn');
        let closeModalBtn = document.getElementById('closeModalBtn');
        let fileInput = document.getElementById('profilePicInput');
        let path_up = document.querySelector('.up_path');
        editPicBtn.addEventListener('click', function() {
            fileInput.click(); 
        });
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            path_up.innerHTML = file.name;
            modalBg.classList.add('active');
        });
        closeModalBtn.addEventListener('click', function() {
            modalBg.classList.remove('active');
        });

    </script>

    <!-- Footer (reuse from home page) -->
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