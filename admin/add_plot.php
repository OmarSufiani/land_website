<?php
session_start();
include '../databases/db.php'; // your database connection

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image     = trim($_POST['image']);
    $details   = trim($_POST['details']);
    $price     = trim($_POST['price']);
    $location  = trim($_POST['location']);
    $video_url = trim($_POST['video_url']);

    // ✅ Check for duplicates (based on details + location)
    $stmt = $conn->prepare("SELECT id FROM plots WHERE details = ? AND location = ?");
    $stmt->bind_param("ss", $details, $location);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "<div class='alert alert-danger'>❌ This plot already exists in $location.</div>";
    } else {
        // ✅ Insert new plot
        $stmt = $conn->prepare("INSERT INTO plots (image, details, price, location, video_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $image, $details, $price, $location, $video_url);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Plot added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>

<head>

  <title>Add Plot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<a href="dashboard.php" class="btn btn-primary"><--- Dashboard</a><br><br>
  <div class="card shadow p-4 rounded-4">
    <h3 class="mb-4 text-primary">Add New Plot</h3>
    <?= $message ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Image URL</label>
        <input type="text" name="image" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Details</label>
        <textarea name="details" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="text" name="price" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Video URL</label>
        <input type="text" name="video_url" class="form-control">
      </div>
 <button type="submit" class="btn btn-primary">Add Plot</button>
<a href="dashboard.php" class="btn btn-secondary">Cancel</a>

    </form>
  </div>
</div>

</body>
</html>
