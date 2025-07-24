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
$amount = 10; // Number of users to display
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "SELECT COUNT(*) FROM users";
$result = $pdo->query($sql);
$count = $result->fetchColumn();
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pages_count = ceil($count / $amount);
$offset = ($current_page - 1) * $amount; // Calculate offset for pagination
$sql ="SELECT * FROM users ORDER BY created_at LIMIT $amount OFFSET $offset";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental First - Users</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f7fafc;
        }
        .users-wrapper {
            max-width: 1100px;
            margin: 40px auto 0 auto;
            padding: 18px;
        }
        .users-title {
            font-size: 2rem;
            color: #3366cc;
            margin-bottom: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-align: center;
        }
        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 18px;
        }
        .users-table th, .users-table td {
            text-align: left;
            padding: 16px 14px;
        }
        .users-table th {
            color: #3366cc;
            font-size: 1.03rem;
            font-weight: 600;
            background: #eaf1fc;
            border-radius: 7px 7px 0 0;
        }
        .users-table tr {
            background: #fff;
            box-shadow: 0 2px 10px rgba(51,102,204,0.05);
            border-radius: 10px;
            transition: box-shadow 0.2s;
        }
        .users-table tr:hover {
            box-shadow: 0 4px 16px rgba(51,102,204,0.10);
        }
        .user-profile-pic {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 4px rgba(51,102,204,0.10);
        }
        .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-username {
            color: #3366cc;
            font-weight: 600;
            font-size: 1.08rem;
            margin-bottom: 3px;
        }
        .user-fullname {
            color: #223366;
            font-size: 0.97rem;
        }
        .user-email {
            color: #666;
            font-size: 0.95rem;
        }
        .user-bio {
            color: #444;
            font-size: 0.96rem;
            font-style: italic;
            max-width: 320px;
            line-height: 1.45;
        }
        .block-btn {
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
        .block-btn:hover {
            background: #e63946;
            color: #fff;
        }
        .block-btn i {
            font-size: 1.09rem;
        }
        @media (max-width: 900px) {
            .users-table, .users-table thead, .users-table tbody, .users-table th, .users-table td, .users-table tr {
                display: block;
            }
            .users-table tr {
                margin-bottom: 18px;
                border-radius: 10px;
            }
            .users-table th {
                display: none;
            }
            .users-table td {
                padding: 12px 15px;
                position: relative;
            }
            .users-table td:before {
                content: attr(data-label);
                font-weight: bold;
                color: #3366cc;
                display: block;
                margin-bottom: 4px;
                font-size: 0.98rem;
            }
            .user-bio {
                max-width: 100%;
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

    <div class="users-wrapper">
        <div class="users-title">
            Users
        </div>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Username & Full Name</th>
                    <th>Email</th>
                    <th>Bio</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- User row 1 -->
                 <?php
        while($user = $result->fetch(PDO::FETCH_ASSOC)) :?>
                <tr>
                    <td data-label="Profile">
                        <img src="p_imgs\<?php echo $user["prof_img"] ?>" class="user-profile-pic" alt="<?php echo $user["Fname"]." ".$user["Lname"] ?>">
                    </td>
                    <td data-label="Username & Full Name">
                        <div class="user-info">
                            <span class="user-username">@<?php echo $user["username"] ?></span>
                            <span class="user-fullname"><?php echo $user["Fname"]." ".$user["Lname"] ?></span>
                        </div>
                    </td>
                    <td data-label="Email">
                        <span class="user-email"><?php echo $user["email"] ?></span>
                    </td>
                    <td data-label="Bio">
                        <span class="user-bio"><?php echo $user["bio"] ?></span>
                    </td>
                    <?php if ($user["role"]):?>
                    <td data-label="Action">
                        <button style="opacity: .4;pointer-events:none" class="block-btn" >
                            <i class="fa fa-ban"></i> Block
                        </button>
                    </td>
                    <?php else:?>
                        <td data-label="Action">
                            <form action="do_block.php" method="POST">
                                <input type="hidden" name="username" value="<?php echo $user["username"]?>">
                                <button style="" class="block-btn" type="submit">
                                    <i class="fa fa-ban"></i> Block
                                </button>
                            </form>
                    </td>
                    <?php endif;?>
                </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php
        if ($current_page > 1) {
            echo '<a href="?page=' . ($current_page - 1) . '">&laquo;</a>';
        }
        if ($pages_count > 1) {
            for ($i = 1; $i <= $pages_count; $i++) {
            if ($i == $current_page) {
                echo '<a href="?page=' . $i . '" class="active">' . $i . '</a>';
            } else {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }
        }
        if ($current_page < $pages_count) {
            echo '<a href="?page=' . ($current_page + 1) . '">&raquo;</a>';
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