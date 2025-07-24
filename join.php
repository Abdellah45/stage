<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Mental First</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: var(--gray);
        }
        .join-container {
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
        .join-container h2 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .join-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .join-form input,
        .join-form textarea {
            padding: 0.7rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        .join-form input:focus,
        .join-form textarea:focus {
            border-color: var(--primary);
            outline: none;
        }
        .join-form textarea {
            min-height: 70px;
            resize: vertical;
            font-family: inherit;
            line-height: 1.5;
        }
        .char-counter {
            text-align: right;
            font-size: 0.93rem;
            color: #888;
            margin-top: -0.7rem;
            margin-bottom: 0.5rem;
        }
        .join-btn-main {
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
        .join-btn-main:hover {
            background: var(--accent);
        }
        .already-account {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: #555;
        }
        .already-account a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            margin-left: 0.3rem;
        }
        .already-account a:hover {
            text-decoration: underline;
            color: var(--secondary);
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
        .join-container {
            position: relative;
        }
        .error-message{
            background: #f8d7da;
            color: #721c24;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <a href="index.php" class="close-btn" title="Back to Home">
        <i class="fas fa-times"></i>
    </a>
    <div class="join-container">
        <h2>Join Mental First</h2>
        <?php 
        if (isset($_SESSION["error"])) {
            echo '<div class="error-message">'.htmlspecialchars($_SESSION["error"]).'</div>';
            unset($_SESSION["error"]);
        }
        ?>
        <form class="join-form" action="do_join.php" method="post" autocomplete="off">
            <input type="text" name="First_name" placeholder="First name" required minlength="3" maxlength="32">
            <input type="text" name="Last_name" placeholder="Last name" required minlength="3" maxlength="32">
            <input type="text" name="username" placeholder="Username" required minlength="3" maxlength="32">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required minlength="6">
            <input type="password" name="password_confirm" placeholder="Confirm Password" required minlength="6">
            <textarea name="bio" id="bio" maxlength="160" placeholder="Tell us a bit about yourself (max 160 characters)" required></textarea>
            <div class="char-counter" id="bioCounter">0/160</div>
            <button type="submit" class="join-btn-main">Sign Up</button>
        </form>
        <div class="already-account">
            Already have an account?
            <a href="login.php">Log in</a>
        </div>
    </div>
    <script>
        // Character counter for bio textarea
        const bio = document.getElementById('bio');
        const bioCounter = document.getElementById('bioCounter');
        function updateBioCounter() {
            bioCounter.textContent = (bio.value.length) + '/160';
        }
        bio.addEventListener('input', updateBioCounter);
        updateBioCounter();
    </script>
</body>
</html>