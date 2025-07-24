<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
    $sql = "SELECT * FROM users WHERE username =\"$username\"";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0){
        $row = $result->fetch();
        if (password_verify($password, $row['password'])) {
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
            $user = new User($row["username"], $row["email"], $row["Lname"], $row["Fname"], $row["password"], $row["bio"], $row["prof_img"],$row["role"]);
            $_SESSION["user"] = $user;
            if ($row["role"]){
                header("Location: dashboard.php");
                exit();
            }else{
                header("Location: index.php");
                exit();
            }
        }else{
            $_SESSION["error"] = "Invalid password.";
            header("Location: login.php");
            exit();
        }
    }else{
        $_SESSION["error"] = "Username does not exist.";
        header("Location: login.php");
        exit();
    }
}
?>