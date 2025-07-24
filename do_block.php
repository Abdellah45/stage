<?php 
if (!isset($_POST["username"])){
    header("location: index.php");
    exit();
}
$username = $_POST["username"];
echo "this is it".$username; 
$pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
$result = $pdo->query("SELECT prof_img FROM users WHERE username=\"$username\"")->fetch(PDO::FETCH_ASSOC);
try{
    $stmt = $pdo->prepare("DELETE FROM users WHERE username= :id");
    $stmt->bindParam(':id',$username,PDO::PARAM_STR);
    $stmt->execute();
    print_r($result);
    if ($result["prof_img"] != "pic_empty.png") {
            unlink("p_imgs/".$result["prof_img"]);
        }
    header("location: users.php");
}catch (PDOException $e){
    header("location: index.php");
}

?>