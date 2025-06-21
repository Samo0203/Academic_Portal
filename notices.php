<?php
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_POST['post']) && in_array($_SESSION['user']['role'], ['admin', 'lecturer'])) {
    if (!is_dir('upload')) {
        mkdir('upload', 0777, true);
    }

    $filePath = "";
    if (!empty($_FILES["file"]["name"])) {
        $filename = basename($_FILES["file"]["name"]);
        $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filePath = "upload/" . time() . "_" . $filename;
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
                $message = "<p class='message error'>Failed to upload attachment.</p>";
            }
        } else {
            $message = "<p class='message error'>File type not allowed. Allowed types: pdf, jpg, png, doc, txt.</p>";
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO notices (title, description, type, file) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['title'], $_POST['description'], $_POST['type'], $filePath);
        $stmt->execute();
        $message = "<p class='message success'>Notice posted successfully.</p>";
    }
}

if (isset($_POST['del']) && in_array($_SESSION['user']['role'], ['admin', 'lecturer'])) {
    $id = intval($_POST['del']);
    $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = "<p class='message success'>Notice deleted successfully.</p>";
}

$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Notice Board</title>
  
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; margin: 0; }
    h1 { text-align: center; color: maroon; margin-bottom: 30px; }
    form.notice-form {
      background: #fff; padding: 25px; margin: 0 auto 30px; max-width: 700px; border: 2px solid maroon; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    input, textarea, select {
      width: 100%; padding: 10px; margin-top: 8px; margin-bottom: 18px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;
    }
    button {
      background-color: maroon; color: #fff; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-size: 16px;
    }
    button:hover { background-color: #800000; }
    .notice {
      background: white; border-left: 6px solid maroon; margin: 20px auto; padding: 20px; max-width: 700px; border-radius: 6px; box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    h3 { margin: 0; color: maroon; }
    p { margin: 10px 0; }
    a { color: #800000; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }
    .delete-form { margin-top: 10px; background: transparent; padding: 0; border: none; }
  </style>
</head>
<body>
  <a href="dashboard.php"><< Back To Home</a>
  <h1>Notice Board</h1> 

  <?= $message ?>

  <?php if (in_array($_SESSION['user']['role'], ['admin', 'lecturer'])): ?>
  <form method="post" enctype="multipart/form-data" class="notice-form">
      <label>Title:</label>
      <input name="title" placeholder="Enter Notice Title" required>

      <label>Description:</label>
      <textarea name="description" placeholder="Enter Notice Description" required></textarea>

      <label>Type:</label>
      <select name="type" required>
          <option>General</option>
          <option>Exam</option>
          <option>Event</option>
      </select>

      <label>Attachment (optional):</label>
      <input type="file" name="file">

      <button type="submit" name="post">Post Notice</button>
  </form>
  <?php endif; ?>

  <?php
  while ($row = $notices->fetch_assoc()):
  ?>
  <div class="notice">
    <h3><?= htmlspecialchars($row['title']) ?> (<?= htmlspecialchars($row['type']) ?>)</h3>
    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    <?php if ($row['file']): ?>
      <a href="<?= htmlspecialchars($row['file']) ?>" target="_blank">Download Attachment</a><br>
    <?php endif; ?>

    <?php if (in_array($_SESSION['user']['role'], ['admin', 'lecturer'])): ?>
      <form method="post" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this notice?');">
        <input type="hidden" name="del" value="<?= $row['id'] ?>">
        <button type="submit">Delete</button>
      </form>
    <?php endif; ?>
  </div>
  <?php endwhile; ?>
</body>
</html>
