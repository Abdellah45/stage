<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    $pdo = new PDO ('mysql:host=localhost;dbname=amosoblog','root','');
    if ($_POST['edit'] === 'true') {
        $result = $pdo->query("SELECT * FROM posts WHERE id = {$_POST['post_id']}")->fetch(PDO::FETCH_ASSOC);
            // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($title) || empty($content) || empty($category)) {
        $_SESSION["publish_error"] = "All fields are required.";
        header("Location: write.php");
        exit();
    }

    // Handle image upload
    if ($image['error'] === UPLOAD_ERR_OK) {
        unlink("posts_imgs/$result[image]");
        $filename = basename($image['name']);
        $imagePath = 'posts_imgs/' . $filename;
        move_uploaded_file($image['tmp_name'], $imagePath);
    }else{
        $filename = $result['image'];
    }

    // Insert post into database
    try {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, category = ?, content = ?, image = ? WHERE id = ?");
        $stmt->execute([$title,  $category,$content, $filename,$_POST['post_id']]);
        $_SESSION["publish_success"] = "Post updated successfully!";
        header("Location: posts.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["publish_error"] = "Error saving post: " . $e->getMessage();
        header("Location: write.php");
        exit();
    }

    }else if ($_POST['edit'] === 'false') {
            // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($title) || empty($content) || empty($category)) {
        $_SESSION["publish_error"] = "All fields are required.";
        header("Location: write.php");
        exit();
    }

    // Handle image upload
    if ($image['error'] === UPLOAD_ERR_OK) {
        $filename = basename($image['name']);
        $imagePath = 'posts_imgs/' . $filename;
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = null;
        $_SESSION["publish_error"] = "Image upload failed.";
        header("Location: write.php");
        exit();
    }

    // Insert post into database
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, category, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $category, $filename]);
        $_SESSION["publish_success"] = "Post published successfully!";
        header("Location: posts.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["publish_error"] = "Error saving post: " . $e->getMessage();
        header("Location: write.php");
        exit();
    }
    }

} else {
    $_SESSION["publish_error"] = "Invalid request method.";
    header("Location: write.php");
    exit();
}
?>