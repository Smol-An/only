<?php
session_start();
?>

<h1>Welcome to the Home Page</h1>
<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="register.php">Register</a><br>
    <a href="login.php">Login</a>
<?php else: ?>
    <a href="profile.php">Profile</a><br>
    <a href="logout.php">Logout</a>
<?php endif; ?>
