<?php
include 'databases/db.php'; // adjust path if needed

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Plot ID.");
}

$id = intval($_GET['id']);

// Fetch plot details
$stmt = $conn->prepare("SELECT * FROM plots WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Plot not found.");
}

$plot = $result->fetch_assoc();
$stmt->close();

// ✅ Fix paths (only video now)
$videoPath = !empty($plot['video_url']) ? "uploads/videos/" . basename($plot['video_url']) : "";

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $phone      = trim($_POST['phone_number']);
    $location   = trim($_POST['location']);
    $txtMessage = trim($_POST['message']);

    if ($name && $phone && $txtMessage) {
        $stmt = $conn->prepare("INSERT INTO info (plot_id, name, phone_number, location, created_at, message) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("issss", $id, $name, $phone, $location, $txtMessage);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Thank you, your message has been sent!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>⚠️ Please fill in all required fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Plot Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <a href="index.php" class="btn btn-secondary mb-3">← Back to Listings</a>

  <div class="card shadow-lg rounded-4 mb-4">
    <div class="card-body">
      <h3 class="card-title text-primary"><?= htmlspecialchars($plot['location']) ?></h3>
      <p><strong>Price:</strong> <?= htmlspecialchars($plot['price']) ?></p>
      <p><?= nl2br(htmlspecialchars($plot['details'])) ?></p>

      <?php if ($videoPath): ?>
        <div class="mt-3">
          <video controls 
                 style="width:100%; max-height:400px; object-fit:contain; border-radius:0.75rem;">
            <source src="<?= htmlspecialchars($videoPath) ?>" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
      <?php else: ?>
        <p class="text-muted">No video available for this plot.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ✅ Contact Form -->
  <div class="card shadow-sm rounded-4">
    <div class="card-body">
      <h4 class="mb-3 text-success">Leave a Message</h4>
      <?= $message ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Name*</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone Number*</label>
          <input type="text" name="phone_number" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" >
        </div>
        <div class="mb-3">
          <label class="form-label">Message*</label>
          <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
