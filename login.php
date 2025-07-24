<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
}else{
    $error = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Mental First</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: var(--gray);
        }
        .login-container {
            max-width: 400px;
            margin: 4rem auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(44,110,73,0.10);
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-container h2 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .login-form input {
            padding: 0.7rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        .login-form input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .login-btn-main {
            background: var(--primary);
            color: #fff;
            padding: 0.7rem 0;
            border-radius: 2rem;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }
        .login-btn-main:hover {
            background: var(--accent);
        }
        .no-account {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: #555;
        }
        .no-account a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            margin-left: 0.3rem;
        }
        .close-btn {
            position: fixed;
            top: 18px;
            right: 24px;
            font-size: 2.2rem;
            color: #fff;
            background: var(--accent);
            border-radius: 50%;
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 2px 12px rgba(44,110,73,0.13);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            z-index: 1000;
            border: 3px solid #fff;
        }
        .close-btn:hover {
            background: var(--secondary);
            color: #fff;
            box-shadow: 0 6px 24px rgba(44,110,73,0.18);
        }
        .no-account a:hover {
            text-decoration: underline;
            color: var(--secondary);
        }
        .error-message{
            color: red;
        }
    </style>
</head>
<body>
    <a href="index.php" class="close-btn" title="Back to Home">
        <i class="fas fa-times"></i>
    </a>
    <div class="login-container">
        <h2>Login to Mental First</h2>
        <form class="login-form" action="do_login.php" method="post">
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif;?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn-main">Log In</button>
        </form>
        <div class="no-account">
            Don't have an account?
            <a href="join.php">Sign up</a>
        </div>
    </div>
</body>
</html>