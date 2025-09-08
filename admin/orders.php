<?php
session_start();
include '../databases/db.php'; // database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch all info records with joined plots table
$sql = "
    SELECT i.id, i.name, i.phone_number, i.created_at, i.message, i.location,
           p.location AS plot_name
    FROM info i
    LEFT JOIN plots p ON i.plot_id = p.id
    ORDER BY i.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <a href="dashboard.php" class="btn btn-primary"><--- Dashboard</a><br><br>
  <div class="card shadow p-4 rounded-4">
    <h3 class="mb-4 text-primary">Information Records</h3>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Plot Name</th>
              <th>Name</th>
              <th>Phone Number</th>
              <th>Location (User)</th>
              <th>Message</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['plot_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['phone_number']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No records found.</div>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
