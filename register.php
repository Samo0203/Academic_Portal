<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="register-container">
    <form class="register-form" method="post" action="">
      <h2>User Registration</h2>
      <?php
      $message = "";
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
          $name = trim($_POST['name']);
          $email = trim($_POST['email']);
          $password = $_POST['password'];
          $role = $_POST['role'];

          if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
              $message = "<p class='message error'>Password must be at least 8 characters and include a number.</p>";
          } else {
              $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
              $check->bind_param("s", $email);
              $check->execute();
              $check->store_result();

              if ($check->num_rows > 0) {
                  $message = "<p class='message error'>This email is already registered. Try logging in instead.</p>";
              } else {
                  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                  $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                  $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
                  $stmt->execute();
                  $message = "<p class='message success'>Registration successful! You can now <a href='login.php'>log in</a>.</p>";
              }
          }
      }
      echo $message;
      ?>
      <label for="name">Name</label>
      <input type="text" name="name" required>
      <label for="email">Email</label>
      <input type="email" name="email" required>
      <label for="password">Password</label>
      <input type="password" name="password" required>
      <label for="role">Role</label>
      <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="student">Student</option>
        <option value="lecturer">Lecturer</option>
      </select>
      <button type="submit" name="register">Register</button>
      <div class="login-link">
        Already registered? <a href="login.php">Login here</a>
      </div>
    </form>
  </div>
</body>
</html>