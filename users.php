<?php
include "config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (strlen($password) < 8 || !preg_match('/\\d/', $password)) {
        $message = "<p class='message error'>Password must be at least 8 characters and include a number.</p>";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if (!empty($_POST['id'])) {
            // Edit user
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $passwordHash, $role, $id);
            if ($stmt->execute()) {
                $message = "<p class='message success'>User updated successfully.</p>";
            } else {
                $message = "<p class='message error'>Failed to update user.</p>";
            }
        } else {
            // Add user
            // Check email uniqueness
            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $message = "<p class='message error'>Email already exists.</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);
                if ($stmt->execute()) {
                    $message = "<p class='message success'>New user added successfully.</p>";
                } else {
                    $message = "<p class='message error'>Failed to add user.</p>";
                }
            }
        }
    }
}

$users = $conn->query("SELECT id, name, email, role FROM users ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Management</title>
  
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
    h1 { color: maroon; text-align: center; }
    form {
      max-width: 400px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
    button { margin-top: 15px; width: 100%; padding: 10px; background: maroon; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #800000; }
    table {
      width: 90%; margin: 20px auto; border-collapse: collapse; background: white; box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
    th { background: maroon; color: white; }
    .edit-btn {
      background: #0056b3; color: white; border: none; padding: 5px 12px; border-radius: 5px; cursor: pointer;
    }
    .edit-btn:hover { background: #003d80; }
    .message { text-align: center; font-size: 14px; margin-bottom: 10px; }
    .message.success { color: green; }
    .message.error { color: red; }
    a {
      color: maroon; font-weight: bold; text-decoration: none; margin-left: 10px;
    }
  </style>
  <script>
    function editUser(id, name, email, role) {
      document.getElementById('id').value = id;
      document.getElementById('name').value = name;
      document.getElementById('email').value = email;
      document.getElementById('role').value = role;
      document.getElementById('password').value = "";
      window.scrollTo(0, 0);
    }
    function clearForm() {
      document.getElementById('id').value = "";
      document.getElementById('name').value = "";
      document.getElementById('email').value = "";
      document.getElementById('role').value = "";
      document.getElementById('password').value = "";
    }
  </script>
</head>
<body>
  <a href="dashboard.php"><< Back To Home</a>
  <h1>User Management</h1>

  <?= $message ?>

  <form method="post" action="">
    <input type="hidden" id="id" name="id" value="">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="role">Role</label>
    <select id="role" name="role" required>
      <option value="" disabled selected>Select role</option>
      <option value="student">Student</option>
      <option value="lecturer">Lecturer</option>
      <option value="admin">Admin</option>
    </select>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter new password" required>

    <button type="submit">Save User</button>
    <button type="button" onclick="clearForm()">Clear</button>
  </form>

  <table>
    <thead>
      <tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while ($user = $users->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
          <button class="edit-btn" onclick="editUser('<?= $user['id'] ?>', '<?= addslashes(htmlspecialchars($user['name'])) ?>', '<?= addslashes(htmlspecialchars($user['email'])) ?>', '<?= $user['role'] ?>')">Edit</button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
