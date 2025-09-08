<?php
include 'databases/db.php';
include 'includes/header.php';

// ✅ Fetch plots
$sql = "SELECT id, image, details, price, location FROM plots ORDER BY id DESC";
$result = $conn->query($sql);
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<!-- 🚀 Image Carousel -->
<div id="plotCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
  <div class="carousel-inner text-center">
    <!-- Replace these with your images -->
    <div class="carousel-item active">
      <img src="uploads/images/plot1.jpg" class="d-block mx-auto img-fluid" alt="Plot 1">
    </div>
    <div class="carousel-item">
      <img src="uploads/images/plot2.jpg" class="d-block mx-auto img-fluid" alt="Plot 2">
    </div>
    <div class="carousel-item">
      <img src="uploads/images/plot3.jpg" class="d-block mx-auto img-fluid" alt="Plot 3">
    </div>
  </div>
</div>


<!-- 🚀 Plots in Cards -->
<div class="container my-5">
  <h3 class="text-center mb-4 text-primary">Available Plots</h3>
  <div class="row g-4">

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card shadow h-100">
<img src="uploads/images/<?= htmlspecialchars(basename($row['image'])) ?>" 
     class="card-img-top" 
     alt="Plot Image">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['location']) ?></h5>
              <p class="card-text"><strong>Price:</strong> <?= htmlspecialchars($row['price']) ?></p>
              <p class="card-text text-truncate"><?= htmlspecialchars($row['details']) ?></p>
              <a href="plot_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">View More Details</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info">No plots available at the moment.</div>
      </div>
    <?php endif; ?>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/footer.php'; ?>
