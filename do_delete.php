<?php 
if (!isset($_GET["post_id"])){
    header("location: posts.php");
    exit();
}
$post_id = $_GET["post_id"];
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$stmt = $pdo->prepare("DELETE FROM posts WHERE id= :id");
$stmt->bindParam(':id',$post_id,PDO::PARAM_INT);
$stmt->execute();
header("location: posts.php");
?>