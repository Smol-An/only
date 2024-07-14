<?php
require 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    if ($password !== $repeat_password) {
        echo "Passwords do not match!";
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR phone = :phone");
    $stmt->execute(['email' => $email, 'phone' => $phone]);
    if ($stmt->rowCount() > 0) {
        echo "Email or phone number already exists!";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, phone, email, password) VALUES (:name, :phone, :email, :password)");
    if ($stmt->execute(['name' => $name, 'phone' => $phone, 'email' => $email, 'password' => $hashed_password])) {
        echo "Registration successful!";
        header('Location: login.php');
        exit;
    } else {
        echo "Registration failed!";
    }
}
?>

<a href="index.php">Home</a>
<form method="POST" style="margin-top: 20px;">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" placeholder="Name" required><br><br>
    <label for="phone">Phone:</label><br>
    <input type="text" id="phone" name="phone" placeholder="Phone" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" placeholder="Email" required><br><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" placeholder="Password" required><br><br>
    <label for="repeat_password">Repeat Password:</label><br>
    <input type="password" id="repeat_password" name="repeat_password" placeholder="Repeat Password" required><br><br>
    <button type="submit">Register</button>
</form>
