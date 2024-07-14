<?php
require 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :login OR phone = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: profile.php');
        exit;
    } else {
        echo "Invalid login or password!";
    }
}
?>

<a href="index.php">Home</a>
<form method="POST" style="margin-top: 20px;">
    <label for="login">Email or Phone:</label><br>
    <input type="text" id="login" name="login" placeholder="Email or Phone" required><br><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>
