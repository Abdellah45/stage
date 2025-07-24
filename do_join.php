<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nom = $_POST["Last_name"];
    $prenome = $_POST["First_name"];
    $bio = $_POST["bio"];
    $conf_pass = $_POST["password_confirm"];
    // Validate username
    $pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
    $sql = "SELECT * FROM users WHERE username =\"$username\"";
    $result = $pdo->query($sql);
    if (!(preg_match('/^[a-zA-Z][a-zA-Z0-9_]{2,19}$/', $username))) {
        $_SESSION["error"] = "invalid username.";
        header("location: join.php");
        exit();
    }else if (!(preg_match('/^[a-zA-Z][a-zA-Z0-9_]{2,19}$/', $prenome))) {
        $_SESSION["error"] = "invalid First name.";
        header("location: join.php");
        exit();
    }else if (!(preg_match('/^[a-zA-Z][a-zA-Z0-9_]{2,19}$/', $nom))) {
        $_SESSION["error"] = "invalid Last name.";
        header("location: join.php");
        exit();
    }else if ($result->rowCount() > 0) {
            $_SESSION["error"] = "Username already exists.";
            header("location: join.php");
            exit();
    }else if($password !== $conf_pass){
        $_SESSION["error"] = "invalid password";
        header("location: join.php");
        exit();
    }else{
        try {
            $pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
            $sql = "INSERT INTO users (Fname, Lname, username, email, password, bio, prof_img)
            VALUES (:fname, :lname, :username, :email, :password, :bio,:prof_img)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':fname'    => $prenome,
                ':lname'    => $nom,
                ':username' => $username,
                ':email'    => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':bio'      => $bio,
                ':prof_img'    => "pic_empty.png",
            ]);
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
            $user = new User($username, $email, $nom, $prenome, password_hash($password, PASSWORD_DEFAULT), $bio, "pic_empty.png",false);
            $_SESSION["user"] = $user;
            header("Location: index.php");
            exit();
      } catch (PDOException $e) {
        $_SESSION["error"] = "Database error: " . $e->getMessage();
        header("location: join.php");
        exit();
      }
    }
}
?>

