<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-container">
  <form class="register-form" method="post" action="">
    <h2>Login</h2>
    <?php
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if ($email === "admin@gmail.com" && $password === "admin123") {
            $_SESSION['user'] = [
                'id' => 0,
                'name' => 'Administrator',
                'email' => $email,
                'role' => 'admin'
            ];
            header("Location: dashboard.php");
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "<p class='message error'>Invalid email or password. Please try again.</p>";
        }
    }
    echo $message;
    ?>
    <label>Email</label>
    <input name="email" type="email" placeholder="Email" required>
    <label>Password</label>
    <input name="password" type="password" placeholder="Password" required>
    <button name="login">Login</button>
    <div class="login-link">
        Don't have an account? <a href="register.php">Register</a>
    </div>
  </form>
</div>
</body>
</html>