<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = :email OR phone = :phone) AND id != :user_id");
    $stmt->execute(['email' => $email, 'phone' => $phone, 'user_id' => $user_id]);
    if ($stmt->rowCount() > 0) {
        echo "Email or phone number already exists!";
        exit;
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone, email = :email, password = :password WHERE id = :user_id");
        $stmt->execute(['name' => $name, 'phone' => $phone, 'email' => $email, 'password' => $hashed_password, 'user_id' => $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone, email = :email WHERE id = :user_id");
        $stmt->execute(['name' => $name, 'phone' => $phone, 'email' => $email, 'user_id' => $user_id]);
    }

    echo "Profile updated successfully!";
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();
?>

<a href="index.php">Home</a>
<form method="POST" style="margin-top: 20px;">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
    <label for="phone">Phone:</label><br>
    <input type="text" id="phone" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
    <label for="password">New Password (optional):</label><br>
    <input type="password" id="password" name="password" placeholder="New Password"><br><br>
    <button type="submit">Update Profile</button>
</form>
