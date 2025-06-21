<?php
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['user']['role'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $role === 'admin' && isset($_POST['startDate'], $_POST['totalWeeks'])) {
    $startDate = $_POST['startDate'];
    $totalWeeks = intval($_POST['totalWeeks']);

    if ($totalWeeks < 10) {
        $message = "<p class='message error'>Total weeks must be at least 10.</p>";
    } else {
        $conn->query("DELETE FROM calendar"); // Clear previous calendar
        $start = new DateTime($startDate);

        for ($i = 0; $i < $totalWeeks; $i++) {
            $weekNum = $i + 1;
            $weekStart = clone $start;
            $weekStart->modify("+".($i * 7)." days");
            $weekEnd = clone $weekStart;
            $weekEnd->modify("+7 days");

            $description = "Academic Week";
            if ($weekNum === (int)($totalWeeks/2)) {
                $description = "Mid-Semester Vacation";
            } elseif ($weekNum === ($totalWeeks - 2)) {
                $description = "Study Leave";
            } elseif (in_array($weekNum, [$totalWeeks - 1, $totalWeeks])) {
                $description = "Final Exam Week";
            }

            $startStr = $weekStart->format("Y-m-d");
            $endStr = $weekEnd->format("Y-m-d");

            $stmt = $conn->prepare("INSERT INTO calendar (week_number, start_date, end_date, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $weekNum, $startStr, $endStr, $description);
            $stmt->execute();
        }

         echo "<script>alert('Academic calendar generated successfully!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Academic Calendar</title>
  
  <style>
    body { font-family: Arial; background: #eef3f8; padding: 40px; }
    h1 { text-align: center; color: maroon; }
    form { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    label, input { display: block; width: 95%; margin-top: 15px; }
    input, button { padding: 10px; font-size: 16px; border-radius: 5px; }
    button { background-color: maroon; color: white; border: none; margin-top: 15px; cursor: pointer; }
    button:hover { background-color: #800000; }
    table { width: 100%; margin-top: 30px; border-collapse: collapse; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    th { background: maroon; color: white; }
    tr.special td { background-color: #fef3c7; }
    tr.exam td { background-color: #fcd34d; }
    tr.study td { background-color: #c7f0f5; }
    a {
        color: #800000;
        text-decoration: none;
        font-weight: bold;
    }
  </style>
</head>
<body>
  <a href="dashboard.php"><< Back To Home</a>
  <h1>Academic Calendar</h1>

  <?= $message ?>

  <?php if ($role === 'admin'): ?>
  <form method="POST">
    <label for="startDate">Semester Start Date</label>
    <input type="date" name="startDate" required />

    <label for="totalWeeks">Total Number of Weeks</label>
    <input type="number" name="totalWeeks" min="10" required />

    <button type="submit">Generate Calendar</button>
  </form>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Week</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $calendar = $conn->query("SELECT * FROM calendar ORDER BY week_number");
      while ($row = $calendar->fetch_assoc()):
        $class = "";
        if ($row['description'] === 'Mid-Semester Vacation') $class = "special";
        else if ($row['description'] === 'Study Leave') $class = "study";
        else if ($row['description'] === 'Final Exam Week') $class = "exam";
      ?>
      <tr class="<?= $class ?>">
        <td>Week <?= $row['week_number'] ?></td>
        <td><?= htmlspecialchars($row['start_date']) ?></td>
        <td><?= htmlspecialchars($row['end_date']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
