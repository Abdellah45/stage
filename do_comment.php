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
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == "GET" ){
    if (isset($_GET["delete"])) {
        $commentId = $_GET["delete"];
        $pdo = new PDO('mysql:host=localhost;dbname=amosoblog', 'root', '');
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        header("Location: post-link.php?post_id=" . $_GET['post_id']);
        exit();
    }
}
$user = $_SESSION["user"];
$username = $user->username;
$content = $_POST["comment"];
$post_id = $_POST["post_id"];
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$sql = "INSERT INTO comments (username, content, post_id)
VALUES (:username, :content, :post_id)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username'    => $username,
    ':content' => $content,
    ':post_id' => $post_id
]);
header("Location: post-link.php?post_id=" . $post_id);
exit();
?>